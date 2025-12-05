import { Component, Input, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { DoctorService } from './doctor.service';
import { Doctor } from './doctor.model';

@Component({
  selector: 'app-doctors-list',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './doctors-list.component.html',
  styleUrls: ['./doctors-list.component.css']
})
export class DoctorsListComponent implements OnInit {
  @Input() doctors: Doctor[] = [];
  @Input() showSeeAll: boolean = true;
  @Input() maxDisplay: number = 8;
  
  loading = false;
  error = '';

  constructor(private doctorService: DoctorService) {}

  ngOnInit(): void {
    // Only load doctors if not provided via @Input
    if (this.doctors.length === 0) {
      this.loadDoctors();
    }
  }

  loadDoctors(): void {
    this.loading = true;
    this.error = '';
    
    this.doctorService.getDoctors().subscribe({
      next: (data: Doctor[]) => {
        this.doctors = data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading doctors:', err);
        this.error = 'Failed to load doctors. Please try again.';
        this.loading = false;
      }
    });
  }

  get displayedDoctors(): Doctor[] {
    return this.showSeeAll ? this.doctors.slice(0, this.maxDisplay) : this.doctors;
  }

  getDoctorInitial(doctor: Doctor): string {
    return doctor.user?.name?.charAt(0).toUpperCase() || 'D';
  }

  getDoctorImage(doctor: Doctor): string {
    return doctor.user?.profile_picture_url || doctor.user?.profile_pic || '';
  }
}