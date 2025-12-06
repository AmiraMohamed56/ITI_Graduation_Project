// all-specialties.component.ts (FIXED VERSION)
import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { SpecialtyService } from '../services/specialities.service';
import { Specialty } from './specialty.model';
import { Subject } from 'rxjs';
import { debounceTime, distinctUntilChanged } from 'rxjs/operators';

@Component({
  selector: 'app-all-specialties',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './all-specialties.html',
  styleUrls: ['./all-specialties.css']
})
export class AllSpecialtiesComponent implements OnInit {
  specialties: Specialty[] = [];
  loading: boolean = true;
  searchTerm: string = '';
  totalSpecialties: number = 0;

  // Pagination
  currentPage: number = 1;
  itemsPerPage: number = 12;
  totalPages: number = 1;

  // For debouncing search input
  private searchSubject = new Subject<string>();

  constructor(
    private specialtyService: SpecialtyService,
    private router: Router
  ) {
    // Setup debounced search - wait 300ms after user stops typing
    this.searchSubject.pipe(
      debounceTime(300),
      distinctUntilChanged()
    ).subscribe(searchTerm => {
      this.performSearch(searchTerm);
    });
  }

  ngOnInit(): void {
    this.loadSpecialties();
  }

  loadSpecialties(search?: string): void {
    this.loading = true;
    // Load all specialties (increase per_page to get all)
    this.specialtyService.getSpecialties(1000, search).subscribe({
      next: (response) => {
        console.log('Search term:', search);
        console.log('Backend returned:', response.data?.length, 'items');

        this.specialties = response.data || [];
        this.totalSpecialties = response.meta?.total || this.specialties.length;

        // FIXED: Calculate pagination based on filtered results
        this.totalPages = Math.ceil(this.specialties.length / this.itemsPerPage);

        // FIXED: Reset to page 1 if current page exceeds total pages
        if (this.currentPage > this.totalPages && this.totalPages > 0) {
          this.currentPage = 1;
        }

        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading specialties:', err);
        this.specialties = [];
        this.totalSpecialties = 0;
        this.totalPages = 0;
        this.loading = false;
      }
    });
  }

  onSearchInput(): void {
    // Emit search term to the subject (will be debounced)
    this.searchSubject.next(this.searchTerm);
  }

  performSearch(searchTerm: string): void {
    // Reset pagination when searching
    this.currentPage = 1;

    if (!searchTerm.trim()) {
      this.loadSpecialties();
    } else {
      this.loadSpecialties(searchTerm);
    }
  }

  clearSearch(): void {
    this.searchTerm = '';
    this.currentPage = 1;
    this.loadSpecialties();
  }

  // FIXED: This getter now correctly paginates the filtered results
  get paginatedSpecialties(): Specialty[] {
    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
    const endIndex = startIndex + this.itemsPerPage;
    return this.specialties.slice(startIndex, endIndex);
  }

  nextPage(): void {
    if (this.currentPage < this.totalPages) {
      this.currentPage++;
      this.scrollToTop();
    }
  }

  previousPage(): void {
    if (this.currentPage > 1) {
      this.currentPage--;
      this.scrollToTop();
    }
  }

  goToPage(page: number): void {
    this.currentPage = page;
    this.scrollToTop();
  }

  get pageNumbers(): number[] {
    const pages: number[] = [];
    const maxPagesToShow = 5;

    let startPage = Math.max(1, this.currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(this.totalPages, startPage + maxPagesToShow - 1);

    if (endPage - startPage < maxPagesToShow - 1) {
      startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      pages.push(i);
    }

    return pages;
  }

  scrollToTop(): void {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  viewSpecialtyDoctors(specialtyId: number): void {
    this.router.navigate(['/doctors'], {
      queryParams: { specialty_id: specialtyId }
    });
  }

  goBack(): void {
    this.router.navigate(['/']);
  }

  getSpecialtyIcon(name: string): string {
    const iconMap: { [key: string]: string } = {
      'cardiology': 'fa-heart-pulse',
      'neurology': 'fa-brain',
      'orthopedics': 'fa-bone',
      'orthopedic': 'fa-bone',
      'pediatrics': 'fa-baby',
      'pediatric': 'fa-baby',
      'dermatology': 'fa-hand-dots',
      'ophthalmology': 'fa-eye',
      'eye': 'fa-eye',
      'psychiatry': 'fa-head-side-virus',
      'mental': 'fa-head-side-virus',
      'radiology': 'fa-x-ray',
      'surgery': 'fa-user-doctor',
      'surgeon': 'fa-user-doctor',
      'general': 'fa-stethoscope',
      'dentistry': 'fa-tooth',
      'dental': 'fa-tooth',
      'oncology': 'fa-ribbon',
      'cancer': 'fa-ribbon',
      'gynecology': 'fa-venus',
      'obstetrics': 'fa-baby-carriage',
      'urology': 'fa-droplet',
      'ent': 'fa-ear-listen',
      'gastro': 'fa-stomach',
      'pulmonology': 'fa-lungs',
      'respiratory': 'fa-lungs',
      'nephrology': 'fa-kidney',
      'endocrinology': 'fa-syringe',
      'anesthesia': 'fa-hand-holding-medical',
      'pathology': 'fa-microscope',
      'emergency': 'fa-truck-medical',
      'physical': 'fa-dumbbell',
      'rehabilitation': 'fa-wheelchair'
    };

    const key = name.toLowerCase();
    for (const [keyword, icon] of Object.entries(iconMap)) {
      if (key.includes(keyword)) {
        return icon;
      }
    }
    return 'fa-stethoscope'; // default icon
  }

  ngOnDestroy(): void {
    this.searchSubject.complete();
  }
}
