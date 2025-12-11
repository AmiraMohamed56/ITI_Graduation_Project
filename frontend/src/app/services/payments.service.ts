import { Injectable } from "@angular/core";
import { HttpClient } from "@angular/common/http";
import { Observable } from "rxjs";

@Injectable({
  providedIn: 'root'
})

export class PaymentsService {
  private apiUrl = 'http://backend.test/api';

  constructor(private http: HttpClient) {}

  getPayments(): Observable<any> {
    return this.http.get(`${this.apiUrl}/user/payments`);
  }

  getAppointments(): Observable<any> {
    return this.http.get(`${this.apiUrl}/user/appointments`)
  }

}
