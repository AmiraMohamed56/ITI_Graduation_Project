import { Component, OnInit, OnDestroy, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { RouterLink } from '@angular/router';
import { NotificationService, Notification } from '../services/notification.service';
import { AuthService } from '../services/auth.service';
import { Subscription } from 'rxjs';
import { Subject, takeUntil } from 'rxjs';
import { ConfirmService } from '../shared/confirm.service';

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
  private destroy$ = new Subject<void>();

  constructor(private notificationService: NotificationService, private confirm: ConfirmService) {}

  ngOnInit(): void {
    // Load notifications on init
    this.notificationService.loadNotifications();

    // Subscribe to notifications
    this.notificationService.notifications$
      .pipe(takeUntil(this.destroy$))
      .subscribe(notifications => {
        this.notifications = notifications;
      });

    // Subscribe to unread count
    this.notificationService.unreadCount$
      .pipe(takeUntil(this.destroy$))
      .subscribe(count => {
        this.unreadCount = count;
      });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  toggleDropdown(): void {
    this.isOpen = !this.isOpen;
  }

  closeDropdown(): void {
    this.isOpen = false;
  }

  handleNotificationClick(notification: Notification): void {
    // Mark as read
    if (!notification.read_at) {
      this.notificationService.markAsRead(notification.id).subscribe();
    }

    // Navigate based on notification type
    if (notification.data?.appointment_id) {
      // Navigate to appointment details
      // this.router.navigate(['/appointments', notification.data.appointment_id]);
    }

    this.closeDropdown();
  }

  markAllAsRead(): void {
    this.notificationService.markAllAsRead().subscribe({
      next: () => {
        console.log('All notifications marked as read');
      },
      error: (error) => {
        console.error('Error marking notifications as read:', error);
      }
    });
  }

  deleteNotification(event: Event, notificationId: string): void {
    event.stopPropagation();
    this.confirm.confirm('Delete notification', 'Are you sure you want to delete this notification?', 'Delete', 'Cancel')
      .then(ok => {
        if (!ok) return;
        this.notificationService.deleteNotification(notificationId).subscribe({
          next: () => {
            console.log('Notification deleted');
          },
          error: (error) => {
            console.error('Error deleting notification:', error);
          }
        });
      });
  }

  deleteAll(): void {
    this.confirm.confirm('Clear all notifications', 'Are you sure you want to delete all notifications?', 'Clear All', 'Cancel')
      .then(ok => {
        if (!ok) return;
        this.notificationService.deleteAllNotifications().subscribe({
          next: () => {
            console.log('All notifications deleted');
            this.closeDropdown();
          },
          error: (error) => {
            console.error('Error deleting notifications:', error);
          }
        });
      });
  }

  getNotificationType(type: string): string {
    if (!type) return 'general';
    const lower = type.toLowerCase();
    if (lower.includes('appointment')) return 'appointment';
    if (lower.includes('reminder')) return 'reminder';
    if (lower.includes('payment')) return 'payment';
    return 'general';
  }

  getNotificationIcon(type: string): string {
    return this.notificationService.getNotificationIcon(type);
  }

  getIconColor(type: string): string {
    return this.notificationService.getNotificationColor(type);
  }

  getIconBackground(type: string): string {
    const bgMap: { [key: string]: string } = {
      'appointment': 'bg-blue-100 dark:bg-blue-900/30',
      'reminder': 'bg-orange-100 dark:bg-orange-900/30',
      'payment': 'bg-green-100 dark:bg-green-900/30',
      'general': 'bg-gray-100 dark:bg-gray-700'
    };
    return bgMap[type] || 'bg-gray-100';
  }

  formatTime(dateString: string): string {
    return this.notificationService.formatNotificationTime(dateString);
  }

  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent): void {
    const target = event.target as HTMLElement;
    if (!target.closest('.relative')) {
      this.closeDropdown();
    }
  }
}
