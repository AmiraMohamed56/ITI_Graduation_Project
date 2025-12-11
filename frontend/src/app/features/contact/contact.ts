import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule, NgForm } from '@angular/forms';
import { ContactService, ContactFormData } from './contact.service.ts';

@Component({
  selector: 'app-contact',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './contact.html',
  styleUrl: './contact.css'
})
export class Contact {
  formData: ContactFormData = {
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
  };

  isSubmitting = false;
  showSuccess = false;
  showError = false;
  errorMessage = '';
  successMessage = '';

  constructor(private contactService: ContactService) {}

  onSubmit(form: NgForm) {
    if (form.invalid) {
      // Mark all fields as touched to show validation errors
      Object.keys(form.controls).forEach(key => {
        form.controls[key].markAsTouched();
      });
      return;
    }

    this.isSubmitting = true;
    this.showSuccess = false;
    this.showError = false;

    this.contactService.submitContact(this.formData).subscribe({
      next: (response) => {
        this.isSubmitting = false;
        this.showSuccess = true;
        this.successMessage = response.message || 'Thank you! Your message has been sent successfully.';
        
        // Reset form
        form.resetForm();
        this.formData = {
          name: '',
          email: '',
          phone: '',
          subject: '',
          message: ''
        };

        // Hide success message after 5 seconds
        setTimeout(() => {
          this.showSuccess = false;
        }, 5000);
      },
      error: (error) => {
        this.isSubmitting = false;
        this.showError = true;
        this.errorMessage = error.message;

        // Hide error message after 5 seconds
        setTimeout(() => {
          this.showError = false;
        }, 5000);
      }
    });
  }

  closeAlert(type: 'success' | 'error') {
    if (type === 'success') {
      this.showSuccess = false;
    } else {
      this.showError = false;
    }
  }
}
