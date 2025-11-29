import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Doctor } from './doctor.model';

@Injectable({
  providedIn: 'root'
})
export class DoctorService {

  private apiUrl = 'http://localhost:8000/api/doctors';

  // بيانات مؤقتة للاختبار
  private fallbackDoctors: Doctor[] = [
    {
      id: 1,
      user_id: 101,
      specialty_id: 1,
      bio: 'General Practitioner',
      education: 'MBBS',
      years_experience: 5,
      gender: 'male',
      consultation_fee: 200,
      rating: 4.5,
      available_for_online: true,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
      deleted_at: null
    },
    {
      id: 2,
      user_id: 102,
      specialty_id: 2,
      bio: 'Pediatrician',
      education: 'MBBS, Pediatrics',
      years_experience: 7,
      gender: 'female',
      consultation_fee: 250,
      rating: 4.8,
      available_for_online: false,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString(),
      deleted_at: null
    }
  ];

  constructor(private http: HttpClient) {}

  // جلب كل الأطباء
  getDoctors(): Observable<Doctor[]> {
    return this.http.get<Doctor[]>(this.apiUrl).pipe(
      catchError(err => {
        console.warn('API failed, using fallback data', err);
        return of(this.fallbackDoctors);
      })
    );
  }

  // جلب طبيب بالـ ID
  getDoctorById(id: number): Observable<Doctor> {
    return this.http.get<Doctor>(`${this.apiUrl}/${id}`).pipe(
      catchError(err => {
        console.warn(`API failed for doctor ${id}, using fallback data`, err);
        const doctor = this.fallbackDoctors.find(d => d.id === id);
        return of(doctor!);
      })
    );
  }

  // إضافة طبيب جديد
  addDoctor(data: Doctor): Observable<any> {
    return this.http.post(this.apiUrl, data).pipe(
      catchError(err => {
        console.warn('API add failed, pretending to add doctor', err);
        this.fallbackDoctors.push({ ...data, id: this.fallbackDoctors.length + 1, created_at: new Date().toISOString(), updated_at: new Date().toISOString() });
        return of({ success: true });
      })
    );
  }

  // تعديل بيانات طبيب
  updateDoctor(id: number, data: Doctor): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, data).pipe(
      catchError(err => {
        console.warn('API update failed, updating fallback data', err);
        const index = this.fallbackDoctors.findIndex(d => d.id === id);
        if (index !== -1) this.fallbackDoctors[index] = { ...data, id, updated_at: new Date().toISOString() };
        return of({ success: true });
      })
    );
  }

  // حذف طبيب
  deleteDoctor(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`).pipe(
      catchError(err => {
        console.warn('API delete failed, removing from fallback data', err);
        this.fallbackDoctors = this.fallbackDoctors.filter(d => d.id !== id);
        return of({ success: true });
      })
    );
  }
  getDoctorsBySpecialty(specialtyId: number): Observable<Doctor[]> {
  return this.http.get<Doctor[]>(`${this.apiUrl}?specialty_id=${specialtyId}`).pipe(
    catchError(err => {
      console.warn('API failed, using fallback doctors', err);
      return of(this.fallbackDoctors.filter(d => d.specialty_id === specialtyId));
    })
  );
}

}
