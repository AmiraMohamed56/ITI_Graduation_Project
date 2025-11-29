import { Component, OnInit } from "@angular/core";
import { AppointmentService } from "../services/appointment.service";
import { scheduled } from "rxjs";

@Component({
  selector: 'app-booking',
  templateUrl: './booking.component.html',
  styleUrls: ['./booking.component.css']
})

export class BookingComponent implements OnInit {

  specialities: any[] = [];
  doctors: any[] = [];
  availableTimes: {time: string, scheduleId: number}[] = [];
  selectedScheduleId: number | null = null;
  patient_id = 1;
  availableDates: any[] = [];

  selectedSpecialityId: number | null = null;
  selectedDoctorId: number | null = null;
  selectedDate: string | '' = '';
  selectedTime: string | '' = '';
  appointmentType: string = 'consultation';
  notes: string = '';

  constructor(private appointmentService: AppointmentService) {}

  ngOnInit(): void {
    this.loadSpecialities();
  }

  loadSpecialities() {
    // fetch from api
    this.specialities = [
      {id: 1, name: 'Cardiology'},
      {id: 2, name: 'Dermatology'},
      {id: 3, name: 'Neurology'}
    ];
  }

  onSpecialityChange() {
    this.loadDoctors();
  }

  loadDoctors() {
    if(!this.selectedSpecialityId) return;

    this.appointmentService.getDoctors().subscribe((res: any) => {
      this.doctors = res.filter((d: any) => d.speciality_id === this.selectedSpecialityId);
    });
  }

  onDoctorChange() {
    this.availableTimes = [];
    if(this.selectedDoctorId) {
      // this.loadAvailableTimes();
      this.loadAvailableDates();
    }
  }

  loadAvailableDates() {
    if (!this.selectedDoctorId) return;

    this.appointmentService.getDoctorSchedules(this.selectedDoctorId).subscribe((res: any) => {
      this.availableDates = [];
      res.forEach((d: any) => {
        this.availableDates.push(d.day_of_week);
      });

    });
  }

  onDateChange() {
    if (this.selectedDoctorId && this.selectedDate) {
      this.loadAvailableTimes();
    }
  }

  loadAvailableTimes() {
    this.appointmentService.getDoctorSchedule(this.selectedDoctorId!, this.selectedDate).subscribe((res: any) => {
      this.availableTimes = [];

      res.forEach((schedule: any) => {
        // each schedule has start_time and appointment_duration
        const start = new Date(`1970-01-01T${schedule.start_time}`);
        const end = new Date(`1970-01-01T${schedule.end_time}`);
        const duration = 20; // 20 minutes per appointment
        while (start < end) {
          const timeStr = start.toTimeString().slice(0, 5);
          this.availableTimes.push({time: timeStr, scheduleId: schedule.id});
          start.setMinutes(start.getMinutes() + duration);
        }
      });
    });
  }


  onTimeSelect(timeSlot: any) {
    this.selectedScheduleId = timeSlot.scheduleId;
    this.selectedTime = timeSlot.time;
  }

  submitBooking() {
    if(!this.selectedDoctorId || !this.selectedDate || !this.selectedTime) {
      return alert('please select doctor, date and time.');
    }

    const data = {
      patient_id: this.patient_id,
      doctor_id: this.selectedDoctorId,
      doctor_schedule_id: this.selectedScheduleId,
      schedule_date: this.selectedDate,
      schedule_time: this.selectedTime,
      type: this.appointmentType,
      notes: this.notes
    };

    this.appointmentService.bookAppointment(data).subscribe(res => {
      alert('appointment booked successfully.');
      // redirect to the confirmation
    }, err => {
      console.error(err);
      alert('Faild to book appointment.');
    });
  }
}
