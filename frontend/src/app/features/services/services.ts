import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-services',
  imports: [],
  templateUrl: './services.html',
  styleUrl: './services.css'
})
export class Services {

  constructor(private router: Router) {}

  goToBooking() {
    this.router.navigate(['/book-appointment']);
  }


}
