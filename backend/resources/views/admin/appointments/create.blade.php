@extends('layouts.admin')

@section('title', 'Create Appointment')

@section('breadcrumb')
    <a href="{{ route('admin.appointments.index') }}">Appointments</a> / Create
@endsection

@section('content')
@if ($errors->any())
    <div class="bg-red-500 text-white p-3 mb-4 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<x-admin.card>
    <h2 class="text-lg font-semibold mb-6">Create New Appointment</h2>

    <form action="{{ route('admin.appointments.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Patient --}}
        <div>
            <label>Patient</label>
            <select name="patient_id" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Doctor --}}
        <div>
            <label>Doctor</label>
            <select id="doctorSelect" name="doctor_id" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="">Select Doctor</option>
                @foreach($doctors as $doctor)
                    <option
                        value="{{ $doctor->id }}"
                        data-schedules='@json($doctor->schedules)'
                        data-appointments='@json($doctor->appointments)'
                    >
                        {{ $doctor->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Schedule Day --}}
        <div>
            <label>Schedule Day</label>
            <select id="scheduleSelect" name="doctor_schedule_id" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="">Select Day</option>
            </select>
        </div>

        {{-- Time Slot --}}
        <div>
            <label>Time Slot</label>
            <select id="timeSlotSelect" name="schedule_time" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="">Select Time</option>
            </select>
        </div>

        {{-- Type --}}
        <div>
            <label>Type</label>
            <select name="type" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="consultation">Consultation</option>
                <option value="follow_up">Follow Up</option>
                <option value="telemedicine">Telemedicine</option>
            </select>
        </div>

        {{-- Status --}}
        <div>
            <label>Status</label>
            <select name="status" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="completed">Completed</option>
            </select>
        </div>

        {{-- Price --}}
        <div>
            <label>Price</label>
            <input type="number" name="price" step="0.01" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        </div>

        {{-- Notes --}}
        <div>
            <label>Notes</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400"></textarea>
        </div>

        <div class="flex justify-end">
            <x-admin.button>Create Appointment</x-admin.button>
        </div>
    </form>
</x-admin.card>

<script>
const doctorSelect = document.getElementById('doctorSelect');
const scheduleSelect = document.getElementById('scheduleSelect');
const timeSlotSelect = document.getElementById('timeSlotSelect');

doctorSelect.addEventListener('change', function() {
    const schedules = JSON.parse(this.options[this.selectedIndex].dataset.schedules || "[]");
    const appointments = JSON.parse(this.options[this.selectedIndex].dataset.appointments || "[]");

    scheduleSelect.innerHTML = '<option value="">Select Day</option>';
    timeSlotSelect.innerHTML = '<option value="">Select Time</option>';

    schedules.forEach(schedule => {
        scheduleSelect.innerHTML += `<option value="${schedule.id}"
            data-start="${schedule.start_time}"
            data-end="${schedule.end_time}"
            data-duration="${schedule.appointment_duration}"
            data-day="${schedule.day_of_week}"
        >
            ${schedule.day_of_week.toUpperCase()} (${schedule.start_time} - ${schedule.end_time})
        </option>`;
    });
});

scheduleSelect.addEventListener('change', function() {
    const start = this.options[this.selectedIndex].dataset.start;
    const end = this.options[this.selectedIndex].dataset.end;
    const duration = parseInt(this.options[this.selectedIndex].dataset.duration);
    const scheduleId = this.value;

    const appointments = JSON.parse(doctorSelect.options[doctorSelect.selectedIndex].dataset.appointments || "[]");

    timeSlotSelect.innerHTML = '<option value="">Select Time</option>';
    if (!start || !end || !duration) return;

    let current = start;
    while(current < end){
        const isBooked = appointments.some(app =>
            app.doctor_schedule_id == scheduleId &&
            (app.schedule_time || '').slice(0,5) == current &&
            app.status != 'cancelled'
        );

        if(isBooked){
            timeSlotSelect.innerHTML += `<option value="${current}" disabled>${current} (Booked)</option>`;
        } else {
            timeSlotSelect.innerHTML += `<option value="${current}">${current}</option>`;
        }

        let [h,m] = current.split(':').map(Number);
        m += duration;
        while(m >= 60){ h++; m-=60; }
        current = String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0');
    }
});
</script>
@endsection
