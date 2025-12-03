import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { NgIf, NgFor } from '@angular/common';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, NgIf, NgFor],
  templateUrl: './navbar.html',
})
export class Navbar {
  showMenu = false;
  showNotifications = false;

  // unreadCount = 3;
  notifications: { message: string; time: string, read: boolean }[]  = [
    { message: 'New message from Admin', time: '2 mins ago', read: false },
    { message: 'Your post has a new comment', time: '10 mins ago', read: false },
    { message: 'System update scheduled', time: '1 hour ago', read: false },
  ];

  get unreadCount(): number {
    return this.notifications.filter(n=> !n.read).length;
  }

  toggleMenu() {
    this.showMenu = !this.showMenu;
  }

  toggleNotifications() {
    this.showNotifications = !this.showNotifications;

    // mark all notifications as read when opeing the dropdown
    if(this.showNotifications) {
      this.notifications = this.notifications.map(n=> ({...n, read:true}));
    }
  }
}
