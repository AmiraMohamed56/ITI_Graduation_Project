import { Component } from '@angular/core';
import { Navbar } from '../../ui/navbar/navbar';
import { Footer } from '../../ui/footer/footer';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [RouterOutlet ,Navbar, Footer],
  templateUrl: './main-layout.html',
  styleUrls: ['./main-layout.css'],
})
export class MainLayout {

}
