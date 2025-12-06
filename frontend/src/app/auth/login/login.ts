import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';


@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './login.html',
  styleUrl: './login.css'
})
export class Login {

  form = {email: '', password: ''};
  errorMessage = '';

  constructor(private router: Router, private auth: AuthService) { }

  onSubmit(){
    if (!this.form.email || !this.form.password) {
      this.errorMessage = 'Please enter your email and password.';
      return;
    }

    this.auth.login(this.form).subscribe({
      next: () => {
        this.router.navigate(['/home']);
      },
      error: (err) => {
        this.errorMessage = this.auth.getErrorMessage(err);
      }
    });
  }


}
