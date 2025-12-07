import { Component, inject } from '@angular/core';

import { Router } from '@angular/router';

@Component({
  selector: 'app-google-callback-component',
  standalone: true,
  imports: [],
  templateUrl: './google-callback-component.html',
  styleUrl: './google-callback-component.css',
})
export class GoogleCallbackComponent {
  router = inject(Router);

  ngOnInit() {
    const params = new URLSearchParams(window.location.search);

    const token = params.get('token');
    const name = params.get('name');
    const email = params.get('email');
    const id = params.get('id');
    const google_id = params.get('google_id');

    if (token) {
      const user = {
        id,
        name,
        email,
        google_id
      };
      localStorage.setItem('auth_token', token);
      localStorage.setItem('user', JSON.stringify(user));


      this.router.navigate(['/book-appointment']);
    } else {
      this.router.navigate(['/login']);
    }
  }
}
