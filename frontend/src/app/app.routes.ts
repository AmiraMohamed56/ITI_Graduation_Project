import { Routes } from '@angular/router';
import { Login } from './auth/login/login';
import { Register } from './auth/register/register';
import { PatientProfile } from './patient-profile/patient-profile';
import { PaymentFormComponent } from './payments/payment-form/payment-form';
import { MainLayout } from './layouts/main-layout/main-layout';
import { AuthLayout } from './layouts/auth-layout/auth-layout';

// export const routes: Routes = [
//     {path: '', redirectTo: 'login', pathMatch: 'full' },
//     {path: 'login', component: Login },
//     {path: 'register', component: Register },
//     {path: 'patient-profile', component: PatientProfile },
//     {path: 'payment-form', component:PaymentFormComponent}
// ];

export const routes: Routes = [
  {
    path: '',
    component: AuthLayout,
    children: [
      { path: 'login', component: Login },
      { path: 'register', component: Register },
      { path: '', redirectTo: 'login', pathMatch: 'full' }
    ]
  },
  {
    path: '',
    component: MainLayout,
    children: [
      { path: 'patient-profile', component: PatientProfile },
      { path: 'payment-form', component: PaymentFormComponent },
      // add other "app" pages here
    ]
  }

// global pages
import { Contact } from './features/contact/contact';
import { AboutUs } from './features/about-us/about-us';
import { Services } from './features/services/services';

export const routes: Routes = [
  { path: '', redirectTo: 'home', pathMatch: 'full' },

  // public pages in layout
  {
    path: '',
    component: Layout,
    children: [
      { path: 'about us', component: AboutUs },
      { path: 'contact', component: Contact },
      { path: 'services', component: Services },
    ],
  },
];
