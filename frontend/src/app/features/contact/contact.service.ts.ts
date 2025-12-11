import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

export interface ContactFormData {
  name: string;
  email: string;
  phone?: string;
  subject: string;
  message: string;
}

export interface ContactResponse {
  success: boolean;
  message: string;
  data?: {
    id: number;
    name: string;
    email: string;
    phone: string;
    subject: string;
    message: string;
    created_at: string;
    replied: boolean;
  };
}

@Injectable({
  providedIn: 'root'
})
export class ContactService {
  private apiUrl = 'http://127.0.0.1:8000/api/contacts';

  constructor(private http: HttpClient) {}

  submitContact(data: ContactFormData): Observable<ContactResponse> {
    return this.http.post<ContactResponse>(this.apiUrl, data)
      .pipe(
        catchError(this.handleError)
      );
  }

  private handleError(error: HttpErrorResponse) {
    let errorMessage = 'An error occurred. Please try again.';
    
    if (error.error instanceof ErrorEvent) {
      // Client-side error
      errorMessage = `Error: ${error.error.message}`;
    } else {
      // Server-side error
      if (error.status === 422) {
        // Validation errors
        const validationErrors = error.error.errors;
        errorMessage = Object.values(validationErrors).flat().join(', ');
      } else if (error.error?.message) {
        errorMessage = error.error.message;
      }
    }
    
    return throwError(() => new Error(errorMessage));
  }
}