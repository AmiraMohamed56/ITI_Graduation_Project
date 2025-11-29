import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Category } from '../categories/category.model';
import { CategoriesComponent } from '../categories/categories.component';
import { CategoryService } from '../categories/category.service';
import { Doctor } from '../doctors/doctor.model';
import { DoctorService } from '../doctors/doctor.service';
import { DoctorsListComponent } from '../doctors/doctors-list.component';

@Component({
  selector: 'app-landing',
  standalone: true,
  imports: [CommonModule, CategoriesComponent, DoctorsListComponent],
  templateUrl: './landing.component.html',
  styleUrls: ['./landing.component.css']
})
export class LandingComponent implements OnInit {

  categories: Category[] = [];
  doctors: Doctor[] = [];
  loadingDoctors: boolean = false;

  constructor(
    private categoryService: CategoryService,
    private doctorsService: DoctorService
  ) { }

  ngOnInit(): void {
    this.loadCategories();
  }

  loadCategories() {
    this.categoryService.getCategories().subscribe({
      next: data => this.categories = data,
      error: () => console.warn('Failed to load categories')
    });
  }

  onCategorySelected(categoryId: number) {
    this.loadingDoctors = true;
    this.doctorsService.getDoctorsBySpecialty(categoryId).subscribe({
      next: data => {
        this.doctors = data;
        this.loadingDoctors = false;
      },
      error: () => this.loadingDoctors = false
    });
  }
}
