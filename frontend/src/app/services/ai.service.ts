import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface DoctorInfo {
  id: number;
  name: string;
  email: string;
  phone: string;
  specialty: string;
}

export interface Diagnosis {
  name: string;
  confidence_percent: number;
  reasoning: string;
  recommended_specialty: string;
  doctors: DoctorInfo[];
}

export interface StructuredData {
  diagnoses: Diagnosis[];
  next_steps: string[];
  urgency: string;
}

export interface AiAnalysisResponse {
  status: boolean;
  data: {
    analysis_id: number;
    raw: string;
    structured?: StructuredData;
  }
}

@Injectable({ providedIn: 'root' })
export class AiService {
  private api = '/api/ai/symptoms';

  constructor(private http: HttpClient) {}

  analyzeSymptoms(payload: {symptoms: string, age?: number, gender?: string}): Observable<AiAnalysisResponse> {
    return this.http.post<AiAnalysisResponse>(this.api, payload);
  }
}
