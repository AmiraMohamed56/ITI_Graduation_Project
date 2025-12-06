import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';

declare const gapi: any;

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterModule],
  templateUrl: './login.html',
  styleUrl: './login.css'
})
export class Login implements OnInit {

  form = {email: '', password: ''};
  errorMessage = '';

  constructor(private router: Router, private auth: AuthService) { }

  ngOnInit(): void {
    this.initGoogleLogin();
  }

  // Initialize Google login
  initGoogleLogin() {
    gapi.load('auth2', () => {
      const auth2 = gapi.auth2.init({
        client_id: '45277697582-ivg5vloq6dn4gcp0b73jr7l0uvq5s3nd.apps.googleusercontent.com', // Replace with your Google Client ID
      });

      // Attach the click handler to the Google login button
      auth2.attachClickHandler(
        document.getElementById('google-login-button'), // Attach to the button in the template
        {},
        (googleUser: any) => {
          const id_token = googleUser.getAuthResponse().id_token;
          this.auth.googleLogin(id_token).subscribe(
            (res) => {
              // On successful login, navigate to the home page or dashboard
              console.log('Google Login successful:', res);
              this.router.navigate(['/home']);
            },
            (error) => {
              // Handle error during Google login
              console.error('Google login error:', error);
              this.errorMessage = 'Google login failed. Please try again.';
            }
          );
        },
        (error: any) => {
          // Handle error if Google login fails
          console.error('Google login error:', error);
          this.errorMessage = 'Google login failed. Please try again.';
        }
      );
    });
  }

  onSubmit(){
    this.errorMessage = '';
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
