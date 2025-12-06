import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AiService } from '../services/ai.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-symptoms-checker',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './symptoms-checker.component.html'
})
export class SymptomsCheckerComponent {
  symptomsText = '';
  age?: number;
  gender?: string = '';
  loading = false;
  result: any = null;
  error: string | null = null;

  constructor(private ai: AiService) {}

  submit() {
    this.error = null;
    if (!this.symptomsText || this.symptomsText.trim().length < 3) {
      this.error = 'Please describe your symptoms briefly (at least 3 characters).';
      return;
    }

    this.loading = true;
    this.result = null;

    this.ai.analyzeSymptoms({ symptoms: this.symptomsText, age: this.age, gender: this.gender }).subscribe({
      next: res => {
        this.loading = false;
        if (res.status && res.data) {
          this.result = res.data;
        } else {
          this.error = 'No result from AI.';
        }
      },
      error: err => {
        this.loading = false;
        this.error = err?.error?.message || 'Request failed. Try again later.';
      }
    });
  }

  urgencyClass(urgency: string) {
    if (!urgency) return 'bg-gray-100 text-gray-700';
    if (urgency === 'emergency') return 'bg-red-100 text-red-700';
    if (urgency === 'urgent') return 'bg-yellow-100 text-yellow-700';
    return 'bg-green-100 text-green-700';
  }

  bookSpecialist(specialty: string) {
    alert(`Redirect to book with ${specialty} (implement real flow).`);
  }
}
