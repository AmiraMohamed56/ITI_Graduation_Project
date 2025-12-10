import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Review {
  id?: number;
  patient_id: number;
  doctor_id: number;
  rating: number;
  comment: string;
}

export interface ReviewResponse {
  status: boolean;
  message: string;
  data?: Review;
}

@Injectable({
  providedIn: 'root'
})
export class ReviewService {
  private apiUrl = `${environment.apiUrl}/reviews`;

  constructor(private http: HttpClient) {}

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    });
  }

  createReview(review: Review): Observable<ReviewResponse> {
    return this.http.post<ReviewResponse>(this.apiUrl, review, {
      headers: this.getHeaders()
    });
  }

  getReviews(): Observable<Review[]> {
    return this.http.get<Review[]>(this.apiUrl, {
      headers: this.getHeaders()
    });
  }

  getReview(id: number): Observable<Review> {
    return this.http.get<Review>(`${this.apiUrl}/${id}`, {
      headers: this.getHeaders()
    });
  }

  updateReview(id: number, review: Review): Observable<ReviewResponse> {
    return this.http.put<ReviewResponse>(`${this.apiUrl}/${id}`, review, {
      headers: this.getHeaders()
    });
  }

  deleteReview(id: number): Observable<ReviewResponse> {
    return this.http.delete<ReviewResponse>(`${this.apiUrl}/${id}`, {
      headers: this.getHeaders()
    });
  }
}
