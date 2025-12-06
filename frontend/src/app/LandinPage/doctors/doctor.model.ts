export interface Doctor {
  id: number;
  user_id: number;
  specialty_id: number;
  bio: string;
  education: string;
  years_experience: number;
  gender: string;
  consultation_fee: number;
  rating: number;
  available_for_online: boolean;

  user: {
    id: number;
    name: string;
    email: string;
    phone: string;
    profile_pic: string | null;
    profile_picture_url: string | null;
    status: number;
  };

  specialty: {
    id: number;
    name: string;
  } | null;

  schedules: {
    id: number;
    day: string;
    start_time: string;
    end_time: string;
  }[];

  rating_avg: number;
  reviews_count: number;

  reviews: {
    id: number;
    rating: number;
    comment: string;
    patient_name: string;
    created_at: string;
  }[];
}
