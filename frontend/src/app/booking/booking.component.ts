import { Component, OnInit } from '@angular/core';
import { AppointmentService } from '../services/appointment.service';
import { scheduled } from 'rxjs';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ToastService } from '../services/toast.service';
import { Router } from '@angular/router';
import { AuthService } from '../services/auth.service';
import { ActivatedRoute } from '@angular/router';
import { PaymentsService } from '../services/payments.service';

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
  // availableDates: any[] = [];
  availableDates: {id: number, date: string}[] = [];
  availableTimes: { time: string; scheduleId: number, booked: boolean }[] = [];
  patient_id: number | null = null;

  selectedSpecialityId: number | null = null;
  selectedDoctorId: number | null = null;
  selectedScheduleId: number | null = null;
  selectedDate: string | '' = '';
  selectedTime: string | '' = '';
  appointmentType: string = 'consultation';
  notes: string = '';

  existing_appointments_for_this_patient: any[] = [];
  active_appointments_for_this_patient: any[] = [];
  existing_appointment_at_this_time: any = null;

  constructor(
    private appointmentService: AppointmentService,
    private toastService: ToastService,
    private router: Router,
    private authService: AuthService,
    private route: ActivatedRoute,
    private appointments: PaymentsService
  ) {}

  ngOnInit(): void {

    this.loadSpecialities();
    this.getLoggedInUser();



    // Check if doctor id exists in the url
    this.route.queryParams.subscribe(params => {
      const doctorId = Number(params['doctorId']);
      // console.log('redirected doctor id: ', doctorId);
      if(doctorId) {
        this.prefill(doctorId);
      }
    })
  }

  prefill(doctorId: any) {
    // console.log('redirected doctor id form inside prefill: ', doctorId);
    this.appointmentService.getDoctors().subscribe((res: any) => {
      const allDoctors = res.data;

      const doctor = allDoctors.find((d: any) => Number(d.id) == doctorId);
      // console.log(allDoctors);
      // console.log('doctor from prefill: ', doctor);

      if(!doctor) return;

      // prefill speciality
      this.selectedSpecialityId = Number(doctor.specialty_id);

      // load doctors
      this.loadDoctors();

      // prefill doctor
      this.selectedDoctorId = Number(doctorId);

      // load doctor schedules
      this.onDoctorChange();
    })
  }

  getLoggedInUser() {
    const loggedInUser = this.authService.getUser();
    if(loggedInUser && loggedInUser.id) {
      this.patient_id = Number(loggedInUser.id);
      // console.log('user id : ', this.patient_id);

      this.appointments.getAppointments().subscribe((res: any) => {
        this.existing_appointments_for_this_patient = res.data.filter((app: any) => app.patient_id == this.patient_id );
        // this.active_appointments_for_this_patient = this.existing_appointments_for_this_patient.filter((app: any) => new Date(app.schedule_date) >= new Date());
        this.existing_appointments_for_this_patient.forEach(a => {
          if (a.status != 'cancelled' && a.status != 'completed') {
            this.active_appointments_for_this_patient.push(a);
          }
        });
      });

    } else {
      console.error('User is not logged in.');
      this.toastService.show('User not logged in. Please login first', 'error');
      this.router.navigate(['/auth/login']);
    }


  }

  loadSpecialities() {

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
    // console.log('specialty: ', this.selectedSpecialityId);
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

    if (this.selectedDoctorId) {

      this.loadAvailableDates();
    }
  }

  loadAvailableDates() {

    if (!this.selectedDoctorId) return;

    this.appointmentService.getDoctorSchedules(this.selectedDoctorId).subscribe((res: any) => {
      this.availableDates = [];
      (res.data).forEach((d: any) => {
        this.availableDates.push({id: d.id, date: d.day_of_week});
      });
      // console.log('available dates = ', this.availableDates);
    });
  }

  onDateChange() {

    if (this.selectedDoctorId && this.selectedDate) {
      this.loadAvailableTimes();
    }
  }


  loadAvailableTimes() {
    // console.log('seelcted date', this.selectedDate);
    // console.log("selectedDoctroId: ", this.selectedDoctorId);
    if (!this.selectedDoctorId || !this.selectedDate) return;

    this.appointmentService
      .getDoctorSchedule(this.selectedDoctorId!, Number(this.selectedDate))
      .subscribe((schedules) => {
        this.availableTimes = [];
        // console.log(schedules.data);
        if (!(schedules.data).length) return;

        this.appointmentService
          .getAppointments(Number(this.selectedDoctorId), Number(this.selectedDate))
          .subscribe((appointments) => {
            const bookedTimes = (appointments.data).map((a:any) => a.schedule_time.slice(11, 16));

            (schedules.data).forEach((schedule: any) => {

              let start = schedule.start_time.slice(11, 16);
              const end = schedule.end_time.slice(11, 16);
              const duration = schedule.appointment_duration || 20;

              while (start < end) {
                const timeStr = start;

                this.availableTimes.push({
                  time: timeStr,
                  scheduleId: schedule.id,
                  booked: bookedTimes.includes(timeStr)
                });

                let [h, m] = start.split(':').map(Number);
                m += duration;
                if (m > 60) {
                  h += Math.floor(m / 60);
                  m %= 60;
                }
                start = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;

              }
            });
          });
      });
  }

onTimeSelect(event: Event) {

    const target = event.target as HTMLSelectElement;
    this.selectedTime = target.value;
    this.existing_appointment_at_this_time = this.active_appointments_for_this_patient.find((app: any) => app.schedule_time == this.selectedTime);

    const slot = this.availableTimes.find((t) => t.time === this.selectedTime);
    this.selectedScheduleId = slot ? slot.scheduleId : null;


  }

  // ===================================== live preview start =======================================
  get selectedDoctor(): any | null {
    if (!this.selectedDoctorId) return null;
    const doc = this.doctors.find((d) => d.id === Number(this.selectedDoctorId));
    return doc ? doc : null;
  }

  get selectedDoctorName(): string {
    return this.selectedDoctor?.user?.name || '';
  }

  get selectedDoctorProfilePic(): string {
    return this.selectedDoctor?.user?.profile_pic || '/default_profile.jpg';
  }

  get selectedDoctorSpeciality(): string {
    return this.selectedDoctor?.speciality?.name || this.selectedSpecialityName;
  }

  get selectedSpecialityName(): string {
    if (!this.selectedSpecialityId) return '—';
    const sp = this.specialities.find((s) => s.id == Number(this.selectedSpecialityId));
    return sp ? sp.name : '—';
  }

  get date(): any {
    // console.log(this.availableDates.find(item => item.id == Number(this.selectedDate))?.date);
    return this.availableDates.find(item => item.id == Number(this.selectedDate))?.date;
  }

  // ====================================== live preview end =====================================

  submitBooking() {
    if (!this.selectedDoctorId || !this.selectedDate || !this.selectedTime) {
      return this.toastService.show('Please select doctor, date and time', 'error');
    }

    const day = this.availableDates.find(item => item.id == Number(this.selectedDate));

    const data = {
      patient_id: this.patient_id,
      doctor_id: this.selectedDoctorId,
      doctor_schedule_id: Number(this.selectedDate),
      schedule_date: day?.date,
      schedule_time: this.selectedTime,
      type: this.appointmentType,
      notes: this.notes,
    };
    // console.log("final data", data);
    // console.log("active appointments: ", this.active_appointments_for_this_patient);



    this.appointmentService.getAppointments(this.selectedDoctorId, Number(this.selectedDate)).subscribe(res => {
      const existing_appointments_for_this_day = res.data;
      // console.log(existing_appointments_for_this_day);

      // prevent duplicate booking for the same doctor and same day
      if (existing_appointments_for_this_day.length > 0) {
        this.toastService.show('You already have an appointment with this doctor on this day', 'error');
        this.selectedTime = '';
        this.loadAvailableTimes();
        return;
      // prevent more thatn 3 upcomming appointments
      } else if (this.active_appointments_for_this_patient.length >=3) {
        this.toastService.show('You reached the maximum number of future appointments.', 'error');
        this.selectedTime = '';
        this.loadAvailableTimes();
        return;
      // prevent the same time with other doctor
      } else if (this.active_appointments_for_this_patient.find((app: any) => app.schedule_time == this.selectedTime)) {
        this.toastService.show('You have appointment with other doctor at this same time');
        this.selectedTime = '';
        this.loadAvailableTimes();
        return;
      } else {
        this.appointmentService.bookAppointment(data).subscribe(
          (res) => {
            this.toastService.show('Appointment booked successfully', 'success');

            const appointmentId = res.data.id;

            // redirect to payment page with appointment id
            this.router.navigate(['/payment-form'], {queryParams: {appointment_id: appointmentId}});

            // remove selected time from available times (load the new data from the backend)
            // this.selectedTime = '';
            // this.loadAvailableTimes();
          },
          (err) => {
            console.error(err);
            this.toastService.show('Failed to book appointment', 'error');
          }
        );
      }
    });




  }


  // goToPayment() {
  //   // optionally do validation or save form data here
  //   this.router.navigate(['/payment-form']);
  // }

  goToDoctor() {
    this.router.navigate([`/doctor/${this.selectedDoctor?.id}`])
  }
}
