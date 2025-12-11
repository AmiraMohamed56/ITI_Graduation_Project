import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { environment } from '../../environments/environment';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

export interface Notification {
  id: string;
  type: string;
  data: {
    appointment_id?: number;
    title: string;
    message: string;
    type: string;
    hours_until?: number;
  };
  read_at: string | null;
  created_at: string;
}

export interface NotificationResponse {
  status: boolean;
  data: Notification[];
  unread_count: number;
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  private apiUrl = `${environment.apiUrl}/patient/notifications`;
  private echo: Echo<any> | null = null;

  // BehaviorSubjects for real-time updates
  private notificationsSubject = new BehaviorSubject<Notification[]>([]);
  private unreadCountSubject = new BehaviorSubject<number>(0);

  public notifications$ = this.notificationsSubject.asObservable();
  public unreadCount$ = this.unreadCountSubject.asObservable();

  constructor(private http: HttpClient) {}

  /**
   * Initialize Pusher/Echo for real-time notifications
   */
  initializeEcho(userId: number): void {
    if (this.echo) return; // Already initialized

    const token = localStorage.getItem('token');
    if (!token) return;

    // Initialize Pusher
    (window as any).Pusher = Pusher;

    this.echo = new Echo({
      broadcaster: 'pusher',
      key: environment.pusherKey,
      cluster: environment.pusherCluster,
      forceTLS: true,
      authEndpoint: `${environment.apiUrl.replace('/api', '')}/broadcasting/auth`,
      auth: {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json'
        }
      }
    });

    // Listen for notifications on the private channel
    this.echo.private(`App.Models.User.${userId}`)
      .notification((notification: any) => {
        console.log('New notification received:', notification);
        this.handleNewNotification(notification);
      });
  }

  /**
   * Handle incoming real-time notification
   */
  private handleNewNotification(notification: any): void {
    const currentNotifications = this.notificationsSubject.value;
    const newNotification: Notification = {
      id: notification.id || Date.now().toString(),
      type: notification.type,
      data: notification,
      read_at: null,
      created_at: new Date().toISOString()
    };

    this.notificationsSubject.next([newNotification, ...currentNotifications]);
    this.unreadCountSubject.next(this.unreadCountSubject.value + 1);

    // Show browser notification if permitted
    this.showBrowserNotification(newNotification);
  }

  /**
   * Show browser notification
   */
  private showBrowserNotification(notification: Notification): void {
    if ('Notification' in window && Notification.permission === 'granted') {
      new Notification(notification.data.title, {
        body: notification.data.message,
        icon: '/assets/logo.png'
      });
    }
  }

  /**
   * Request browser notification permission
   */
  requestNotificationPermission(): void {
    if ('Notification' in window && Notification.permission === 'default') {
      Notification.requestPermission();
    }
  }

  /**
   * Get HTTP headers with auth token
   */
  private getHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  }

  /**
   * Fetch all notifications from API
   */
  getNotifications(unreadOnly: boolean = false): Observable<NotificationResponse> {
    let url = this.apiUrl;
    if (unreadOnly) {
      url += '?unread=1';
    }
    return this.http.get<NotificationResponse>(url, { headers: this.getHeaders() });
  }

  /**
   * Load and cache notifications
   */
  loadNotifications(unreadOnly: boolean = false): void {
    this.getNotifications(unreadOnly).subscribe({
      next: (response) => {
        this.notificationsSubject.next(response.data);
        this.unreadCountSubject.next(response.unread_count);
      },
      error: (error) => console.error('Error loading notifications:', error)
    });
  }

  /**
   * Get unread count
   */
  getUnreadCount(): Observable<{ status: boolean; unread_count: number }> {
    return this.http.get<{ status: boolean; unread_count: number }>(
      `${this.apiUrl}/unread-count`,
      { headers: this.getHeaders() }
    );
  }

  /**
   * Mark notification as read
   */
  markAsRead(id: string): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/${id}/mark-as-read`,
      {},
      { headers: this.getHeaders() }
    );
  }

  /**
   * Mark all notifications as read
   */
  markAllAsRead(): Observable<any> {
    return this.http.post(
      `${this.apiUrl}/mark-all-as-read`,
      {},
      { headers: this.getHeaders() }
    );
  }

  /**
   * Delete a notification
   */
  deleteNotification(id: string): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`, { headers: this.getHeaders() });
  }

  /**
   * Delete all notifications
   */
  deleteAllNotifications(): Observable<any> {
    return this.http.delete(`${this.apiUrl}/all`, { headers: this.getHeaders() });
  }

  /**
   * Update local notification state after marking as read
   */
  updateNotificationAsRead(id: string): void {
    const notifications = this.notificationsSubject.value.map(n =>
      n.id === id ? { ...n, read_at: new Date().toISOString() } : n
    );
    this.notificationsSubject.next(notifications);
    this.unreadCountSubject.next(Math.max(0, this.unreadCountSubject.value - 1));
  }

  /**
   * Update local state after marking all as read
   */
  updateAllNotificationsAsRead(): void {
    const notifications = this.notificationsSubject.value.map(n => ({
      ...n,
      read_at: new Date().toISOString()
    }));
    this.notificationsSubject.next(notifications);
    this.unreadCountSubject.next(0);
  }

  /**
   * Remove notification from local state
   */
  removeNotification(id: string): void {
    const notifications = this.notificationsSubject.value.filter(n => n.id !== id);
    this.notificationsSubject.next(notifications);
  }

  /**
   * Clear all notifications from local state
   */
  clearAllNotifications(): void {
    this.notificationsSubject.next([]);
    this.unreadCountSubject.next(0);
  }

  /**
   * Disconnect Echo
   */
  disconnect(): void {
    if (this.echo) {
      this.echo.disconnect();
      this.echo = null;
    }
  }
}
