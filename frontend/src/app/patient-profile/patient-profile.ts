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

patientId: number = Number(localStorage.getItem('user_id'));

  patient: any = null;
  loading = false;

  showModal = false;
  editUser: any = {};
  editPatient: any = {};

  activeTab: 'profile' | 'history' | 'appointments' = 'profile';

  constructor(private patientService: PatientService) {}

  ngOnInit(): void {
    if (!this.patientId) {
      console.error("User not logged in!");
      return;
    }
    this.getPatientData();
  }

  /** Load Patient Data */
  getPatientData() {
    this.loading = true;
    this.patientService.getPatientById(this.patientId).subscribe({
      next: (res: any) => {
        this.patient = res.data;

        // دمج البيانات للمواعيد القادمة مع التفاصيل الكاملة
        if (this.patient.appointments && this.patient.upcoming_appointments) {
          this.patient.upcoming_appointments = this.patient.upcoming_appointments.map((upcoming: any) => {
            const fullAppointment = this.patient.appointments.find((app: any) => app.id === upcoming.id);
            return {
              ...upcoming,
              type: fullAppointment?.type,
              price: fullAppointment?.price,
              notes: fullAppointment?.notes,
              doctor: fullAppointment?.doctor,
              schedule: fullAppointment?.schedule,
              record: fullAppointment?.record
            };
          });
        }

        // Copy basic fields to edit forms
        this.editUser = {
          name: this.patient.name,
          email: this.patient.email,
          phone: this.patient.phone,
          profile_pic: null,
        };

        this.editPatient = {
          blood_type: this.patient.blood_type,
          chronic_diseases: this.patient.chronic_diseases,
        };

        this.loading = false;
      },
      error: (err) => {
        console.error("Error loading patient:", err);
        this.loading = false;
      }
    });
  }

  /** Initials for Avatar */
  getInitials(): string {
    if (!this.patient?.name) return '';
    return this.patient.name.split(' ').map((n: any) => n[0]).join('');
  }

  /** Image Preview */
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

  /** Save all changes */
  saveChanges(editForm: any) {
    if (editForm.invalid) {
      Object.values(editForm.controls).forEach((ctrl: any) => ctrl.markAsTouched());
      return;
    }

    // User update
    const formData = new FormData();
    if (this.editUser.name) formData.append("name", this.editUser.name);
    if (this.editUser.email) formData.append("email", this.editUser.email);
    if (this.editUser.phone) formData.append("phone", this.editUser.phone);
    if (this.editUser.profile_pic instanceof File)
      formData.append("profile_pic", this.editUser.profile_pic);

    this.patientService.updateUser(this.patientId, formData).subscribe({
      next: () => {
        // Patient update
        const patientInfo = {
          blood_type: this.editPatient.blood_type,
          chronic_diseases: this.editPatient.chronic_diseases,
        };

        this.patientService.updatePatientInfo(this.patientId, patientInfo).subscribe({
          next: () => {
            this.closeModal();
            this.getPatientData();
          }
        });
      }
    });
  }

  /** Validators */
  getNameError(ctrl: any) {
    if (ctrl.errors?.['required']) return "Name is required";
    if (ctrl.errors?.['minlength']) return "Name must be at least 3 characters";
    return "";
  }

  getEmailError(ctrl: any) {
    if (ctrl.errors?.['required']) return "Email is required";
    if (ctrl.errors?.['email']) return "Invalid email format";
    return "";
  }

  getPhoneError(ctrl: any) {
    if (ctrl.errors?.['required']) return "Phone number is required";
    if (ctrl.errors?.['pattern']) return "Phone must be 10-15 digits";
    return "";
  }

  getBloodError(ctrl: any) {
    if (ctrl.errors?.['required']) return "Blood type is required";
    if (ctrl.errors?.['pattern']) return "Format must be A+, O-, AB+, etc.";
    return "";
  }

  getChronicError(ctrl: any) {
    if (ctrl.errors?.['required']) return "Chronic diseases required";
    if (ctrl.errors?.['minlength']) return "Minimum 5 characters";
    return "";
  }

  /** Remove profile image */
  removeProfilePic() {
    this.patientService.removeProfilePic(this.patientId).subscribe({
      next: () => this.getPatientData()
    });
  }

  /** Modal controls */
  openEditModal() {
    this.editUser = { ...this.patient };
    this.editPatient = { ...this.patient };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  /** MEDICAL HISTORY PAGINATION */
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

  /** UPCOMING APPOINTMENTS PAGINATION */
  currentAppPage = 1;
  appPageSize = 3;

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

  findFullAppointment(upcomingApp: any): any {
    if (!this.patient?.appointments) return null;
    return this.patient.appointments.find((app: any) => app.id === upcomingApp.id);
  }
}
