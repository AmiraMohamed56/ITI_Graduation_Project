import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { DoctorService, DoctorSearchParams } from '../doctors/doctor.service';
import { Doctor } from '../doctors/doctor.model';
import { debounceTime, Subject } from 'rxjs';

@Component({
  selector: 'app-all-doctors',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './all-doctors.html',
  styleUrl: './all-doctors.css',
})
export class AllDoctors implements OnInit {
  doctors: Doctor[] = [];
  
  loading = false;
  error = '';
  
  // Search filters
  searchName = '';
  searchSpecialty = '';
  searchGender = '';
  searchOnlineStatus = '';
  
  // Pagination from API
  currentPage = 1;
  itemsPerPage = 12;
  totalPages = 0;
  totalDoctors = 0;
  
  // Available filter options
  specialties: string[] = [];
  genders = ['Male', 'Female', 'Other'];
  onlineStatuses = [
    { label: 'All', value: '' },
    { label: 'Available Online', value: 'true' },
    { label: 'Not Available Online', value: 'false' }
  ];

  private searchSubject = new Subject<string>();

  constructor(private doctorService: DoctorService) {}

  ngOnInit(): void {
    // Setup debounced search
    this.searchSubject.pipe(
      debounceTime(300)
    ).subscribe(() => {
      this.applyFilters();
    });

    this.loadSpecialties();
    this.loadDoctors();
  }

  loadSpecialties(): void {
    // Load specialties from all doctors for filter dropdown
    this.doctorService.getAllSpecialties().subscribe({
      next: (specialties) => {
        this.specialties = specialties;
      },
      error: (err) => {
        console.error('Error loading specialties:', err);
      }
    });
  }

  loadDoctors(): void {
    this.loading = true;
    this.error = '';
    
    const params: DoctorSearchParams = {
      page: this.currentPage,
      per_page: this.itemsPerPage
    };

    // Add filters if they exist
    if (this.searchName) params.name = this.searchName;
    if (this.searchSpecialty) params.specialty = this.searchSpecialty;
    if (this.searchGender) params.gender = this.searchGender;
    if (this.searchOnlineStatus) params.available_for_online = this.searchOnlineStatus;

    console.log('Loading doctors with params:', params); // Debug log

    this.doctorService.getDoctorsPaginated(params).subscribe({
      next: (response) => {
        console.log('API Response:', response); // Debug log
        this.doctors = response.data;
        this.totalDoctors = response.meta.total;
        this.currentPage = response.meta.current_page;
        this.totalPages = response.meta.last_page;
        this.itemsPerPage = response.meta.per_page;
        
        console.log('Doctors loaded:', this.doctors.length);
        console.log('Total doctors:', this.totalDoctors);
        console.log('Total pages:', this.totalPages);
        console.log('Current page:', this.currentPage);
        
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading doctors:', err);
        this.error = 'Failed to load doctors. Please try again.';
        this.loading = false;
      }
    });
  }

  // Method to handle search name changes with debouncing
  onSearchNameChange(): void {
    this.searchSubject.next(this.searchName);
  }

  applyFilters(): void {
    this.currentPage = 1; // Reset to first page when filters change
    this.loadDoctors();
  }

  onPageChange(page: number): void {
    if (page >= 1 && page <= this.totalPages) {
      this.currentPage = page;
      this.loadDoctors();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  clearFilters(): void {
    this.searchName = '';
    this.searchSpecialty = '';
    this.searchGender = '';
    this.searchOnlineStatus = '';
    this.currentPage = 1;
    this.loadDoctors();
  }

  getDoctorInitial(doctor: Doctor): string {
    return doctor.user.name.charAt(0).toUpperCase() || 'D';
  }

  getDoctorImage(doctor: Doctor): string {
    return doctor.user.profile_picture_url || doctor.user.profile_pic || '';
  }

  // Computed properties for pagination display
  get showingFrom(): number {
    if (this.totalDoctors === 0) return 0;
    return (this.currentPage - 1) * this.itemsPerPage + 1;
  }

  get showingTo(): number {
    const to = this.currentPage * this.itemsPerPage;
    return Math.min(to, this.totalDoctors);
  }

  getPageNumbers(): number[] {
    const pages: number[] = [];
    const maxVisible = 5;
    
    if (this.totalPages <= maxVisible) {
      for (let i = 1; i <= this.totalPages; i++) {
        pages.push(i);
      }
    } else {
      if (this.currentPage <= 3) {
        for (let i = 1; i <= 4; i++) pages.push(i);
        pages.push(-1); // ellipsis
        pages.push(this.totalPages);
      } else if (this.currentPage >= this.totalPages - 2) {
        pages.push(1);
        pages.push(-1);
        for (let i = this.totalPages - 3; i <= this.totalPages; i++) pages.push(i);
      } else {
        pages.push(1);
        pages.push(-1);
        pages.push(this.currentPage - 1);
        pages.push(this.currentPage);
        pages.push(this.currentPage + 1);
        pages.push(-1);
        pages.push(this.totalPages);
      }
    }
    
    return pages;
  }
}