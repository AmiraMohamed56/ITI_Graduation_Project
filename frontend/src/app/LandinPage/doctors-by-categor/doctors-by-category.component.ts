import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';
import { Doctor } from '../doctors/doctor.model';
import { DoctorService } from '../doctors/doctor.service';
import { DoctorsListComponent } from '../doctors/doctors-list.component';

@Component({
  selector: 'app-doctors-by-category',
  standalone: true,
  imports: [CommonModule, DoctorsListComponent],
  templateUrl: './doctors-by-category.component.html',
  styleUrls: ['./doctors-by-category.component.css']
})
export class DoctorsByCategoryComponent implements OnInit {

  categoryId!: number;
  categoryName: string = '';
  doctors: Doctor[] = [];
  loading: boolean = true;
  error: string = '';

  constructor(
    private route: ActivatedRoute,
    private doctorService: DoctorService
  ) {}

  ngOnInit(): void {
    this.route.params.subscribe(params => {
      this.categoryId = +params['id'];
      this.loadDoctors();
    });
  }

  loadDoctors() {
    this.loading = true;
    this.error = '';
    
    this.doctorService.getDoctorsBySpecialty(this.categoryId).subscribe({
      next: data => {
        this.doctors = data;
        
        // Get category name from first doctor's specialty
        if (data.length > 0 && data[0].specialty) {
          this.categoryName = data[0].specialty.name;
        }
        
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading doctors:', err);
        this.error = 'Failed to load doctors. Please try again.';
        this.loading = false;
      }
    });
  }
}