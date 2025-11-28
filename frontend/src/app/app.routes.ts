import { Routes } from '@angular/router';
import { Login } from './auth/login/login';
import { Register } from './auth/register/register';
import { PatientProfile } from './patient-profile/patient-profile';
import { PaymentFormComponent } from './payments/payment-form/payment-form';

export const routes: Routes = [
    {path: '', redirectTo: 'login', pathMatch: 'full' },
    {path: 'login', component: Login },
    {path: 'register', component: Register },
    {path: 'patient-profile', component: PatientProfile },
    {path: 'payment-form', component:PaymentFormComponent}
];
