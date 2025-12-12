import { Component, OnInit, OnDestroy, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { NotificationService, Notification } from '../services/notification.service';
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
    console.log('NotificationDropdown initialized');

    // Get user from localStorage and initialize
    const userStr = localStorage.getItem('user');
    if (userStr) {
      try {
        const userData = JSON.parse(userStr);
        console.log('User data:', userData);

        // Handle different user data structures
        let userId: number | null = null;

        if (userData.user && userData.user.id) {
          // Structure: {user: {id: xxx}}
          userId = userData.user.id;
        } else if (userData.id) {
          // Structure: {id: xxx}
          userId = userData.id;
        }

        console.log('Extracted user ID:', userId);

        if (userId) {
          // Initialize Echo for real-time notifications
          this.notificationService.initializeEcho(userId);

          // Request browser notification permission
          this.notificationService.requestNotificationPermission();
        } else {
          console.error('Could not extract user ID from:', userData);
        }
      } catch (error) {
        console.error('Error parsing user data:', error);
      }
    } else {
      console.warn('No user data found in localStorage');
    }

    // Subscribe to notifications
    this.subscriptions.add(
      this.notificationService.notifications$.subscribe(notifications => {
        console.log('Notifications updated:', notifications);
        this.notifications = notifications;
      })
    );

    // Subscribe to unread count
    this.subscriptions.add(
      this.notificationService.unreadCount$.subscribe(count => {
        console.log('Unread count updated:', count);
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
    console.log('Loading notifications...');
    this.isLoading = true;

    // Load notifications from API
    this.notificationService.loadNotifications();

    // Also load unread count
    this.notificationService.loadUnreadCount();

    // Small delay to show loading state
    setTimeout(() => {
      this.isLoading = false;
    }, 500);
  }

  markAsRead(notification: Notification, event: Event): void {
    event.stopPropagation();

    if (notification.read_at) {
      console.log('Notification already read:', notification.id);
      return;
    }

    console.log('Marking notification as read:', notification.id);
    this.notificationService.markAsRead(notification.id).subscribe({
      next: () => {
        this.notificationService.updateNotificationAsRead(notification.id);
        console.log('Notification marked as read successfully');
      },
      error: (error) => {
        console.error('Error marking notification as read:', error);
        alert('Failed to mark notification as read');
      }
    });
  }

  markAllAsRead(): void {
    console.log('Marking all notifications as read');
    this.notificationService.markAllAsRead().subscribe({
      next: () => {
        this.notificationService.updateAllNotificationsAsRead();
        console.log('All notifications marked as read successfully');
      },
      error: (error) => {
        console.error('Error marking all as read:', error);
        alert('Failed to mark all notifications as read');
      }
    });
  }

  deleteNotification(id: string, event: Event): void {
    event.stopPropagation();

    if (!confirm('Are you sure you want to delete this notification?')) {
      return;
    }

    console.log('Deleting notification:', id);
    this.notificationService.deleteNotification(id).subscribe({
      next: () => {
        this.notificationService.removeNotification(id);
        console.log('Notification deleted successfully');
      },
      error: (error) => {
        console.error('Error deleting notification:', error);
        alert('Failed to delete notification');
      }
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
      case 'schedule':
        return '📆';
      case 'patient':
        return '👤';
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
    return date.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined
    });
  }

  @HostListener('document:click', ['$event'])
  onClickOutside(event: Event): void {
    const target = event.target as HTMLElement;
    if (!target.closest('.notification-dropdown')) {
      this.isOpen = false;
    }
  }
}
