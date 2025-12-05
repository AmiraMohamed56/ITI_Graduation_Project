export interface Specialty {
  id: number;
  name: string;
  doctors_count: number;
  created_at?: string;
  updated_at?: string;
}

export interface SpecialtyResponse {
  success: boolean;
  data: Specialty[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
  };
  message: string;
}
