import { Routes } from '@angular/router';

// global pages
import { Contact } from './features/contact/contact';
import { AboutUs } from './features/about-us/about-us';
import { Services } from './features/services/services';

export const routes: Routes = [
// { path: '', redirectTo: 'home', pathMatch: 'full' },

// public pages in layout
// {
//   path:'',component:AboutUs,children:[

//     {path:'about us',component:AboutUs},
//     {path:'services',component:Services}
//   ]
// },
{path:'about us',component:AboutUs},
{path:'contact',component:Contact},
{path:'services',component:Services}

];
