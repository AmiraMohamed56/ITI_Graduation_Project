import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Category } from './category.model';
import { CategoryService } from './category.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-categories',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.css']
})
export class CategoriesComponent implements OnInit {

  @Input() categories: Category[] = [];
  @Output() selectCategory: EventEmitter<number> = new EventEmitter();

  loading: boolean = false;

  constructor(private categoryService: CategoryService , private router: Router) {}

  ngOnInit(): void {
    if (this.categories.length === 0) {
      this.loadCategories();
    }
  }

  loadCategories() {
    this.loading = true;
    this.categoryService.getCategories().subscribe({
      next: data => {
        this.categories = data;
        this.loading = false;
      },
      error: () => this.loading = false
    });
  }

  onCategoryClick(categoryId: number) {
      this.router.navigate(['/landing/doctors', categoryId]);

  }
}
