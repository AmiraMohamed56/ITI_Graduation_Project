import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router , RouterModule } from '@angular/router';
import { Specialty } from './specialty.model';
import { SpecialtyService } from '../services/specialities.service';

@Component({
  selector: 'app-specialities',
  imports: [CommonModule, FormsModule],
  templateUrl: './specialities.html',
  styleUrl: './specialities.css',
})
export class Specialities implements OnInit {
  specialties: Specialty[] = [];
  loading: boolean = true;
  displayLimit: number = 8;

  constructor(
    private specialtyService: SpecialtyService,
    private router: Router
  ) {}

   ngOnInit(): void {
    this.loadSpecialties();
  }

  loadSpecialties(): void {
    this.loading = true;
    // Load only 8 for landing page
    this.specialtyService.getSpecialties(8).subscribe({
      next: (response) => {
        this.specialties = (response.data || []).slice(0, 8);
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading specialties:', err);
        this.specialties = [];
        this.loading = false;
      }
    });
  }

  viewAllSpecialties(): void {
    this.router.navigate(['/specialties']);
  }

  viewSpecialtyDoctors(specialtyId: number): void {
    this.router.navigate(['/doctors'], {
      queryParams: { specialty_id: specialtyId }
    });
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
}
