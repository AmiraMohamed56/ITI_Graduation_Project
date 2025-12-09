import { NgFor, NgIf } from '@angular/common';
import { Component, HostListener, OnInit } from '@angular/core';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../services/auth.service';
@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, NgIf, NgFor],
  templateUrl: './navbar.html',
  styleUrls: ['./navbar.css'],
})
export class NavbarComponent implements OnInit {
  showNotifications = false;
  showProfile = false;
  showMobileMenu = false;

  isLoggedIn = false;
  user: any = null;

  constructor(private auth: AuthService, private router: Router) {}

  ngOnInit(): void {
    this.loadUserState();

    this.router.events.subscribe(() => {
      this.showNotifications = false;
      this.showProfile = false;
      this.showMobileMenu = false;
    });

  }

  loadUserState() {
    const savedUser = localStorage.getItem('user');
    this.isLoggedIn = !!savedUser;
    this.user = savedUser ? JSON.parse(savedUser) : null;
  }

  toggleNotifications(): void {
    this.showNotifications = !this.showNotifications;
    if (this.showNotifications) this.showProfile = false;
  }

  toggleProfile(): void {
    this.showProfile = !this.showProfile;
    if (this.showProfile) this.showNotifications = false;
  }

  toggleMobileMenu(): void {
    this.showMobileMenu = !this.showMobileMenu;
  }

  closeMobileMenu(): void {
    this.showMobileMenu = false;
  }

  logout(): void {
    this.auth.logout();
    this.showProfile = false;
  }

  // Close dropdowns when clicking outside
  @HostListener('document:click', ['$event'])
  onDocumentClick(event: MouseEvent): void {
    const target = event.target as HTMLElement;

    // Profile dropdown
    const profileDropdown = document.querySelector('.profile-dropdown');
    if (
      this.showProfile &&
      profileDropdown &&
      !profileDropdown.contains(target) &&
      !target.closest('.profile-button')
    ) {
      this.showProfile = false;
    }

    // Notifications dropdown
    const notifDropdown = document.querySelector('.notifications-dropdown');
    if (
      this.showNotifications &&
      notifDropdown &&
      !notifDropdown.contains(target) &&
      !target.closest('.notifications-button')
    ) {
      this.showNotifications = false;
    }
  }
}
