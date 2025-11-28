import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { DoctorsService, Doctor } from '../../services/doctor.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-add-doctor',
  templateUrl: './add-doctor.component.html',
  imports: [CommonModule, FormsModule],
  styleUrls: ['./add-doctor.component.css']
})
export class AddDoctorComponent {

  newDoctor: Doctor = {
    user_id: 0,
    specialty_id: 0,
    bio: '',
    education: '',
    years_experience: 0,
    gender: 'male',
    consultation_fee: 0,
    available_for_online: false
  };

  loading: boolean = false;
  error: string = '';

  constructor(private doctorsService: DoctorsService, private router: Router) { }

  addDoctor() {
    this.loading = true;
    this.doctorsService.addDoctor(this.newDoctor).subscribe({
      next: (data) => {
        this.loading = false;
        this.router.navigate(['/admin/doctors']);
      },
      error: () => {
        this.loading = false;
        this.error = 'Error occurred while adding doctor';
      }
    });
  }
}
