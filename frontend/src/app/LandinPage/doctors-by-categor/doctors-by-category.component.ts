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
  doctors: Doctor[] = [];
  loading: boolean = true;

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
    this.doctorService.getDoctorsBySpecialty(this.categoryId).subscribe({
      next: data => {
        this.doctors = data;
        this.loading = false;
      },
      error: () => this.loading = false
    });
  }
}
