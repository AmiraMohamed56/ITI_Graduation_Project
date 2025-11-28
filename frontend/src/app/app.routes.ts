import { Routes } from '@angular/router';
import { CategoriesComponent } from './categories/categories.component';
// import { DoctorsComponent } from './admin/doctors/doctors.component';
import { DoctorsListComponent } from './admin/doctors/pages/list/doctors-list.component';
import { AddDoctorComponent } from './admin/doctors/pages/add/add-doctor.component';
import { EditDoctorComponent } from './admin/doctors/pages/edit/edit-doctor.component';
import { DoctorDetailsComponent } from './admin/doctors/pages/details/doctor-details.component';


export const routes: Routes = [
  { path: '', component: DoctorsListComponent },
  { path: 'add', component: AddDoctorComponent },
  { path: 'edit/:id', component: EditDoctorComponent },
  { path: 'details/:id', component: DoctorDetailsComponent },
  { path: 'categories', component: CategoriesComponent }

];
