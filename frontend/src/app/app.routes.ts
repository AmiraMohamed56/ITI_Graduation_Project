import { Routes } from '@angular/router';

// Auth pages
import { Login } from './auth/login/login';
import { Register } from './auth/register/register';

// Authenticated pages
import { PatientProfile } from './patient-profile/patient-profile';
import { PaymentFormComponent } from './payments/payment-form/payment-form';

// Layouts
import { MainLayout } from './layouts/main-layout/main-layout';
import { AuthLayout } from './layouts/auth-layout/auth-layout';

// Public pages
import { Contact } from './features/contact/contact';
import { AboutUs } from './features/about-us/about-us';
import { Services } from './features/services/services';
import { LandingComponent } from './LandinPage/landing/landing.component';
import { DoctorProfileComponent } from './LandinPage/doctor-profile/doctor-profile.component';
// Landing Page Route
//     { path: 'Landing', component: LandingComponent },
// {
//   path: 'landing/doctors/:id',
//   loadComponent: () => import('./LandinPage/doctors-by-categor/doctors-by-category.component').then(m => m.DoctorsByCategoryComponent)
// },
// { path: 'doctor/:id', component: DoctorProfileComponent }
export const routes: Routes = [
  // Auth routes (login/register)
  {
    path: '',
    component: AuthLayout,
    children: [
      { path: 'login', component: Login },
      { path: 'register', component: Register },
      { path: '', redirectTo: 'login', pathMatch: 'full' },
      
    ],
  },

  // Public pages (accessible without login)
  {
    path: '',
    component: MainLayout,
    children: [
      { path: 'about-us', component: AboutUs },
      { path: 'contact', component: Contact },
      { path: 'services', component: Services },
      { path: 'Landing', component: LandingComponent },
      {path: 'landing/doctors/:id',
      loadComponent: () => import('./LandinPage/doctors-by-categor/doctors-by-category.component').then(m => m.DoctorsByCategoryComponent)
      },
      { path: 'doctor/:id', component: DoctorProfileComponent }
      
    ],
  },

  // Protected pages (after login)
  {
    path: '',
    component: MainLayout,
    children: [
      { path: 'patient-profile', component: PatientProfile },
      { path: 'payment-form', component: PaymentFormComponent },
      // add other authenticated pages here
    ],
  },

  // Fallback: redirect unknown routes to login
  { path: '**', redirectTo: 'login' },
];



