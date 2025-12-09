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
private api = 'http://localhost:8000/api/ai/symptoms';

  constructor(private http: HttpClient) {}

analyzeSymptoms(payload: { symptoms: string, age?: number, gender?: string, file?: File }): Observable<AiAnalysisResponse> {
  const formData = new FormData();

  formData.append('symptoms', payload.symptoms);

  if (payload.age !== undefined) formData.append('age', payload.age.toString());
  if (payload.gender) formData.append('gender', payload.gender);
  if (payload.file) formData.append('file', payload.file);

  return this.http.post<AiAnalysisResponse>(this.api, formData);
}

}
