<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Api\Patient\Mail\EmailVerificationCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Patients\ForgotPasswordRequest;
use App\Http\Requests\Api\Patients\PatientLoginRequest;
use App\Http\Requests\Api\Patients\PatientRegisterRequest;
use App\Http\Requests\Api\Patients\ResetPasswordRequest;
use App\Http\Requests\Api\Patients\VerifyCodeRequest;
use App\Http\Resources\Patient\PatientAuthResource;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Testing\Fluent\Concerns\Has;
use Pest\Support\Str;

class PatientAuthController extends Controller
{
    public function register(PatientRegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'patient',
            'password' => Hash::make($request->password),
        ]);

        $patient = Patient::create([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('patient_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Patient registered successfully',
            'token' => $token,
            'data' => new PatientAuthResource($patient),
        ], 201);
    }

    public function login(PatientLoginRequest $request)
    {
        $user = User::where('email', $request->email)
            ->where('role', 'patient')
            ->first();

        if (!$user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        
        if (!$user->patient) {
            Patient::create(['user_id' => $user->id]);
            $user->refresh(); 
        }

        $token = $user->createToken('patient_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'data' => new PatientAuthResource($user->patient),
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful',
        ]);
    }

    public function sendVerificationCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->firstOrFail();

        $code = mt_rand(100000, 999999);
        $expiresAt = now()->addMinutes(15);

        \DB::table('email_verifications')->insert([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($user->email)->send(new EmailVerificationCode($code, $user->name));

        return response()->json([
            'status' => true,
            'message' => 'Verification code sent to your email',
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $record = \DB::table('email_verifications')
            ->where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired code',
            ], 422);
        }

        \DB::table('email_verifications')
            ->where('id', $record->id)
            ->update(['used' => true]);

        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully',
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent to your email',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unable to send reset link',
        ], 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        // $user = User::where('email', $request->email)
        //     ->where('role', 'patient')
        //     ->first();

        // if (!$user) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'User not found',
        //     ], 404);
        // }

        // $tokenData = DB::table('password_reset_tokens')
        //     ->where('email', $request->email)
        //     ->first();

        // if (!$tokenData) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Invalid reset token',
        //     ], 404);
        // }

        // if (!hash_equals($tokenData->token, hash('sha256', $request->token))) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Invalid token',
        //     ], 400);
        // }


        $status = Password::reset(
            $request->only('email', 'token', 'password', 'password_confirmation'),

            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->setRememberToken(\Illuminate\Support\Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'message' => 'Password reset successful',
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid token or email',
        ], 400);
    }
}
