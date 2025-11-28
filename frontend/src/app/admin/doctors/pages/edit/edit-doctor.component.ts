import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { DoctorsService, Doctor } from '../../services/doctor.service';
import { RouterModule, ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-edit-doctor',
  standalone: true,
  templateUrl: './edit-doctor.component.html',
  styleUrls: ['./edit-doctor.component.css'],
  imports: [CommonModule, FormsModule, RouterModule]
})
export class EditDoctorComponent implements OnInit {

  doctorId!: number;
  doctor: Doctor | null = null;
  loading = false;
  error = '';

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private doctorsService: DoctorsService
  ) {}

  ngOnInit(): void {
    this.doctorId = Number(this.route.snapshot.paramMap.get('id'));
    this.loadDoctor();
  }

  loadDoctor() {
    this.loading = true;

    this.doctorsService.getDoctorById(this.doctorId).subscribe({
      next: (data) => {
        this.doctor = data;
        this.loading = false;
      },
      error: () => {
        this.error = 'Error loading doctor data.';
        this.loading = false;
      }
    });
  }

  updateDoctor() {
    if (!this.doctor) return;

    this.loading = true;

    this.doctorsService.updateDoctor(this.doctorId, this.doctor).subscribe({
      next: () => {
        this.loading = false;
        this.router.navigate(['/admin/doctors']);
      },
      error: () => {
        this.error = 'Error updating doctor.';
        this.loading = false;
      }
    });
  }
}
