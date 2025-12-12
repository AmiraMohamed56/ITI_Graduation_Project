import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { tap } from 'rxjs/operators';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { environment } from '../../environments/environment';

declare global {
  interface Window {
    Pusher: any;
    Echo: any;
  }
}

export interface Notification {
  id: string;
  type: string;
  data: {
    title: string;
    message: string;
    appointment_id?: number;
    type: string;
  };
  read_at: string | null;
  created_at: string;
  created_at_full: string;
}

@Injectable({
  providedIn: 'root'
})
export class NotificationService {
  private apiUrl = environment.apiUrl;
  private notificationsSubject = new BehaviorSubject<Notification[]>([]);
  private unreadCountSubject = new BehaviorSubject<number>(0);
  private echo: Echo<any> | null = null;

  public notifications$ = this.notificationsSubject.asObservable();
  public unreadCount$ = this.unreadCountSubject.asObservable();

  constructor(private http: HttpClient) {}

  private getAuthHeaders(): HttpHeaders {
    const token = localStorage.getItem('token');
    return new HttpHeaders({
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    });
  }

  /**
   * Initialize Echo for real-time notifications
   */
  initializeEcho(userId: number): void {
    if (this.echo) {
      console.log('Echo already initialized');
      return;
    }

    const token = localStorage.getItem('token');
    if (!token) {
      console.error('No auth token found');
      return;
    }

    try {
      window.Pusher = Pusher;

      this.echo = new Echo({
        broadcaster: 'pusher',
        key: environment.pusherKey,
        cluster: environment.pusherCluster,
        forceTLS: true,
        authEndpoint: `${this.apiUrl.replace('/api', '')}/broadcasting/auth`,
        auth: {
          headers: {
            Authorization: `Bearer ${token}`,
            Accept: 'application/json',
          },
        },
      });

      // Listen for notifications on the private channel
      this.echo
        .private(`App.Models.User.${userId}`)
        .notification((notification: any) => {
          console.log('New notification received:', notification);

          // Add new notification to the list
          const currentNotifications = this.notificationsSubject.value;
          const newNotification: Notification = {
            id: notification.id || Date.now().toString(),
            type: notification.type,
            data: notification,
            read_at: null,
            created_at: new Date().toISOString(),
            created_at_full: new Date().toISOString()
          };

          this.notificationsSubject.next([newNotification, ...currentNotifications]);
          this.unreadCountSubject.next(this.unreadCountSubject.value + 1);

          // Show browser notification
          this.showBrowserNotification(notification.title || 'New Notification', notification.message);
        });

      console.log('Echo initialized successfully for user:', userId);
    } catch (error) {
      console.error('Error initializing Echo:', error);
    }
  }

  /**
   * Load notifications from API
   */
  loadNotifications(): void {
    const headers = this.getAuthHeaders();

    this.http.get<any>(`${this.apiUrl}/patient/notifications`, { headers })
      .subscribe({
        next: (response) => {
          console.log('Notifications loaded:', response);

          if (response.status && response.data) {
            // Map API response to our Notification interface
            const notifications: Notification[] = response.data.map((item: any) => ({
              id: item.id,
              type: item.type,
              data: {
                title: item.title,
                message: item.message,
                appointment_id: item.appointment_id,
                type: item.type
              },
              read_at: item.read_at,
              created_at: item.created_at,
              created_at_full: item.created_at_full
            }));

            this.notificationsSubject.next(notifications);
            this.unreadCountSubject.next(response.unread_count || 0);
          }
        },
        error: (error) => {
          console.error('Error loading notifications:', error);
        }
      });
  }

  /**
   * Load unread count only
   */
  loadUnreadCount(): void {
    const headers = this.getAuthHeaders();

    this.http.get<any>(`${this.apiUrl}/patient/notifications/unread-count`, { headers })
      .subscribe({
        next: (response) => {
          if (response.status) {
            this.unreadCountSubject.next(response.unread_count);
          }
        },
        error: (error) => {
          console.error('Error loading unread count:', error);
        }
      });
  }

  /**
   * Mark notification as read
   */
  markAsRead(notificationId: string): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.post(
      `${this.apiUrl}/patient/notifications/${notificationId}/mark-as-read`,
      {},
      { headers }
    ).pipe(
      tap(() => {
        console.log('Notification marked as read:', notificationId);
      })
    );
  }

  /**
   * Mark all notifications as read
   */
  markAllAsRead(): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.post(
      `${this.apiUrl}/patient/notifications/mark-all-as-read`,
      {},
      { headers }
    ).pipe(
      tap(() => {
        console.log('All notifications marked as read');
      })
    );
  }

  /**
   * Delete a notification
   */
  deleteNotification(notificationId: string): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.delete(
      `${this.apiUrl}/patient/notifications/${notificationId}`,
      { headers }
    ).pipe(
      tap(() => {
        console.log('Notification deleted:', notificationId);
      })
    );
  }

  /**
   * Delete all notifications
   */
  deleteAllNotifications(): Observable<any> {
    const headers = this.getAuthHeaders();
    return this.http.delete(
      `${this.apiUrl}/patient/notifications/all`,
      { headers }
    ).pipe(
      tap(() => {
        console.log('All notifications deleted');
      })
    );
  }

  /**
   * Update local notification state to read
   */
  updateNotificationAsRead(notificationId: string): void {
    const notifications = this.notificationsSubject.value.map(n =>
      n.id === notificationId ? { ...n, read_at: new Date().toISOString() } : n
    );
    this.notificationsSubject.next(notifications);
    this.unreadCountSubject.next(Math.max(0, this.unreadCountSubject.value - 1));
  }

  /**
   * Update all local notifications to read
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
  removeNotification(notificationId: string): void {
    const notifications = this.notificationsSubject.value.filter(n => n.id !== notificationId);
    const removedNotification = this.notificationsSubject.value.find(n => n.id === notificationId);

    this.notificationsSubject.next(notifications);

    if (removedNotification && !removedNotification.read_at) {
      this.unreadCountSubject.next(Math.max(0, this.unreadCountSubject.value - 1));
    }
  }

  /**
   * Clear all local notifications
   */
  clearAllNotifications(): void {
    this.notificationsSubject.next([]);
    this.unreadCountSubject.next(0);
  }

  /**
   * Request browser notification permission
   */
  requestNotificationPermission(): void {
    if ('Notification' in window && Notification.permission === 'default') {
      Notification.requestPermission().then(permission => {
        console.log('Notification permission:', permission);
      });
    }
  }

  /**
   * Show browser notification
   */
  private showBrowserNotification(title: string, body: string): void {
    if ('Notification' in window && Notification.permission === 'granted') {
      new Notification(title, {
        body: body,
        icon: '/assets/logo.png',
        badge: '/assets/badge.png'
      });
    }
  }

  /**
   * Disconnect Echo
   */
  disconnect(): void {
    if (this.echo) {
      this.echo.disconnect();
      this.echo = null;
      console.log('Echo disconnected');
    }
  }
}
