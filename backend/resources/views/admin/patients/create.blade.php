@extends('admin.layout')

@section('title', 'Create Patient')

@section('content')
<h2 class="text-2xl font-bold mb-4">Add New Patient</h2>

<form action="{{ route('admin.patients.store') }}" method="POST" class="bg-white p-6 shadow rounded">
    @csrf

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label>Name</label>
            <input type="text" name="name" class="w-full border p-2" required>
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" class="w-full border p-2" required>
        </div>

        <div>
            <label>Phone</label>
            <input type="text" name="phone" class="w-full border p-2" required>
        </div>

        <div>
            <label>Password</label>
            <input type="password" name="password" class="w-full border p-2" required>
        </div>

        <div>
            <label>Blood Type</label>
            <input type="text" name="blood_type" class="w-full border p-2">
        </div>

        <div>
            <label>Chronic Diseases</label>
            <textarea name="chronic_diseases" class="w-full border p-2"></textarea>
        </div>
    </div>

    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Save</button>
</form>

@endsection
