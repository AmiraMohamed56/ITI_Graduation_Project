import { Component } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';

@Component({
  selector: 'app-forgot-password',
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './forgot-password.html',
  styleUrl: './forgot-password.css',
})
export class ForgotPassword {

  form = { email: '' };
  errorMessage = '';
  successMessage = '';

  constructor(private auth: AuthService) { }

  onSubmit() {
    this.errorMessage = '';
    this.successMessage = '';

    if (!this.form.email) {
      this.errorMessage = 'Please enter your email.';
      return;
    }

    this.auth.forgotPassword(this.form).subscribe({
      next: (res) => {
        this.successMessage ='Password reset link has been sent to your email. Please check your inbox.';
        this.form.email = '';
      },
      error: (err) => {
        this.errorMessage = this.auth.getErrorMessage(err);
      }
    });
  }
}
