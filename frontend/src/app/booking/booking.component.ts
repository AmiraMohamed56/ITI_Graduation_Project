import { Component, OnInit } from '@angular/core';
import { AppointmentService } from '../services/appointment.service';
import { scheduled } from 'rxjs';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ToastService } from '../services/toast.service';
import { Router } from '@angular/router';
@Component({
  selector: 'app-booking',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './booking.component.html',
  styleUrls: ['./booking.component.css'],
})
export class BookingComponent implements OnInit {
  specialities: any[] = [];
  doctors: any[] = [];
  availableDates: any[] = [];
  availableTimes: { time: string; scheduleId: number, booked: boolean }[] = [];
  patient_id = 1;

  selectedSpecialityId: number | null = null;
  selectedDoctorId: number | null = null;
  selectedScheduleId: number | null = null;
  selectedDate: string | '' = '';
  selectedTime: string | '' = '';
  appointmentType: string = 'consultation';
  notes: string = '';

  constructor(private appointmentService: AppointmentService, private toastService: ToastService, private router: Router) {}

  ngOnInit(): void {
    this.loadSpecialities();
  }

  loadSpecialities() {
    // fetch from api
    // this.specialities = [
    //   { id: 1, name: 'Cardiology' },
    //   { id: 2, name: 'Dermatology' },
    //   { id: 3, name: 'Neurology' },
    // ];
    this.appointmentService.getSpecialities().subscribe((res: any) => {
      this.specialities = [];
      (res.data).forEach((s: any) => {
        this.specialities.push(s);
      });
    });
  }

  onSpecialityChange() {
    this.selectedSpecialityId = Number(this.selectedSpecialityId);
    this.loadDoctors();
  }

  loadDoctors() {
    if (!this.selectedSpecialityId) return;

    this.appointmentService.getDoctors().subscribe((res: any) => {
      this.doctors = (res.data)
        .filter((d: any) => d.specialty_id === this.selectedSpecialityId)
        .map((d: any) => ({
          ...d,
          id: Number(d.id),
          user: d.user || { name: '-', profile_pic: 'assets/default-profile.png' },
          speciality: d.speciality || { name: this.selectedSpecialityName },
        }));
    });
  }

  onDoctorChange() {
    // this.availableTimes = [];
    if (this.selectedDoctorId) {
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

  // loadAvailableTimes() {
  //   if (!this.selectedDoctorId || !this.selectedDate) return;
  //   // step 1: load schedule definition
  //   this.appointmentService
  //     .getDoctorSchedule(this.selectedDoctorId!, this.selectedDate)
  //     .subscribe((schedules: any) => {
  //       this.availableTimes = [];

  //       if (!schedules.length) return;

  //       // step 2: load booked appointments for this doctor
  //       this.appointmentService.getAppointments(Number(this.selectedDoctorId)!, this.selectedDate!).subscribe((appointments: any[]) => {
  //         const bookedTimes = appointments.map(a => a.schedule_time);

  //         schedules.forEach((schedule: any) => {
  //           // each schedule has start_time and appointment_duration
  //           const start = new Date(`1970-01-01T${schedule.start_time}`);
  //           const end = new Date(`1970-01-01T${schedule.end_time}`);
  //           const duration = schedule.appointment_duration || 20; // 20 minutes per appointment
  //           while (start < end) {
  //             const timeStr = start.toTimeString().slice(0, 5);

  //             // skip if booked
  //             if (!bookedTimes.includes(timeStr)) {
  //               this.availableTimes.push({ time: timeStr, scheduleId: schedule.id });
  //             }
  //             start.setMinutes(start.getMinutes() + duration);
  //           }
  //         });
  //       });


  //     });
  // }

  loadAvailableTimes() {
    if (!this.selectedDoctorId || !this.selectedDate) return;

    this.appointmentService
      .getDoctorSchedule(this.selectedDoctorId!, this.selectedDate)
      .subscribe((schedules: any[]) => {
        this.availableTimes = [];

        if (!schedules.length) return;

        this.appointmentService
          .getAppointments(Number(this.selectedDoctorId), this.selectedDate)
          .subscribe((appointments: any[]) => {
            const bookedTimes = appointments.map(a => a.schedule_time);

            schedules.forEach((schedule: any) => {
              const start = new Date(`1970-01-01T${schedule.start_time}`);
              const end = new Date(`1970-01-01T${schedule.end_time}`);
              const duration = schedule.appointment_duration || 20;

              while (start < end) {
                const timeStr = start.toTimeString().slice(0, 5);

                this.availableTimes.push({
                  time: timeStr,
                  scheduleId: schedule.id,
                  booked: bookedTimes.includes(timeStr)
                });

                start.setMinutes(start.getMinutes() + duration);
              }
            });
          });
      });
  }


  onTimeSelect(event: Event) {
    const target = event.target as HTMLSelectElement;
    this.selectedTime = target.value;

    const slot = this.availableTimes.find((t) => t.time === this.selectedTime);
    this.selectedScheduleId = slot ? slot.scheduleId : null;

    // this.selectedScheduleId = timeSlot.scheduleId;
    // this.selectedTime = timeSlot.time;
  }

  // ===================================== live preview =======================================
  get selectedDoctor(): any | null {
    if (!this.selectedDoctorId) return null;
    const doc = this.doctors.find((d) => d.id === Number(this.selectedDoctorId));
    return doc ? doc : null;
  }

  get selectedDoctorName(): string {
    return this.selectedDoctor?.user?.name || '';
  }

  get selectedDoctorProfilePic(): string {
    return this.selectedDoctor?.user?.profile_pic || 'assets/default-profile.png';
  }

  get selectedDoctorSpeciality(): string {
    return this.selectedDoctor?.speciality?.name || this.selectedSpecialityName;
  }

  get selectedSpecialityName(): string {
    if (!this.selectedSpecialityId) return '—';
    const sp = this.specialities.find((s) => s.id == Number(this.selectedSpecialityId));
    return sp ? sp.name : '—';
  }
  // ====================================== live preview =====================================

  submitBooking() {
    if (!this.selectedDoctorId || !this.selectedDate || !this.selectedTime) {
      return this.toastService.show('Please select doctor, date and time', 'error');
    }

    const data = {
      patient_id: this.patient_id,
      doctor_id: this.selectedDoctorId,
      doctor_schedule_id: this.selectedScheduleId,
      schedule_date: this.selectedDate,
      schedule_time: this.selectedTime,
      type: this.appointmentType,
      notes: this.notes,
    };

    this.appointmentService.bookAppointment(data).subscribe(
      (res) => {
        this.toastService.show('Appointment booked successfully', 'success');
        // redirect to the confirmation

        // remove selected time from available times (load the new data from the backend)
        this.selectedTime = '';
        this.loadAvailableTimes();
      },
      (err) => {
        console.error(err);
        this.toastService.show('Failed to book appointment', 'error');
      }
    );
  }


  goToPayment() {
    // optionally do validation or save form data here
    this.router.navigate(['/payment-form']);
  }
}
