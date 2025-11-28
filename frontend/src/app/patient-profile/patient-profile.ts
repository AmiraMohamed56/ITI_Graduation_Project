import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';


interface Prescription {
  name: string;
  dosage: string;
}

interface Appointment {
  date: string;
  doctorName: string;
  reason: string;
}

interface Patient {
  name: string;
  email: string;
  phone?: string;
  address?: string;
  date_of_birth: string;
  profileImage?: string;
  medicalHistory?: string;
  prescriptions: Prescription[];
  allergies: string[];
}

@Component({
  selector: 'app-patient-profile',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './patient-profile.html',
  styleUrl: './patient-profile.css',
})
export class PatientProfile {

  showModal: boolean = false;

  patient: Patient = {
    name: 'John Doe',
    email: 'john.doe@example.com',
    phone: '0123456789',
    address: 'Cairo, Egypt',
    date_of_birth: '1999-12-17',
    profileImage: '',
    medicalHistory: '',
    prescriptions: [
      { name: 'Medicine A', dosage: '2 times a day' },
      { name: 'Medicine B', dosage: 'Once before bed' }
    ],
    allergies: ['Penicillin', 'Pollen']
  };

  appointments: Appointment[] = [
    {
      date: "2023-10-05T10:30:00",
      doctorName: 'Dr. Smith',
      reason: 'Routine Checkup'
    },
    {
      date: "2023-11-15T14:00:00",
      doctorName: 'Dr. Johnson',
      reason: 'Follow-up Visit'
    }
  ];

  editPatient: Patient = { ...this.patient };

  getAge(dob: string): number {
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    return age;
  }

  getInitials(): string {
    return this.patient.name.split(' ').map(n => n[0]).join('');
  }

  openEditModal() {
    this.editPatient = { ...this.patient };
    this.showModal = true;
  }

  closeModal() {
    this.showModal = false;
  }

  saveChanges() {
    this.patient = { ...this.editPatient };
    this.closeModal();
  }
}