// specialty.service.ts
import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { from, Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import { Specialty,SpecialtyResponse } from '../specialities/specialty.model';

@Injectable({
  providedIn: 'root'
})
export class SpecialtyService {
  private apiUrl = `${environment.apiUrl}/specialties`;

  constructor(private http: HttpClient) {}

  /**
   * Get all specialties with pagination and optional search
   */
  getSpecialties(perPage: number = 8, search?: string): Observable<SpecialtyResponse> {
    let params = new HttpParams().set('per_page', perPage.toString());

    if (search && search.trim()) {
      params = params.set('search', search.trim());
    }

    return this.http.get<SpecialtyResponse>(this.apiUrl, { params });
  }

  /**
   * Get a specific specialty by ID
   */
  getSpecialtyById(id: number): Observable<{ success: boolean; data: Specialty; message: string }> {
    return this.http.get<{ success: boolean; data: Specialty; message: string }>(`${this.apiUrl}/${id}`);
  }

  /**
   * Get doctors by specialty ID
   */
  getDoctorsBySpecialty(id: number, perPage: number = 10): Observable<any> {
    const params = new HttpParams().set('per_page', perPage.toString());
    return this.http.get<any>(`${this.apiUrl}/${id}/doctors`, { params });
  }
}
