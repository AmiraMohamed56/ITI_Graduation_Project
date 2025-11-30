// import { Component, OnInit } from '@angular/core';
// import { CommonModule } from '@angular/common';
// import { Category } from '../categories/category.model';
// import { CategoriesComponent } from '../categories/categories.component';
// import { CategoryService } from '../categories/category.service';
// import { Doctor } from '../doctors/doctor.model';
// import { DoctorService } from '../doctors/doctor.service';
// import { DoctorsListComponent } from '../doctors/doctors-list.component';

// @Component({
//   selector: 'app-landing',
//   standalone: true,
//   imports: [CommonModule, CategoriesComponent, DoctorsListComponent],
//   templateUrl: './landing.component.html',
//   styleUrls: ['./landing.component.css']
// })
// export class LandingComponent implements OnInit {

//   categories: Category[] = [];
//   doctors: Doctor[] = [];
//   loadingDoctors: boolean = false;

//   constructor(
//     private categoryService: CategoryService,
//     private doctorsService: DoctorService
//   ) { }

//   ngOnInit(): void {
//     this.loadCategories();
//   }

//   loadCategories() {
//     this.categoryService.getCategories().subscribe({
//       next: data => this.categories = data,
//       error: () => console.warn('Failed to load categories')
//     });
//   }

//   onCategorySelected(categoryId: number) {
//     this.loadingDoctors = true;
//     this.doctorsService.getDoctorsBySpecialty(categoryId).subscribe({
//       next: data => {
//         this.doctors = data;
//         this.loadingDoctors = false;
//       },
//       error: () => this.loadingDoctors = false
//     });
//   }
// }



import { CommonModule } from '@angular/common';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { CategoriesComponent } from '../categories/categories.component';
import { DoctorsListComponent } from '../doctors/doctors-list.component';
import { Category } from '../categories/category.model';
import { Doctor } from '../doctors/doctor.model';
import { CategoryService } from '../categories/category.service';
import { DoctorService } from '../doctors/doctor.service';

@Component({
  selector: 'app-landing',
  standalone: true,
  imports: [CommonModule, CategoriesComponent, DoctorsListComponent],
  templateUrl: './landing.component.html',
  styleUrls: ['./landing.component.css']
})
export class LandingComponent implements OnInit, OnDestroy {
  currentSlide = 0;
  totalSlides = 3;
  autoSlideInterval: any;

  slides = [
    {
      image: 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=1920&h=800&fit=crop',
      title: 'Excellence in Healthcare',
      subtitle: 'Providing compassionate care with cutting-edge medical technology'
    },
    {
      image: 'https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?w=1920&h=800&fit=crop',
      title: 'Expert Medical Team',
      subtitle: 'Board-certified physicians dedicated to your health and wellness'
    },
    {
      image: 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=1920&h=800&fit=crop',
      title: 'Modern Facilities',
      subtitle: 'State-of-the-art equipment for accurate diagnosis and treatment'
    }
  ];

  ngOnInit(): void {
    this.startAutoSlide();
    this.loadCategories();
  }

  ngOnDestroy(): void {
    this.stopAutoSlide();
  }

  showSlide(index: number): void {
    this.currentSlide = index;
  }

  nextSlide(): void {
    this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
    this.resetAutoSlide();
  }

  prevSlide(): void {
    this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
    this.resetAutoSlide();
  }

  goToSlide(index: number): void {
    this.currentSlide = index;
    this.resetAutoSlide();
  }

  startAutoSlide(): void {
    this.autoSlideInterval = setInterval(() => {
      this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
    }, 5000);
  }

  stopAutoSlide(): void {
    if (this.autoSlideInterval) {
      clearInterval(this.autoSlideInterval);
    }
  }

  resetAutoSlide(): void {
    this.stopAutoSlide();
    this.startAutoSlide();
  }

  categories: Category[] = [];
  doctors: Doctor[] = [];
  loadingDoctors: boolean = false;

  constructor(
    private categoryService: CategoryService,
    private doctorsService: DoctorService
  ) { }

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