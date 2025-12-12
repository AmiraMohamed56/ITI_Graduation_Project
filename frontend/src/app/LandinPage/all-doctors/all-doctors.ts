import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterModule , ActivatedRoute ,Router  } from '@angular/router';
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
  genders = ['Male', 'Female'];
  onlineStatuses = [
    { label: 'All', value: '' },
    { label: 'Available Online', value: 'true' },
    { label: 'Not Available Online', value: 'false' }
  ];

  // Specialty filtering
  specialtyId: number | null = null;
  specialtyName: string = '';

  private searchSubject = new Subject<string>();

  constructor(private doctorService: DoctorService,
              private route: ActivatedRoute,
              private router: Router
  ) {}

  ngOnInit(): void {
    // Setup debounced search
    this.searchSubject.pipe(
      debounceTime(300)
    ).subscribe(() => {
      this.applyFilters();
    });

    // Always load specialties for the dropdown filter
    this.loadSpecialties();


// Listen for query parameters
      this.route.queryParams.subscribe(params => {
      this.specialtyId = params['specialty_id'] ? +params['specialty_id'] : null;

      if (this.specialtyId) {
        // If we have a specialty_id, load doctors for that specialty
        this.loadDoctorsBySpecialty();
      } else {
        // Otherwise load all doctors with pagination
        this.loadSpecialties();
        this.loadDoctors();
      }
    });
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

   loadDoctorsBySpecialty(): void {
    if (!this.specialtyId) return;

    this.loading = true;
    this.error = '';

    this.doctorService.getDoctorsBySpecialty(this.specialtyId, 100).subscribe({
      next: (data: Doctor[]) => {
        this.doctors = data;
        this.totalDoctors = data.length;

        // Get specialty name from first doctor
        if (data.length > 0 && data[0].specialty) {
          this.specialtyName = data[0].specialty.name;
          this.searchSpecialty = data[0].specialty.name;
        }

        // Calculate pagination for client-side filtering
        this.totalPages = Math.ceil(this.doctors.length / this.itemsPerPage);
        this.currentPage = 1;

        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading doctors by specialty:', err);
        this.error = 'Failed to load doctors for this specialty. Please try again.';
        this.loading = false;
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
    if (this.searchGender) params.gender = this.searchGender.toLowerCase();
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
    this.currentPage = 1;

    // If user selected a specialty from dropdown, clear the URL specialty_id and use API filtering
    if (this.searchSpecialty) {
      this.specialtyId = null;
      this.specialtyName = '';
      // Clear the query parameter from URL
      this.router.navigate([], {
        relativeTo: this.route,
        queryParams: {},
        queryParamsHandling: 'merge'
      });
    }

    // Always use paginated API when applying filters
    this.loadDoctors();
  }


   onPageChange(page: number): void {
    if (page >= 1 && page <= this.totalPages) {
      this.currentPage = page;

      if (this.specialtyId) {
        // For specialty view, just change page (already have all data)
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } else {
        this.loadDoctors();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
    }
  }

  clearFilters(): void {
    this.searchName = '';
    this.searchSpecialty = '';
    this.searchGender = '';
    this.searchOnlineStatus = '';
    this.currentPage = 1;
    this.specialtyId = null;
    this.specialtyName = '';
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

  // Get paginated doctors for display (when viewing by specialty)
  get paginatedDoctors(): Doctor[] {
    if (this.specialtyId) {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return this.doctors.slice(startIndex, endIndex);
    }
    return this.doctors;
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
