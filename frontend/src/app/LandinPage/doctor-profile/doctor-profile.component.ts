import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DoctorService } from '../doctors/doctor.service';
import { Doctor } from '../doctors/doctor.model';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-doctor-profile',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './doctor-profile.component.html',
  styleUrls: ['./doctor-profile.component.css']
})
export class DoctorProfileComponent implements OnInit {

  doctor: Doctor | null = null;
  loading: boolean = false;
  error: string = '';

  constructor(
    private route: ActivatedRoute,
    private doctorService: DoctorService,
    private router: Router

  ) {}

  ngOnInit(): void {
    const doctorId = Number(this.route.snapshot.paramMap.get('id'));
    if (doctorId) this.loadDoctor(doctorId);
  }

  loadDoctor(id: number) {
    this.loading = true;
    this.doctorService.getDoctorById(id).subscribe({
      next: data => {
        this.doctor = data;
        this.loading = false;
      },
      error: () => {
        this.error = 'Error loading doctor profile.';
        this.loading = false;
      }
    });
  }

  bookAppointment() {
    this.router.navigate([`/book-appointment/`], {
      queryParams: { doctorId: this.doctor?.id }
    });

  }
}
