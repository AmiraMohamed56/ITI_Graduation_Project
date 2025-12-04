import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { PatientService } from '../services/patientProfile.service';

@Component({
  selector: 'app-patient-profile',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './patient-profile.html',
  styleUrl: './patient-profile.css',
})
export class PatientProfile implements OnInit {

  patientId = 31; // مؤقت لحين عمل Login

  patient: any = null;
  loading = false;

  showModal = false;
  editUser: any = {};
  editPatient: any = {};
activeTab: 'profile' | 'history' | 'appointments' = 'profile';
  constructor(private patientService: PatientService) {}

  ngOnInit(): void {
    this.getPatientData();
  }
loadPatient() {
    this.loading = true;
    this.patientService.getPatientById(this.patientId).subscribe({
      next: (res: any) => {
        this.patient = res.data;
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading patient', err);
        this.loading = false;
      }
    });
  }

  getInitials(): string {
    if (!this.patient?.name) return '';
    return this.patient.name.split(' ').map((n: any) => n[0]).join('');
  }
  /** Load Patient Data */
  getPatientData() {
    this.loading = true;
    this.patientService.getPatientById(this.patientId).subscribe({
      next: (res: any) => {
        this.patient = res.data;
        this.editUser = { ...this.patient };
        this.editPatient = { ...this.patient };
        this.loading = false;
      },
      error: (err) => {
        console.error("Error loading patient:", err);
        this.loading = false;
      }
    });
  }

  /** Select new Profile Image */
imagePreview: string | null = null;

onImageSelected(event: any) {
  const file = event.target.files[0];

  if (file) {
    this.editUser.profile_pic = file;

    const reader = new FileReader();
    reader.onload = () => {
      this.imagePreview = reader.result as string;
    };
    reader.readAsDataURL(file);
  }
}


  /** Update All (User + Patient Info) */

  
  saveChanges() {
    const formData = new FormData();

    if (this.editUser.name) formData.append("name", this.editUser.name);
    if (this.editUser.email) formData.append("email", this.editUser.email);
    if (this.editUser.phone) formData.append("phone", this.editUser.phone);
    if (this.editUser.profile_pic instanceof File)
      formData.append("profile_pic", this.editUser.profile_pic);

    // Update User Info
    this.patientService.updateUser(this.patientId, formData).subscribe({
      next: () => {

        // Update Patient Info
        const patientInfo = {
          blood_type: this.editPatient.blood_type,
          chronic_diseases: this.editPatient.chronic_diseases,
        };

        this.patientService.updatePatientInfo(this.patientId, patientInfo)
          .subscribe({
            next: () => {
              this.closeModal();
              this.getPatientData();
            }
          });
      }
    });
  }

  /** Remove Profile Picture */
  removeProfilePic() {
    this.patientService.removeProfilePic(this.patientId).subscribe({
      next: () => this.getPatientData()
    });
  }

  /** Open / Close Modal */
  openEditModal() {
    this.editUser = { ...this.patient };
    this.editPatient = { ...this.patient };
    this.showModal = true;
  }
filePreview(file: File): string {
    const reader = new FileReader();
    let preview = '';
    reader.onload = (e: any) => {
      preview = e.target.result;
    };
    reader.readAsDataURL(file);
    return preview;
  }
  closeModal() {
    this.showModal = false;
  }
currentPage = 1;
pageSize = 4;
get totalPages(): number {
  return Math.ceil(this.patient?.medical_history?.length / this.pageSize) || 1;
}

get pagedRecords() {
  if (!this.patient?.medical_history) return [];
  const start = (this.currentPage - 1) * this.pageSize;
  return this.patient.medical_history.slice(start, start + this.pageSize);
}

goToPage(page: number) {
  if (page >= 1 && page <= this.totalPages) this.currentPage = page;
}
// Pagination Variables
currentAppPage = 1;
appPageSize = 3; // عدد الكروت لكل صفحة

get totalAppPages(): number {
  return Math.ceil(this.patient?.upcoming_appointments?.length / this.appPageSize) || 1;
}

get pagedAppointments() {
  if (!this.patient?.upcoming_appointments) return [];
  const start = (this.currentAppPage - 1) * this.appPageSize;
  return this.patient.upcoming_appointments.slice(start, start + this.appPageSize);
}

goToAppPage(page: number) {
  if (page >= 1 && page <= this.totalAppPages) this.currentAppPage = page;
}

}
