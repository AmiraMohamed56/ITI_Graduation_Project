import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { DoctorsService, Doctor } from '../../services/doctor.service';
import { RouterModule } from '@angular/router';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-doctor-details',
  templateUrl: './doctor-details.component.html',
    imports: [CommonModule, FormsModule,RouterModule],

  styleUrls: ['./doctor-details.component.css']
})
export class DoctorDetailsComponent implements OnInit {

  doctorId!: number;
  doctor: Doctor | null = null;
  loading: boolean = false;
  error: string = '';

  constructor(
    private route: ActivatedRoute,
    private doctorsService: DoctorsService
  ) { }

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
        this.error = 'حدث خطأ أثناء تحميل بيانات الطبيب';
        this.loading = false;
      }
    });
  }
}
