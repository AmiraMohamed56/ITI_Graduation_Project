import { CommonModule } from '@angular/common';
import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { RouterModule } from '@angular/router';
import { Router } from '@angular/router';

@Component({
  selector: 'app-payment-form',
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './payment-form.html',
  styleUrls: ['./payment-form.css'],
})
export class PaymentComponent implements OnInit {
  paymentForm!: FormGroup;
  isCard = false;
  showModal = false;
  minExpiryDate: string;
  currentDate = new Date();

  constructor(private fb: FormBuilder, private router: Router) {
    // Set minimum expiry date to current month
    const today = new Date();
    this.minExpiryDate = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}`;
  }

  ngOnInit(): void {
    this.paymentForm = this.fb.group({
      invoice_id: ['INV-2024-001234', Validators.required],
      amount: ['150.00', Validators.required],
      method: ['cash', Validators.required],
      card_holder: [''],
      card_number: [''],
      expiry_date: [''],
      cvv: ['']
    });
  }

  onMethodChange(): void {
    const method = this.paymentForm.get('method')?.value;
    this.isCard = method === 'card';

    // Update validators based on payment method
    if (this.isCard) {
      this.paymentForm.get('card_holder')?.setValidators([Validators.required]);
      this.paymentForm.get('card_number')?.setValidators([Validators.required, Validators.minLength(16)]);
      this.paymentForm.get('expiry_date')?.setValidators([Validators.required]);
      this.paymentForm.get('cvv')?.setValidators([Validators.required, Validators.minLength(3)]);
    } else {
      this.paymentForm.get('card_holder')?.clearValidators();
      this.paymentForm.get('card_number')?.clearValidators();
      this.paymentForm.get('expiry_date')?.clearValidators();
      this.paymentForm.get('cvv')?.clearValidators();
    }

    // Update validation status
    this.paymentForm.get('card_holder')?.updateValueAndValidity();
    this.paymentForm.get('card_number')?.updateValueAndValidity();
    this.paymentForm.get('expiry_date')?.updateValueAndValidity();
    this.paymentForm.get('cvv')?.updateValueAndValidity();
  }

  confirmPayment(): void {
    if (this.paymentForm.valid) {
      this.showModal = true;
      console.log('Payment Data:', this.paymentForm.value);
    } else {
      console.log('Form is invalid');
      // Mark all fields as touched to show validation errors
      Object.keys(this.paymentForm.controls).forEach(key => {
        this.paymentForm.get(key)?.markAsTouched();
      });
    }
  }

  closeModal(): void {
    this.showModal = false;
    this.router.navigate(['/']);
    // Optionally reset form or navigate away
    // this.paymentForm.reset();
  }
}