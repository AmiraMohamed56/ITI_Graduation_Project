import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { catchError } from 'rxjs/operators';

export interface Doctor {
  id?: number;
  user_id: number;
  specialty_id: number;
  bio: string;
  education: string;
  years_experience: number;
  gender: 'male' | 'female';
  consultation_fee: number;
  rating?: number;
  available_for_online?: boolean | null;
  created_at?: string;
  updated_at?: string;
  deleted_at?: string | null;
}

@Injectable({
  providedIn: 'root'
})
export class DoctorsService {

  private apiUrl = 'http://localhost:8000/api/doctors';

  // بيانات مؤقتة fallback مطابقة للداتابيز
  private fallbackDoctors: Doctor[] = [
    {
      id: 1,
      user_id: 101,
      specialty_id: 1,
      bio: 'General Practitioner',
      education: 'MBBS',
      years_experience: 5,
      gender: 'male',
      consultation_fee: 300,
      rating: 4.5,
      available_for_online: true
    },
    {
      id: 2,
      user_id: 102,
      specialty_id: 2,
      bio: 'Pediatrician',
      education: 'MBBS, Pediatrics',
      years_experience: 7,
      gender: 'female',
      consultation_fee: 350,
      rating: 4.8,
      available_for_online: false
    }
  ];

  constructor(private http: HttpClient) {}

  getDoctors(): Observable<Doctor[]> {
    return this.http.get<Doctor[]>(this.apiUrl).pipe(
      catchError(err => {
        console.warn('API failed, using fallback data', err);
        return of(this.fallbackDoctors);
      })
    );
  }

  getDoctorById(id: number): Observable<Doctor> {
    return this.http.get<Doctor>(`${this.apiUrl}/${id}`).pipe(
      catchError(err => {
        console.warn(`API failed for doctor ${id}, using fallback data`, err);
        const doctor = this.fallbackDoctors.find(d => d.id === id);
        return of(doctor!);
      })
    );
  }

  addDoctor(data: Doctor): Observable<any> {
    return this.http.post(this.apiUrl, data).pipe(
      catchError(err => {
        console.warn('API add failed, using fallback', err);
        this.fallbackDoctors.push({ ...data, id: this.fallbackDoctors.length + 1 });
        return of({ success: true });
      })
    );
  }

  updateDoctor(id: number, data: Doctor): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, data).pipe(
      catchError(err => {
        console.warn('API update failed, using fallback', err);
        const index = this.fallbackDoctors.findIndex(d => d.id === id);
        if (index !== -1) this.fallbackDoctors[index] = { ...data, id };
        return of({ success: true });
      })
    );
  }

  deleteDoctor(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`).pipe(
      catchError(err => {
        console.warn('API delete failed, using fallback', err);
        this.fallbackDoctors = this.fallbackDoctors.filter(d => d.id !== id);
        return of({ success: true });
      })
    );
  }
}
