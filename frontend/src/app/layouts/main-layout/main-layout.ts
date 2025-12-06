import { Component } from '@angular/core';
// import { Navbar } from '../../ui/navbar/navbar';

import { Footer } from '../../ui/footer/footer';
import { RouterOutlet } from '@angular/router';
import { ToastComponent } from '../../toast/toast.component';
import { NavbarComponent } from '../../ui/navbar/navbar';
import { ChatButtonComponent } from '../../ui/chat-button/chat-button';
@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [RouterOutlet, ChatButtonComponent,NavbarComponent, Footer, ToastComponent],
  templateUrl: './main-layout.html',
  styleUrls: ['./main-layout.css'],
})
export class MainLayout {}
