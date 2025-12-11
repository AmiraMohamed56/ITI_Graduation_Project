import { Component, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { NotificationService, Notification } from '../services/notification.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-notifications-page',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './notifications-page.html',
  styleUrls: ['./notifications-page.css']
})
export class NotificationsPageComponent implements OnInit, OnDestroy {
  notifications: Notification[] = [];
  filteredNotifications: Notification[] = [];
  unreadCount = 0;
  isLoading = false;
  filter: 'all' | 'unread' | 'read' = 'all';

  private subscriptions = new Subscription();

  constructor(private notificationService: NotificationService) {}

  ngOnInit(): void {
    this.subscriptions.add(
      this.notificationService.notifications$.subscribe(notifications => {
        this.notifications = notifications;
        this.applyFilter();
      })
    );

    this.subscriptions.add(
      this.notificationService.unreadCount$.subscribe(count => {
        this.unreadCount = count;
      })
    );

    this.loadNotifications();
  }

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
  }

  loadNotifications(): void {
    this.isLoading = true;
    this.notificationService.loadNotifications();
    setTimeout(() => this.isLoading = false, 500);
  }

  applyFilter(): void {
    switch (this.filter) {
      case 'unread':
        this.filteredNotifications = this.notifications.filter(n => !n.read_at);
        break;
      case 'read':
        this.filteredNotifications = this.notifications.filter(n => n.read_at);
        break;
      default:
        this.filteredNotifications = this.notifications;
    }
  }

  setFilter(filter: 'all' | 'unread' | 'read'): void {
    this.filter = filter;
    this.applyFilter();
  }

  markAsRead(notification: Notification): void {
    if (notification.read_at) return;

    this.notificationService.markAsRead(notification.id).subscribe({
      next: () => {
        this.notificationService.updateNotificationAsRead(notification.id);
      },
      error: (error) => console.error('Error marking notification as read:', error)
    });
  }

  markAllAsRead(): void {
    this.notificationService.markAllAsRead().subscribe({
      next: () => {
        this.notificationService.updateAllNotificationsAsRead();
      },
      error: (error) => console.error('Error marking all as read:', error)
    });
  }

  deleteNotification(id: string, event: Event): void {
    event.stopPropagation();

    if (confirm('Are you sure you want to delete this notification?')) {
      this.notificationService.deleteNotification(id).subscribe({
        next: () => {
          this.notificationService.removeNotification(id);
        },
        error: (error) => console.error('Error deleting notification:', error)
      });
    }
  }

  deleteAllNotifications(): void {
    if (confirm('Are you sure you want to delete all notifications? This action cannot be undone.')) {
      this.notificationService.deleteAllNotifications().subscribe({
        next: () => {
          this.notificationService.clearAllNotifications();
        },
        error: (error) => console.error('Error deleting all notifications:', error)
      });
    }
  }

  getNotificationIcon(type: string): string {
    switch (type) {
      case 'appointment':
        return '📅';
      case 'reminder':
        return '⏰';
      case 'payment':
        return '💳';
      default:
        return '🔔';
    }
  }

  getNotificationColor(type: string): string {
    switch (type) {
      case 'appointment':
        return 'bg-blue-100 text-blue-600';
      case 'reminder':
        return 'bg-yellow-100 text-yellow-600';
      case 'payment':
        return 'bg-green-100 text-green-600';
      default:
        return 'bg-gray-100 text-gray-600';
    }
  }

  getTimeAgo(dateString: string): string {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (seconds < 60) return 'just now';
    if (seconds < 3600) return `${Math.floor(seconds / 60)} minutes ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)} hours ago`;
    if (seconds < 604800) return `${Math.floor(seconds / 86400)} days ago`;
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  }
}
