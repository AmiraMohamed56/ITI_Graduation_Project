import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { DoctorService } from '../doctors/doctor.service';
import { Doctor } from '../doctors/doctor.model';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { ReviewService, Review } from '../../services/review.service';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-doctor-profile',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './doctor-profile.component.html',
  styleUrls: ['./doctor-profile.component.css']
})
export class DoctorProfileComponent implements OnInit {

  doctor: Doctor | null = null;
  loading: boolean = false;
  error: string = '';

  // Review form
  showReviewModal: boolean = false;
  reviewForm = {
    rating: 5,
    comment: ''
  };
  submittingReview: boolean = false;
  reviewError: string = '';
  reviewSuccess: string = '';

  // Current user info
  currentUser: any = null;
  currentPatientId: number | null = null;

  constructor(
    private route: ActivatedRoute,
    private doctorService: DoctorService,
    private reviewService: ReviewService,
    private authService: AuthService,
    private router: Router
  ) {}

  ngOnInit(): void {
    // Get current user and patient ID
    this.currentUser = this.authService.getUser();

    if (this.currentUser && this.currentUser.patient_id) {
      this.currentPatientId = this.currentUser.patient_id;
    } else if (this.currentUser && this.currentUser.id) {
      this.currentPatientId = this.currentUser.id;
    }

    const doctorId = Number(this.route.snapshot.paramMap.get('id'));
    if (doctorId) this.loadDoctor(doctorId);
  }

  loadDoctor(id: number) {
    this.loading = true;
    this.doctorService.getDoctorById(id).subscribe({
      next: data => {
              console.log("Doctor Data:", data);
      console.log("Reviews:", data.reviews);  // 👈 هنا هتشوف شكل الريفيو

        this.doctor = data;
        this.loading = false;
      },
      error: () => {

        this.error = 'Error loading doctor profile.';
        this.loading = false;
      }
    });
  }

  // =========================
  // BOOK APPOINTMENT BUTTON
  // =========================
  bookAppointment() {
    if (!this.doctor) return;

    this.router.navigate(['/book-appointment'], {
      queryParams: { doctorId: this.doctor.id }
    });
  }

  // =========================
  // REVIEW MODAL HANDLING
  // =========================
  openReviewModal() {
    if (!this.currentUser) {
      this.router.navigate(['/auth/login']);
      return;
    }

    if (!this.currentPatientId) {
      this.error = 'Unable to submit review. Please complete your profile.';
      return;
    }

    this.showReviewModal = true;
    this.reviewError = '';
    this.reviewSuccess = '';
  }

  closeReviewModal() {
    this.showReviewModal = false;
    this.reviewForm = {
      rating: 5,
      comment: ''
    };
  }

  // =========================
  // SUBMIT REVIEW
  // =========================
  submitReview() {
    if (!this.doctor || !this.currentPatientId) return;

    if (this.reviewForm.rating < 1 || this.reviewForm.rating > 5) {
      this.reviewError = 'Rating must be between 1 and 5';
      return;
    }

    if (!this.reviewForm.comment.trim()) {
      this.reviewError = 'Please write a comment';
      return;
    }

    this.submittingReview = true;
    this.reviewError = '';

    const review: Review = {
      patient_id: this.currentPatientId,
      doctor_id: this.doctor.id!,
      rating: this.reviewForm.rating,
      comment: this.reviewForm.comment,
    };

    this.reviewService.createReview(review).subscribe({
      next: (response) => {
        this.submittingReview = false;
        this.reviewSuccess = 'Review submitted successfully!';

        setTimeout(() => {
          this.closeReviewModal();
          this.loadDoctor(this.doctor!.id!);
        }, 1500);
      },
      error: (err) => {
        this.submittingReview = false;
        this.reviewError = this.authService.getErrorMessage(err);
      }
    });
  }

  // =========================
  // SET RATING
  // =========================
  setRating(rating: number) {
    this.reviewForm.rating = rating;
  }
  getRatingText(rating: number): string {
  const texts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
  return texts[rating];
  }

}
