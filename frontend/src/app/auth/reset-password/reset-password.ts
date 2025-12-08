import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute, Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-reset-password',
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './reset-password.html',
  styleUrl: './reset-password.css',
})
export class ResetPassword {

  form = {
    email: '',
    token: '',
    password: '',
    password_confirmation: ''
  };
  errorMessage = '';
  successMessage = '';
  isLoading = false;

  constructor(private route: ActivatedRoute, private router: Router, private auth: AuthService) {
    this.route.queryParams.subscribe(params => {
      this.form.token = params['token'] || '';
      this.form.email = params['email'] || '';

      if(!this.form.token || !this.form.email){
        this.errorMessage = 'Invalid reset link. Please request a new password reset.';
      }
    });
  }

  onSubmit() {
    this.errorMessage = '';
    this.successMessage = '';

    if (!this.form.password || !this.form.password_confirmation) {
      this.errorMessage = 'All fields are required.';
      return;
    }
    if (this.form.password !== this.form.password_confirmation) {
      this.errorMessage = 'Passwords do not match.';
      return;
    }

    if(this.form.password.length < 8){
      this.errorMessage = 'Password must be at least 8 characters long.';
      return;
    }

    this.isLoading = true;

    this.auth.resetPassword(this.form).subscribe({
      next: (res) => {
        this.successMessage = res.message || 'Password reset successfully!';
        setTimeout(() => this.router.navigate(['/auth/login']), 2000);
      },
      error: (err) => {
        this.errorMessage = this.auth.getErrorMessage(err);
      }
    })
  }

}
