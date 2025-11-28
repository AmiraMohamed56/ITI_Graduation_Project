import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { HttpClient } from '@angular/common/http';
import { Specialty } from './category.model';

@Injectable({
  providedIn: 'root'
})
export class CategoryService {

  private apiUrl = 'http://localhost:8000/api/specialties';

  // بيانات مؤقتة للاختبار
  private fallbackSpecialties: Specialty[] = [
    { id: 1, name: 'Cardiology', created_at: '2025-01-01', updated_at: '2025-01-01' },
    { id: 2, name: 'Dermatology', created_at: '2025-01-02', updated_at: '2025-01-02' },
    { id: 3, name: 'Pediatrics', created_at: '2025-01-03', updated_at: '2025-01-03' },
    { id: 4, name: 'Orthopedics', created_at: '2025-01-04', updated_at: '2025-01-04' },
    { id: 5, name: 'Neurology', created_at: '2025-01-05', updated_at: '2025-01-05' },
  ];

  constructor(private http: HttpClient) { }

  getSpecialties(): Observable<Specialty[]> {
    return this.http.get<Specialty[]>(this.apiUrl).pipe(
      catchError(err => {
        console.warn('API failed, using fallback specialties', err);
        return of(this.fallbackSpecialties);
      })
    );
  }

  addSpecialty(newSpecialty: Specialty): Observable<Specialty> {
    return this.http.post<Specialty>(this.apiUrl, newSpecialty).pipe(
      catchError(err => {
        console.warn('API add failed, adding to fallback data', err);
        const nextId = this.fallbackSpecialties.length + 1;
        const specialty = { ...newSpecialty, id: nextId, created_at: new Date().toISOString(), updated_at: new Date().toISOString() };
        this.fallbackSpecialties.push(specialty);
        return of(specialty);
      })
    );
  }

  updateSpecialty(updatedSpecialty: Specialty): Observable<Specialty> {
    return this.http.put<Specialty>(`${this.apiUrl}/${updatedSpecialty.id}`, updatedSpecialty).pipe(
      catchError(err => {
        console.warn('API update failed, updating fallback data', err);
        const index = this.fallbackSpecialties.findIndex(s => s.id === updatedSpecialty.id);
        if (index !== -1) {
          this.fallbackSpecialties[index] = { ...updatedSpecialty, updated_at: new Date().toISOString() };
        }
        return of(updatedSpecialty);
      })
    );
  }

  deleteSpecialty(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`).pipe(
      catchError(err => {
        console.warn('API delete failed, removing from fallback data', err);
        this.fallbackSpecialties = this.fallbackSpecialties.filter(s => s.id !== id);
        return of({ success: true });
      })
    );
  }
}
