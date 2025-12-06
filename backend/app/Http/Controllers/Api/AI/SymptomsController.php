<?php

namespace App\Http\Controllers\Api\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\SymptomAnalysis;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;

class SymptomsController extends Controller
{
    public function analyze(Request $request)
    {

        $request->validate([
            'symptoms' => 'required|string|min:3|max:2000',
            'age' => 'nullable|integer',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $symptoms = $request->input('symptoms');
        $age = $request->input('age');
        $gender = $request->input('gender');

        $prompt = "You are a clinical triage assistant. Given the patient's symptoms and demographics, return a JSON object with:
1) 'diagnoses' : array of up to 3 possible conditions each {name, confidence_percent (0-100), reasoning, recommended_specialty}
2) 'next_steps': short actionable next steps (labs, immediate actions)
3) 'urgency': one of ['emergency','urgent','routine']
Return ONLY valid JSON (no extra explanation).
Patient info:
Symptoms: {$symptoms}
Age: " . ($age ?? 'unknown') . "
Gender: " . ($gender ?? 'unknown') . "
Example:
{
  \"diagnoses\": [
    {\"name\":\"...\",\"confidence_percent\":75,\"reasoning\":\"...\",\"recommended_specialty\":\"Cardiology\"}
  ],
  \"next_steps\":[\"Do X\",\"Do Y\"],
  \"urgency\":\"routine\"
}";

        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');

        if (!$apiKey) {
            return response()->json(['status' => false, 'message' => 'OpenAI API key not configured.'], 500);
        }

        $model = env('OPENAI_MODEL', 'gpt-4o-mini');

        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful clinical assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.1,
                'max_tokens' => 700,
            ]);

        if ($response->failed()) {
            return response()->json([
                'status' => false,
                'message' => 'AI request failed',
                'detail' => $response->body()
            ], 500);
        }

        $text = $response->json('choices.0.message.content');

        // parse JSON from model output
        $parsed = null;
        $clean = trim($text);
        $clean = preg_replace('/^```(?:json)?\n/', '', $clean);
        $clean = preg_replace('/\n```$/', '', $clean);

        $parsed = json_decode($clean, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            if (preg_match('/\{.*\}/s', $clean, $m)) {
                $parsed = json_decode($m[0], true);
            }
        }

        $analysis = SymptomAnalysis::create([
            'user_id' => Auth::id(),
            'symptoms' => $symptoms,
            'ai_response' => $parsed ?? ['raw' => $text],
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'status' => true,
            'data' => [
                'analysis_id' => $analysis->id,
                'raw' => $text,
                'structured' => $parsed
            ]
        ]);
    }
}
