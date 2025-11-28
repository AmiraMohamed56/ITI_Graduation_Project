import { Component, OnInit } from '@angular/core';
import { CategoryService } from './category.service';
import { Specialty } from './category.model';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-categories',
  templateUrl: './categories.component.html',
  styleUrls: ['./categories.component.css'],
  imports: [CommonModule, FormsModule],
})
export class CategoriesComponent implements OnInit {

  specialties: Specialty[] = [];
  newSpecialtyName: string = '';
  editSpecialty: Specialty | null = null;
  loading: boolean = false;
  error: string = '';

  constructor(private categoryService: CategoryService) { }

  ngOnInit(): void {
    this.loadSpecialties();
  }

  loadSpecialties() {
    this.loading = true;
    this.categoryService.getSpecialties().subscribe({
      next: data => {
        this.specialties = data;
        this.loading = false;
      },
      error: () => {
        this.error = 'Error fetching specialties.';
        this.loading = false;
      }
    });
  }

  addSpecialty() {
    if (!this.newSpecialtyName.trim()) return;
    const newSpecialty: Specialty = {
      id: 0,
      name: this.newSpecialtyName,
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString()
    };
    this.categoryService.addSpecialty(newSpecialty).subscribe({
      next: data => {
        this.specialties.push(data);
        this.newSpecialtyName = '';
      },
      error: () => this.error = 'Error adding specialty.'
    });
  }

  startEdit(s: Specialty) {
    this.editSpecialty = { ...s };
  }

  saveEdit() {
    if (!this.editSpecialty) return;
    this.editSpecialty.updated_at = new Date().toISOString();
    this.categoryService.updateSpecialty(this.editSpecialty).subscribe({
      next: data => {
        const index = this.specialties.findIndex(s => s.id === data.id);
        if (index !== -1) this.specialties[index] = data;
        this.editSpecialty = null;
      },
      error: () => this.error = 'Error updating specialty.'
    });
  }

  cancelEdit() {
    this.editSpecialty = null;
  }

  deleteSpecialty(id: number) {
    if (!confirm('Are you sure you want to delete this specialty?')) return;
    this.categoryService.deleteSpecialty(id).subscribe({
      next: () => this.specialties = this.specialties.filter(s => s.id !== id),
      error: () => this.error = 'Error deleting specialty.'
    });
  }
}
