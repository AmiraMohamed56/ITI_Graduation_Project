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

  form = { email: '', token: '', password: '', password_confirmation: '' };
  errorMessage = '';

  constructor(private route: ActivatedRoute, private router: Router, private auth: AuthService) {
    this.route.queryParams.subscribe(params => {
      this.form.email = params['email'] || '';
      this.form.token = params['token'] || '';
    });
  }

  onSubmit() {
    if (!this.form.password || !this.form.password_confirmation) {
      this.errorMessage = 'All fields are required.';
      return;
    }
    if (this.form.password !== this.form.password_confirmation) {
      this.errorMessage = 'Passwords do not match.';
      return;
    }

    this.auth.resetPassword(this.form).subscribe({
      next: () => {
        this.router.navigate(['/auth/login']);
      },
      error: (err) => {
        this.errorMessage = this.auth.getErrorMessage(err);
      }
    })
  }

}
