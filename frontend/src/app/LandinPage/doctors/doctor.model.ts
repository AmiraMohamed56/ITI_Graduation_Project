export interface Doctor {
  id: number;
  user_id: number;
  specialty_id: number;
  bio: string;
  education: string;
  years_experience: number;
  gender: 'male' | 'female';
  consultation_fee: number;
  rating: number;
  available_for_online?: boolean | null;
  created_at: string;
  updated_at: string;
  deleted_at?: string | null;
}
