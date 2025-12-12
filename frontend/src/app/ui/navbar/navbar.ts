// import { NotificationDropdownComponent } from './../../notification-dropdown/notification-dropdown';
// import { CommonModule, NgFor, NgIf } from '@angular/common';
// import { Component, HostListener, OnInit } from '@angular/core';
// import { Router, RouterLink, RouterLinkActive, RouterModule } from '@angular/router';
// import { AuthService } from '../../services/auth.service';
// import { PatientService } from '../../services/patientProfile.service';

import { NgFor, NgIf } from '@angular/common';
import { Component, HostListener, OnInit, OnDestroy } from '@angular/core';
import { Router, RouterLink, RouterLinkActive } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { PatientService } from '../../services/patientProfile.service';
import { NotificationService, Notification } from '../../services/notification.service';
import { NotificationDropdownComponent } from '../../notification-dropdown/notification-dropdown';
import { Subscription } from 'rxjs';

// Add to imports array
@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [RouterLink, RouterLinkActive, NgIf, NgFor, NotificationDropdownComponent],
  templateUrl: './navbar.html',
  styleUrls: ['./navbar.css'],
})
export class NavbarComponent implements OnInit {
  showProfile = false;
  showMobileMenu = false;

  isLoggedIn = false;
  user: any = null;
  patient: any = null;
  patientProfilePic: string = '/default_profile.jpg';
  userInitials: string = 'U';

  constructor(
    private auth: AuthService,
    private router: Router,
    private patientService: PatientService,
  ) {}

  ngOnInit(): void {
    this.loadUserState();

    this.router.events.subscribe(() => {
      this.showProfile = false;
      this.showMobileMenu = false;
    });
  }

  loadUserState() {
    const savedUser = localStorage.getItem('user');
    this.isLoggedIn = !!savedUser;
    this.user = savedUser ? JSON.parse(savedUser) : null;

    if (this.isLoggedIn && this.user) {
      // تأكد من استخراج البيانات من الهيكل الصحيح
      this.processUserData();

      // جلب بيانات المريض إذا كان موجودًا
      if (this.user.id) {
        this.patientService.getPatientById(this.user.id).subscribe(
          (res: any) => {
            if (res && res.data) {
              this.patient = res.data;
              // تحديث الصورة إذا كانت موجودة
              if (this.patient?.profile_pic) {
                this.patientProfilePic = this.patient.profile_pic;
              }
              // تحديث الحروف الأولى
              this.updateUserInitials();
            }
          },
          (error) => {
            console.error('Error loading patient data:', error);
          }
        );
      }
    }
  }

  private processUserData() {
    // معالجة البيانات بناءً على الهيكل المزدوج
    if (this.user && this.user.user) {
      // إذا كان الهيكل: {user: {id: xxx, name: xxx, email: xxx}}
      // نستخدم البيانات الداخلية
      this.userInitials = this.getInitials(this.user.user.name);
    } else if (this.user && this.user.name) {
      // إذا كان الهيكل: {id: xxx, name: xxx, email: xxx}
      this.userInitials = this.getInitials(this.user.name);
    } else {
      this.userInitials = 'U';
    }
  }

  private updateUserInitials() {
    if (this.patient && this.patient.user && this.patient.user.name) {
      this.userInitials = this.getInitials(this.patient.user.name);
    } else if (this.user && this.user.user && this.user.user.name) {
      this.userInitials = this.getInitials(this.user.user.name);
    } else if (this.user && this.user.name) {
      this.userInitials = this.getInitials(this.user.name);
    }
  }

  getInitials(name: string): string {
    if (!name || name.trim() === '') return 'U';

    const names = name.trim().split(' ');
    if (names.length === 1) {
      return names[0].charAt(0).toUpperCase();
    } else {
      return (names[0].charAt(0) + names[names.length - 1].charAt(0)).toUpperCase();
    }
  }

  getUserName(): string {
    if (this.patient && this.patient.user && this.patient.user.name) {
      return this.patient.user.name;
    } else if (this.user && this.user.user && this.user.user.name) {
      return this.user.user.name;
    } else if (this.user && this.user.name) {
      return this.user.name;
    }
    return 'User';
  }

  getUserEmail(): string {
    if (this.patient && this.patient.user && this.patient.user.email) {
      return this.patient.user.email;
    } else if (this.user && this.user.user && this.user.user.email) {
      return this.user.user.email;
    } else if (this.user && this.user.email) {
      return this.user.email;
    }
    return '';
  }

  getUserRole(): string {
    if (this.user && this.user.role) {
      return this.user.role;
    }
    return 'Patient';
  }



  toggleProfile(): void {
    this.showProfile = !this.showProfile;
  }

  toggleMobileMenu(): void {
    this.showMobileMenu = !this.showMobileMenu;
  }

  closeMobileMenu(): void {
    this.showMobileMenu = false;
  }

  logout(): void {
    this.auth.logout();
    this.isLoggedIn = false;
    this.user = null;
    this.patient = null;
    this.patientProfilePic = '/default_profile.jpg';
    this.userInitials = 'U';
    this.showProfile = false;
    this.router.navigate(['/']);
  }

  // إغلاق القوائم المنسدلة عند النقر خارجها
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
  }

  // دالة للتعامل مع أخطاء تحميل الصور
  handleImageError(event: any): void {
    event.target.style.display = 'none';
    const parent = event.target.parentElement;
    const fallback = parent.querySelector('.avatar-fallback');
    if (fallback) {
      fallback.style.display = 'flex';
    }
  }
}
