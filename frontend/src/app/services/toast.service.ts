import { Injectable } from "@angular/core";
import { BehaviorSubject } from "rxjs";

export interface Toast {
  message: string;
  type: 'success' | 'error' | 'info';
}

@Injectable ({
  providedIn: 'root',
})

export class ToastService {
  private _toasts = new BehaviorSubject<Toast[]>([]);
  toasts$ = this._toasts.asObservable();

  show(message: string, type: 'success' | 'error' | 'info' = 'info') {
    const current = this._toasts.value;
    this._toasts.next([...current, { message, type }]);
    setTimeout(() => this.removeToast(), 5000); // auto remove after 3s
  }

  removeToast() {
    const current = this._toasts.value;
    current.shift();
    this._toasts.next([...current]);
  }

}
