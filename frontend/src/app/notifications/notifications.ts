import { Component, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { NotificationService, Notification } from '../services/notification.service';
import { Subject, takeUntil } from 'rxjs';

@Component({
  selector: 'app-notifications',
 imports: [CommonModule, RouterLink],
  templateUrl: './notifications.html',
  styleUrl: './notifications.css',
})

export class NotificationsComponent implements OnInit, OnDestroy {
  notifications: Notification[] = [];
  unreadCount = 0;
  loading = false;
  hasMore = false;
  selectedFilter = 'all';
  private destroy$ = new Subject<void>();

  filters = [
    { label: 'All', value: 'all', icon: 'fa-list', count: 0 },
    { label: 'Appointments', value: 'appointment', icon: 'fa-calendar-check', count: 0 },
    { label: 'Reminders', value: 'reminder', icon: 'fa-clock', count: 0 },
    { label: 'Payments', value: 'payment', icon: 'fa-credit-card', count: 0 },
    { label: 'Unread', value: 'unread', icon: 'fa-envelope', count: 0 }
  ];

  constructor(private notificationService: NotificationService) {}

  ngOnInit(): void {
    this.loadNotifications();

    this.notificationService.notifications$
      .pipe(takeUntil(this.destroy$))
      .subscribe(notifications => {
        this.notifications = notifications;
        this.updateFilterCounts();
      });

    this.notificationService.unreadCount$
      .pipe(takeUntil(this.destroy$))
      .subscribe(count => {
        this.unreadCount = count;
        this.updateFilterCounts();
      });
  }

  ngOnDestroy(): void {
    this.destroy$.next();
    this.destroy$.complete();
  }

  loadNotifications(): void {
    this.loading = true;
    this.notificationService.loadNotifications();
    setTimeout(() => this.loading = false, 500);
  }

  get filteredNotifications(): Notification[] {
    if (this.selectedFilter === 'all') {
      return this.notifications;
    } else if (this.selectedFilter === 'unread') {
      return this.notifications.filter(n => !n.read_at);
    } else {
      return this.notifications.filter(n =>
        this.getNotificationType(n.type) === this.selectedFilter
      );
    }
  }

  updateFilterCounts(): void {
    this.filters[0].count = this.notifications.length;
    this.filters[1].count = this.notifications.filter(n =>
      this.getNotificationType(n.type) === 'appointment'
    ).length;
    this.filters[2].count = this.notifications.filter(n =>
      this.getNotificationType(n.type) === 'reminder'
    ).length;
    this.filters[3].count = this.notifications.filter(n =>
      this.getNotificationType(n.type) === 'payment'
    ).length;
    this.filters[4].count = this.unreadCount;
  }

  markAsRead(notificationId: string): void {
    this.notificationService.markAsRead(notificationId).subscribe({
      next: () => console.log('Notification marked as read'),
      error: (error) => console.error('Error marking notification:', error)
    });
  }

  markAllAsRead(): void {
    this.notificationService.markAllAsRead().subscribe({
      next: () => console.log('All notifications marked as read'),
      error: (error) => console.error('Error:', error)
    });
  }

  deleteNotification(notificationId: string): void {
    if (confirm('Are you sure you want to delete this notification?')) {
      this.notificationService.deleteNotification(notificationId).subscribe({
        next: () => console.log('Notification deleted'),
        error: (error) => console.error('Error:', error)
      });
    }
  }

  deleteAll(): void {
    if (confirm('Are you sure you want to delete all notifications?')) {
      this.notificationService.deleteAllNotifications().subscribe({
        next: () => console.log('All notifications deleted'),
        error: (error) => console.error('Error:', error)
      });
    }
  }

  loadMore(): void {
    // Implement pagination if needed
  }

  getNotificationType(type: string): string {
    if (type.includes('Appointment')) return 'appointment';
    if (type.includes('Reminder')) return 'reminder';
    if (type.includes('Payment')) return 'payment';
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

  formatDate(dateString: string): string {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric',
      year: 'numeric'
    });
  }
}
