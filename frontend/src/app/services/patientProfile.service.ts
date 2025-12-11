import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class PatientService {

  private api = 'http://backend.test/api';

  constructor(private http: HttpClient) {}

  /** Get all patients */
  getAllPatients() {
    return this.http.get(`${this.api}/patient`);
  }

  /** Get one patient by ID */
  getPatientById(id: number) {
    return this.http.get(`${this.api}/patient/${id}`);
  }

  /** Update User info */
  updateUser(id: number, data: FormData) {
    return this.http.post(`${this.api}/patient/${id}/update-user`, data);
  }

  /** Update Patient-specific info */
  updatePatientInfo(id: number, data: any) {
    return this.http.post(`${this.api}/patient/${id}/update-info`, data);
  }

  /** Remove profile picture */
  removeProfilePic(id: number) {
    return this.http.delete(`${this.api}/patient/${id}/profile-pic`);
  }
}
