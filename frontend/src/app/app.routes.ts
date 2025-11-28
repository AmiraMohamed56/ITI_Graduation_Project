import { Routes } from '@angular/router';
import { Login } from './auth/login/login';
import { Register } from './auth/register/register';
import { PatientProfile } from './patient-profile/patient-profile';
import { PaymentFormComponent } from './payments/payment-form/payment-form';
import { LandingComponent } from './LandinPage/landing/landing.component';
import { DoctorProfileComponent } from './LandinPage/doctor-profile/doctor-profile.component';

export const routes: Routes = [
    {path: '', redirectTo: 'login', pathMatch: 'full' },
    {path: 'login', component: Login },
    {path: 'register', component: Register },
    {path: 'patient-profile', component: PatientProfile },
    {path: 'payment-form', component:PaymentFormComponent},

  // Landing Page Route
    { path: 'Landing', component: LandingComponent },
{
  path: 'landing/doctors/:id',
  loadComponent: () => import('./LandinPage/doctors-by-categor/doctors-by-category.component').then(m => m.DoctorsByCategoryComponent)
},
{ path: 'doctor/:id', component: DoctorProfileComponent }



];
