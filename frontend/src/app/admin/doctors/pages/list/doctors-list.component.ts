import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { DoctorsService, Doctor } from '../../services/doctor.service';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-doctor-list',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './doctors-list.component.html',
  styleUrls: ['./doctors-list.component.css']
})
export class DoctorsListComponent implements OnInit {

  doctors: Doctor[] = [];
  loading: boolean = false;
  error: string = '';

  constructor(private doctorsService: DoctorsService) {}

  ngOnInit(): void {
    this.loadDoctors();
  }

  loadDoctors() {
    this.loading = true;
    this.doctorsService.getDoctors().subscribe({
      next: (data) => {
        this.doctors = data;
        this.loading = false;
      },
      error: () => {
        this.error = 'Error fetching doctors data.';
        this.loading = false;
      }
    });
  }

  deleteDoctor(id: number) {
    if (!confirm('Are you sure you want to delete this doctor?')) return;

    this.doctorsService.deleteDoctor(id).subscribe({
      next: () => {
        this.doctors = this.doctors.filter(d => d.id !== id);
      },
      error: () => {
        this.error = 'Error deleting doctor.';
      }
    });
  }

}
