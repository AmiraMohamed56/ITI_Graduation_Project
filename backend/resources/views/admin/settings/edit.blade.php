@extends('layouts.admin')

@section('title', 'Profile Settings')

@section('breadcrumb')
<a href="#" class="hover:underline">Dashboard</a> / <span>Profile</span>
@endsection

@section('content')

@if(session('success'))
    <x-admin.alert type="success" :message="session('success')" />
@endif

@if(session('error'))
    <x-admin.alert type="error" :message="session('error')" />
@endif






<x-admin.card>
    <h2 class="text-2xl font-bold mb-4 text-center">{{ $admin->name }} Profile</h2>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-4" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- profile picture --}}
        <div class="mb-4">
            @if ($admin->profile_pic)
            <div class="flex items-center justify-center">
                <img src="{{ asset('storage/profile_pics/' . $admin->profile_pic) }}" alt="profile picture" class="w-24 h-24 rounded-full mb-4">
            </div>
            @endif
            <div class="flex items-center justify-center">
                <x-admin.input label="Update Profile Picture" type="file" name="profile_pic" hidden/>
            </div>
            @error('profile_pic')
            <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
          @enderror
        </div>

        <x-admin.input label="Full Name" name="name" value="{{ old('name', $admin->name) }}"/>
        @error('name')
        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
        <x-admin.input label="Email Address" type="email" name="email" value="{{ old('email', $admin->email) }}"/>
        @error('email')
        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
        <x-admin.input label="Phone" name="phone" value="{{ old('phone', $admin->phone ?? '') }}"/>
        @error('phone')
        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
        <x-admin.input label="Password" type="password" name="password"/>
        @error('password')
        <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
        @enderror
        <x-admin.input label="Confirm Password" type="password" name="password_confirmation"/>

        <div class="flex justify-end">
            <x-admin.button type="primary">Save Changes</x-admin.button>
        </div>
    </form>
</x-admin.card>
@endsection
