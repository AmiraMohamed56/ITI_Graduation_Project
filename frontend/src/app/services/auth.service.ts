import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { BehaviorSubject, catchError, Observable, tap, throwError } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthService {

  private baseUrl = 'http://127.0.0.1:8000/api/patient';
  private loggedIn = new BehaviorSubject<boolean>(this.hasToken());

  constructor(private http: HttpClient, private router: Router) { }


  register(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/register`, data).pipe(
      tap((res: any) => {
        if (res.token) {
          this.setToken(res.token);
          this.setUser(res.data);
        }
      }),
      catchError((error) => throwError(() =>error))
    );
  }

  login(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/login`, data).pipe(
      tap((res: any) => {
        if (res.token) {
          this.setToken(res.token);
          this.setUser(res.data);
        }
      }),
      catchError((error) => throwError(() =>error))
    );
  }

  logout(): void {
    const headers = new HttpHeaders({
      Authorization: `Bearer ${this.getToken()}`,
    });

    this.http.post(`${this.baseUrl}/logout`, {}, { headers }).subscribe();

    localStorage.removeItem('token');
    localStorage.removeItem('user');
    this.loggedIn.next(false);
    this.router.navigate(['/auth/login']);
  }

  getUser(): any {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }

  isLoggedIn(): Observable<boolean> {
    return this.loggedIn.asObservable();
  }

  private hasToken(): boolean {
    return !!localStorage.getItem('token');
  }

  private setToken(token: string): void {
    localStorage.setItem('token', token);
    this.loggedIn.next(true);
  }

  private setUser(user: any): void {
    localStorage.setItem('user', JSON.stringify(user));
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }

  // private formatError(error: any): string {
  //   if (error.error?.errors) {
  //     return Object.values(error.error.errors).flat().join(', ');
  //   }
  //   if (error.error?.message) {
  //     return error.error.message;
  //   }
  //   return 'Something went wrong. Please try again later.';
  // }

  sendVerificationCode(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/send-verification-code`, data).pipe(
      catchError((error) => throwError(() => error))
    );
  }

  verifyCode(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/verify-code`, data).pipe(
      catchError((error) => throwError(() => error))
    );
  }

  forgotPassword(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/forgot-password`, data).pipe(
      catchError((error) => throwError(() => error))
    );
  }

  resetPassword(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/reset-password`, data).pipe(
      catchError((error) => throwError(() => error))
    );
  }

  getErrorMessage(error: any): string {
    if (error.error?.message) {
      return error.error.message;
    }
    if (typeof error.error === 'string') {
      return error.error;
    }
    if (error.message) {
      return error.message;
    }
    return 'An unknown error occurred.';
  }
}
