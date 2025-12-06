import { Routes } from '@angular/router';

// Auth pages
import { Login } from './auth/login/login';
import { Register } from './auth/register/register';

// Authenticated pages
import { PatientProfile } from './patient-profile/patient-profile';
import { PaymentComponent } from './payments/payment-form/payment-form';

// Layouts
import { MainLayout } from './layouts/main-layout/main-layout';
import { AuthLayout } from './layouts/auth-layout/auth-layout';

// Public pages
import { Contact } from './features/contact/contact';
import { AboutUs } from './features/about-us/about-us';
import { Services } from './features/services/services';
import { LandingComponent } from './LandinPage/landing/landing.component';
import { DoctorProfileComponent } from './LandinPage/doctor-profile/doctor-profile.component';
import { BookingComponent } from './booking/booking.component';
import { ForgotPassword } from './auth/forgot-password/forgot-password';
import { ResetPassword } from './auth/reset-password/reset-password';
import { AllDoctors } from './LandinPage/all-doctors/all-doctors';
import { DoctorsByCategoryComponent } from './LandinPage/doctors-by-categor/doctors-by-category.component';
import { AllSpecialtiesComponent } from './specialities/all-specialties';
import { SymptomsCheckerComponent } from './symptoms-checker/symptoms-checker.component';

export const routes: Routes = [
  // Auth routes (login/register)
  {
    path: 'auth',
    component: AuthLayout,
    children: [
      { path: 'login', component: Login },
      { path: 'register', component: Register },
      { path: 'forgot-password', component: ForgotPassword },
      { path: 'reset-password', component: ResetPassword },
      { path: '', redirectTo: 'login', pathMatch: 'full' },

    ],
  },

  // Public pages (accessible without login) - Default layout
  {
    path: '',
    component: MainLayout,
    children: [
      { path: '', component: LandingComponent },
      { path: 'about-us', component: AboutUs },
      { path: 'contact', component: Contact },
      { path: 'services', component: Services },
      {
        path: 'landing/doctors/:id',
        loadComponent: () => import('./LandinPage/doctors-by-categor/doctors-by-category.component').then(m => m.DoctorsByCategoryComponent)
      },
      { path: 'doctor/:id', component: DoctorProfileComponent },
      { path: 'patient-profile', component: PatientProfile },
      { path: 'payment-form', component: PaymentComponent },
      { path: 'book-appointment', component: BookingComponent },
      { path: 'doctors/all', component: AllDoctors },
      { path: 'doctors/category/:id', component: DoctorsByCategoryComponent },
      { path: 'specialties', component: AllSpecialtiesComponent },

      { path: 'ai', component: SymptomsCheckerComponent },
    ],
  },
  // Fallback: redirect unknown routes to home
  { path: '**', redirectTo: '' },
];





