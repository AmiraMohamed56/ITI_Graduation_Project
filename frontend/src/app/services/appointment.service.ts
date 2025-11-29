import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";

@Injectable({
  providedIn: 'root'
})

export class AppointmentService {

  private apiUrl = 'http://backend.test';

  constructor(private http: HttpClient) {}

  getDoctors(): Observable<any> {
    return this.http.get(`${this.apiUrl}/doctors`);
  }

  getDoctorSchedule(doctorId: number, date: string): Observable<any> {
    return this.http.get(`${this.apiUrl}/doctors/${doctorId}/schedule?date=${date}`);
  }

  getDoctorSchedules(doctorId: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/doctors/${doctorId}/schedules`);
  }

  bookAppointment(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/appointment`, data);
  }



}
