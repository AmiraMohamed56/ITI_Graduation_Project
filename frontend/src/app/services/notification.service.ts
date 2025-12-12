// src/app/services/notification.service.ts
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable, interval, of } from 'rxjs';
import { tap, catchError } from 'rxjs/operators';
import { environment } from '../../environments/environment';

export interface Notification {
  id: string;
  type: string;
  data: any;
  read_at: string | null;
  created_at: string;
  created_at_full?: string;
}

export interface NotificationResponse {
  status: boolean;
  data: Notification[];
  unread_count: number;
  meta?: any;
}

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  private apiUrl = `${environment.apiUrl}/patient`;
  private notificationsSubject = new BehaviorSubject<Notification[]>([]);
  private unreadCountSubject = new BehaviorSubject<number>(0);

  public notifications$ = this.notificationsSubject.asObservable();
  public unreadCount$ = this.unreadCountSubject.asObservable();

  constructor(private http: HttpClient) {
    // Poll for new notifications every 30 seconds
    interval(30000).subscribe(() => this.loadNotifications());
  }

  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token'); // FIXED: match AuthService
    // console.log('Token:', token); // ← Add this debug
    
    // if (!token) {
    //     console.error('No token found in localStorage');
    // }
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  }

  loadNotifications(): void {
    this.http.get<NotificationResponse>(`${this.apiUrl}/notifications`, { headers: this.getHeaders() })
      .pipe(
        tap(response => {
          if (response?.status) {
            this.notificationsSubject.next(response.data);
            this.unreadCountSubject.next(response.unread_count);
          }
        }),
        catchError(error => {
          console.error('Error loading notifications:', error);
          return of(null);
        })
      )
      .subscribe();
  }

  getUnreadCount(): Observable<{ status: boolean; unread_count: number }> {
    return this.http.get<{ status: boolean; unread_count: number }>(
      `${this.apiUrl}/notifications/unread-count`,
      { headers: this.getHeaders() }
    ).pipe(
      tap(response => {
        if (response?.status) this.unreadCountSubject.next(response.unread_count);
      })
    );
  }

  markAsRead(notificationId: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/notifications/${notificationId}/mark-as-read`, {}, { headers: this.getHeaders() })
      .pipe(
        tap(() => {
          const updated = this.notificationsSubject.value.map(n =>
            n.id === notificationId ? { ...n, read_at: new Date().toISOString() } : n
          );
          this.notificationsSubject.next(updated);
          this.updateUnreadCount();
        })
      );
  }

  markAllAsRead(): Observable<any> {
    return this.http.post(`${this.apiUrl}/notifications/mark-all-as-read`, {}, { headers: this.getHeaders() })
      .pipe(
        tap(() => {
          const updated = this.notificationsSubject.value.map(n => ({ ...n, read_at: new Date().toISOString() }));
          this.notificationsSubject.next(updated);
          this.unreadCountSubject.next(0);
        })
      );
  }

  deleteNotification(notificationId: string): Observable<any> {
    return this.http.delete(`${this.apiUrl}/notifications/${notificationId}`, { headers: this.getHeaders() })
      .pipe(
        tap(() => {
          const updated = this.notificationsSubject.value.filter(n => n.id !== notificationId);
          this.notificationsSubject.next(updated);
          this.updateUnreadCount();
        })
      );
  }

  deleteAllNotifications(): Observable<any> {
    return this.http.delete(`${this.apiUrl}/notifications/all`, { headers: this.getHeaders() })
      .pipe(
        tap(() => {
          this.notificationsSubject.next([]);
          this.unreadCountSubject.next(0);
        })
      );
  }

  private updateUnreadCount(): void {
    const unreadCount = this.notificationsSubject.value.filter(n => !n.read_at).length;
    this.unreadCountSubject.next(unreadCount);
  }

  getNotificationIcon(type: string): string {
    const typeMap: any = {
      'appointment': 'fa-calendar-check',
      'reminder': 'fa-clock',
      'payment': 'fa-credit-card',
      'general': 'fa-bell'
    };
    return typeMap[type] || 'fa-bell';
  }

  getNotificationColor(type: string): string {
    const colorMap: any = {
      'appointment': 'text-blue-600',
      'reminder': 'text-orange-600',
      'payment': 'text-green-600',
      'general': 'text-gray-600'
    };
    return colorMap[type] || 'text-gray-600';
  }

  formatNotificationTime(dateString: string): string {
    const date = new Date(dateString);
    const now = new Date();
    const diff = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (diff < 60) return 'Just now';
    if (diff < 3600) return `${Math.floor(diff / 60)} mins ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)} hrs ago`;
    if (diff < 604800) return `${Math.floor(diff / 86400)} days ago`;
    return date.toLocaleDateString();
  }
}
