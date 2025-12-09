import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";

@Injectable({
  providedIn: 'root'
})

export class AppointmentService {

  private apiUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  getSpecialities(): Observable<any> {
    return this.http.get(`${this.apiUrl}/specialties`);
  }

  getDoctors(): Observable<any> {
    return this.http.get(`${this.apiUrl}/doctors`);
  }

  // Get appointments filtered by doctor ID and schedule date
  getAppointments(doctorId: number, doctor_schedule_id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/appointments?doctor_id=${doctorId}&doctor_schedule_id=${doctor_schedule_id}`);
  }

  // Get all schedules for a doctor
  getDoctorSchedules(doctorId: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/doctor_schedules?doctor_id=${doctorId}`);
  }

  // Get schedule for a specific day
  getDoctorSchedule(doctorId: number, doctor_schedule_id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/doctor_schedules?doctor_id=${doctorId}&doctor_schedule_id=${doctor_schedule_id}`);
  }

  // Book a new appointment
  bookAppointment(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/appointments`, data);
  }


}
