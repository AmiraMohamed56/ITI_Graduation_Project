import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { Router, RouterModule } from '@angular/router';

@Component({
  selector: 'app-payment-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule],
  templateUrl: './payment-form.html',
  styleUrl: './payment-form.css',
})
export class PaymentFormComponent {
  paymentForm!: FormGroup;
  isCard = false;
  showModal = false;
  minExpiryDate!: string; // for input[min]

  constructor(private fb: FormBuilder, private router: Router) {}

  ngOnInit(): void {
    this.paymentForm = this.fb.group({
      invoice_id: ['123', Validators.required],
      appointment_id: ['10', Validators.required],
      patient_id: ['5', Validators.required],
      amount: ['200', Validators.required],
      method: ['cash', Validators.required],
      card_holder: [''],
      card_number: [''],
      expiry_date: [''],
      cvv: ['']
    });

    // Set minimum expiry date to current month
    const today = new Date();
    const month = (today.getMonth() + 1).toString().padStart(2, '0'); // months 01-12
    const year = today.getFullYear();
    this.minExpiryDate = `${year}-${month}`;
  }

  onMethodChange() {
    this.isCard = this.paymentForm.value.method === 'card';
    if (this.isCard) {
      this.paymentForm.get('card_holder')?.setValidators([Validators.required]);
      this.paymentForm.get('card_number')?.setValidators([Validators.required]);
      this.paymentForm.get('expiry_date')?.setValidators([Validators.required]);
      this.paymentForm.get('cvv')?.setValidators([Validators.required]);
    } else {
      this.paymentForm.get('card_holder')?.clearValidators();
      this.paymentForm.get('card_number')?.clearValidators();
      this.paymentForm.get('expiry_date')?.clearValidators();
      this.paymentForm.get('cvv')?.clearValidators();
    }
    this.paymentForm.get('card_holder')?.updateValueAndValidity();
    this.paymentForm.get('card_number')?.updateValueAndValidity();
    this.paymentForm.get('expiry_date')?.updateValueAndValidity();
    this.paymentForm.get('cvv')?.updateValueAndValidity();
  }

  confirmPayment() {
    if (this.paymentForm.valid) {
      // Here would typically send the payment data to the backend
      this.showModal = true;   
    } else {
      console.log("Form invalid:", this.paymentForm.value);
    }
  }

  closeModal() {
    this.showModal = false;
    this.router.navigate(['/patient-profile']); 
  }
}

