@extends('layouts.admin')

@section('title', 'Edit Appointment')

@section('breadcrumb')
    <a href="{{ route('admin.appointments.index') }}">Appointments</a> / Edit
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
    <h2 class="text-lg font-semibold mb-6">Edit Appointment</h2>

    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST" class="space-y-6">
        @csrf
        @method('PATCH')

        {{-- Patient --}}
        <div>
            <label>Patient</label>
            <select name="patient_id" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" @selected($patient->id == $appointment->patient_id)>{{ $patient->user->name }}</option>
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
                        @selected($doctor->id == $appointment->doctor_id)
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
                <option value="consultation" @selected($appointment->type=='consultation')>Consultation</option>
                <option value="follow_up" @selected($appointment->type=='follow_up')>Follow Up</option>
                <option value="telemedicine" @selected($appointment->type=='telemedicine')>Telemedicine</option>
            </select>
        </div>

        {{-- Status --}}
        <div>
            <label>Status</label>
            <select name="status" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
                <option value="pending" @selected($appointment->status=='pending')>Pending</option>
                <option value="confirmed" @selected($appointment->status=='confirmed')>Confirmed</option>
                <option value="cancelled" @selected($appointment->status=='cancelled')>Cancelled</option>
                <option value="completed" @selected($appointment->status=='completed')>Completed</option>
            </select>
        </div>

        {{-- Price --}}
        <div>
            <label>Price</label>
            <input type="number" name="price" step="0.01" value="{{ $appointment->price }}" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">
        </div>

        {{-- Notes --}}
        <div>
            <label>Notes</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400">{{ $appointment->notes }}</textarea>
        </div>

        <div class="flex justify-end space-x-2">
            <button type="button" onclick="window.history.back()" class="px-4 py-2 text-sm inline-flex items-center justify-center font-medium
            rounded-xl transition-all duration-200 select-none
            focus:outline-none focus:ring-2 focus:ring-offset-2
            disabled:opacity-60 disabled:cursor-not-allowed bg-gray-200 text-gray-800 hover:bg-gray-300 active:bg-gray-400
            focus:ring-gray-400 shadow-sm hover:shadow drop-shadow">
                Cancel
            </button>

            {{-- <x-admin.button type="secondary" onclick="window.history.back()">Cancel</x-admin.button> --}}
            <x-admin.button>Update Appointment</x-admin.button>
        </div>

    </form>
</x-admin.card>

<script>
const doctorSelect = document.getElementById('doctorSelect');
const scheduleSelect = document.getElementById('scheduleSelect');
const timeSlotSelect = document.getElementById('timeSlotSelect');
const selectedTime = "{{ \Carbon\Carbon::parse($appointment->schedule_time)->format('H:i') }}";

function generateSchedules() {
    const schedules = JSON.parse(doctorSelect.selectedOptions[0]?.dataset.schedules || "[]");
    scheduleSelect.innerHTML = '<option value="">Select Day</option>';
    schedules.forEach(s => {
        const selected = s.id == "{{ $appointment->doctor_schedule_id }}" ? 'selected' : '';
        scheduleSelect.innerHTML += `<option value="${s.id}" data-start="${s.start_time}" data-end="${s.end_time}" data-duration="${s.appointment_duration}" ${selected}>${s.day_of_week.toUpperCase()} (${s.start_time} - ${s.end_time})</option>`;
    });
    if(scheduleSelect.value) generateTimeSlots();
}

function generateTimeSlots() {
    const selectedOption = scheduleSelect.selectedOptions[0];
    if(!selectedOption) return;
    const start = selectedOption.dataset.start;
    const end = selectedOption.dataset.end;
    const duration = parseInt(selectedOption.dataset.duration);
    const scheduleId = scheduleSelect.value;
    const appointments = JSON.parse(doctorSelect.selectedOptions[0]?.dataset.appointments || "[]");

    timeSlotSelect.innerHTML = '<option value="">Select Time</option>';

    let [h, m] = start.split(':').map(Number);
    let current = `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;

    while(current < end){
        const isBooked = appointments.some(app => app.doctor_schedule_id == scheduleId && (app.schedule_time||'').slice(0,5) == current && app.status != 'cancelled');
        const selectedOption = current === selectedTime ? 'selected' : '';
        if(isBooked && !selectedOption){
            timeSlotSelect.innerHTML += `<option value="${current}" disabled>${current} (Booked)</option>`;
        } else {
            timeSlotSelect.innerHTML += `<option value="${current}" ${selectedOption}>${current}</option>`;
        }
        let [hh, mm] = current.split(':').map(Number);
        mm += duration;
        while(mm >= 60){ hh++; mm-=60; }
        current = `${String(hh).padStart(2,'0')}:${String(mm).padStart(2,'0')}`;
    }
}

// events
doctorSelect.addEventListener('change', generateSchedules);
scheduleSelect.addEventListener('change', generateTimeSlots);

// on load
if(doctorSelect.value) generateSchedules();
</script>
@endsection
