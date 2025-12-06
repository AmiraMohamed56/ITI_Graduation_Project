import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface AiAnalysisResponse {
  status: boolean;
  data: {
    analysis_id: number;
    raw: string;
    structured?: any;
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
