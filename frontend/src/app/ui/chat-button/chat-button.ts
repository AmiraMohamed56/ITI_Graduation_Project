
import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-chat-button',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './chat-button.html',
  styleUrl: './chat-button.css',
})
export class ChatButtonComponent {

  constructor(private router: Router) {}

  goToChat() {
    this.router.navigate(['/ai']);
  }
}
