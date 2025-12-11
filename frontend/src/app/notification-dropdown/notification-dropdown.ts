import { Component, OnInit, OnDestroy, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { NotificationService,Notification } from '../services/notification.service';
import { AuthService } from '../services/auth.service';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-notification-dropdown',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './notification-dropdown.html',
  styleUrls: ['./notification-dropdown.css']
})
export class NotificationDropdownComponent implements OnInit, OnDestroy {
  isOpen = false;
  notifications: Notification[] = [];
  unreadCount = 0;
  isLoading = false;

  private subscriptions = new Subscription();

  constructor(
    private notificationService: NotificationService,
    private authService: AuthService
  ) {}

  ngOnInit(): void {
    // Initialize Echo for real-time notifications
    const user = this.authService.getUser();
    if (user?.user?.id) {
      this.notificationService.initializeEcho(user.user.id);
      this.notificationService.requestNotificationPermission();
    }

    // Subscribe to notifications
    this.subscriptions.add(
      this.notificationService.notifications$.subscribe(notifications => {
        this.notifications = notifications;
      })
    );

    // Subscribe to unread count
    this.subscriptions.add(
      this.notificationService.unreadCount$.subscribe(count => {
        this.unreadCount = count;
      })
    );

    // Load initial notifications
    this.loadNotifications();
  }

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
    this.notificationService.disconnect();
  }

  toggleDropdown(): void {
    this.isOpen = !this.isOpen;
    if (this.isOpen && this.notifications.length === 0) {
      this.loadNotifications();
    }
  }

  loadNotifications(): void {
    this.isLoading = true;
    this.notificationService.loadNotifications();
    this.isLoading = false;
  }

  markAsRead(notification: Notification, event: Event): void {
    event.stopPropagation();

    if (notification.read_at) return; // Already read

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

    this.notificationService.deleteNotification(id).subscribe({
      next: () => {
        this.notificationService.removeNotification(id);
      },
      error: (error) => console.error('Error deleting notification:', error)
    });
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

  getTimeAgo(dateString: string): string {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (seconds < 60) return 'just now';
    if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
    if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
    return date.toLocaleDateString();
  }

  @HostListener('document:click', ['$event'])
  onClickOutside(event: Event): void {
    const target = event.target as HTMLElement;
    if (!target.closest('.notification-dropdown')) {
      this.isOpen = false;
    }
  }
}
