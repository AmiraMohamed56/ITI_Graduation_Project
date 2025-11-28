import { Component, OnInit ,Input } from '@angular/core';
import { DoctorService } from './doctor.service';
import { Doctor } from './doctor.model';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-doctors-list',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './doctors-list.component.html',
  styleUrls: ['./doctors-list.component.css']
})
export class DoctorsListComponent implements OnInit {
  @Input() doctors: Doctor[] = [];

  // doctors: Doctor[] = [];
  loading: boolean = false;
  error: string = '';

  constructor(private doctorService: DoctorService) { }

  ngOnInit(): void {
    this.loadDoctors();
  }

  loadDoctors() {
    this.loading = true;
    this.doctorService.getDoctors().subscribe({
      next: (data) => {
        this.doctors = data;
        this.loading = false;
      },
      error: () => {
        this.error = 'حدث خطأ أثناء جلب بيانات الأطباء';
        this.loading = false;
      }
    });
  }
}
