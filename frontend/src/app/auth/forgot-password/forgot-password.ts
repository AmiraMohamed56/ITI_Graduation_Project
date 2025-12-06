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

  constructor(private router: Router, private auth: AuthService) { }

  onSubmit() {
    if (!this.form.email) {
      this.errorMessage = 'Please enter your email.';
      return;
    }

    this.auth.forgotPassword(this.form).subscribe({
      next: (res) => {
        alert(res.message);
      },
      error: (err) => {
        this.errorMessage = this.auth.getErrorMessage(err);
      }
    });
  }
}
