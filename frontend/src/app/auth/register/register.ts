import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';



@Component({
  selector: 'app-register',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './register.html',
  styleUrl: './register.css'
})
export class Register {

  form = {name: '', email: '', password: '', password_confirmation: ''};
  errorMessage = '';

  constructor(private router: Router, private auth: AuthService) { }

  loginWithGoogle() {
    window.location.href = 'http://127.0.0.1:8000/login/google';
  }

  onSubmit(){
    this.errorMessage = '';
    if (!this.form.name || !this.form.email || !this.form.password || !this.form.password_confirmation) {
      this.errorMessage = 'All fields are required.';
      return;
    }

    if (this.form.password !== this.form.password_confirmation) {
      this.errorMessage = 'Passwords do not match.';
      return;
    }

    this.auth.register(this.form).subscribe({
      next: () => {
        this.router.navigate(['/home']);
      },
      error: (err) => {
        this.errorMessage = this.auth.getErrorMessage(err);
      }
    });
  }

}
