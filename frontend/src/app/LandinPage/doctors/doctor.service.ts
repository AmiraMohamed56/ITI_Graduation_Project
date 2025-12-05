import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { map, catchError } from 'rxjs/operators';
import { Doctor } from './doctor.model';

export interface DoctorSearchParams {
  name?: string;
  specialty?: string;
  gender?: string;
  available_for_online?: boolean | string;
  page?: number;
  per_page?: number;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  from: number;
  to: number;
}

export interface PaginatedDoctorsResponse {
  status: boolean;
  message: string;
  data: Doctor[];
  meta: PaginationMeta;
}

@Injectable({
  providedIn: 'root'
})
export class DoctorService {

  private apiUrl = 'http://127.0.0.1:8000/api/doctors';

  constructor(private http: HttpClient) {}

  // Get all doctors (without pagination) - for home page
  getDoctors(): Observable<Doctor[]> {
    return this.http.get<any>(this.apiUrl).pipe(
      map(res => res.data),
      catchError(err => {
        console.error(err);
        return throwError(() => err);
      })
    );
  }

  // Get paginated doctors with filters
  getDoctorsPaginated(params: DoctorSearchParams): Observable<PaginatedDoctorsResponse> {
    let httpParams = new HttpParams();

    if (params.name) {
      httpParams = httpParams.set('name', params.name);
    }
    if (params.specialty) {
      httpParams = httpParams.set('specialty', params.specialty);
    }
    if (params.gender) {
      httpParams = httpParams.set('gender', params.gender);
    }
    if (params.available_for_online !== undefined && params.available_for_online !== '') {
      // Convert boolean to 0 or 1
      const value = params.available_for_online === true || params.available_for_online === 'true' ? '1' : '0';
      httpParams = httpParams.set('available_for_online', value);
    }
    if (params.page) {
      httpParams = httpParams.set('page', params.page.toString());
    }
    if (params.per_page) {
      httpParams = httpParams.set('per_page', params.per_page.toString());
    }

    return this.http.get<PaginatedDoctorsResponse>(this.apiUrl, { params: httpParams }).pipe(
      catchError(err => {
        console.error('Error fetching paginated doctors:', err);
        return throwError(() => err);
      })
    );
  }

  // Get doctors by specialty
  getDoctorsBySpecialty(specialtyId: number): Observable<Doctor[]> {
    return this.http.get<any>(`${this.apiUrl}?specialty_id=${specialtyId}`).pipe(
      map(res => res.data),
      catchError(err => {
        console.error(err);
        return throwError(() => err);
      })
    );
  }

  // Get single doctor by ID
  getDoctorById(id: number): Observable<Doctor> {
    return this.http.get<any>(`${this.apiUrl}/${id}`).pipe(
      map(res => res.data),
      catchError(err => {
        console.error(err);
        return throwError(() => err);
      })
    );
  }

  // Get all specialties (for filter dropdown)
  getAllSpecialties(): Observable<string[]> {
    return this.getDoctors().pipe(
      map(doctors => {
        const specialties = new Set<string>();
        doctors.forEach(doctor => {
          if (doctor.specialty?.name) {
            specialties.add(doctor.specialty.name);
          }
        });
        return Array.from(specialties).sort();
      })
    );
  }
}