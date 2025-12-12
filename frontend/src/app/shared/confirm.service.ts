// Simple DOM-based confirm dialog service
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class ConfirmService {
  confirm(title: string, message: string, confirmText = 'Yes', cancelText = 'Cancel'): Promise<boolean> {
    return new Promise(resolve => {
      const overlay = document.createElement('div');
      overlay.className = 'confirm-overlay';
      overlay.innerHTML = `
        <div class="confirm-modal">
          <div class="confirm-header">${title}</div>
          <div class="confirm-body">${message}</div>
          <div class="confirm-actions">
            <button class="confirm-btn confirm-cancel">${cancelText}</button>
            <button class="confirm-btn confirm-ok">${confirmText}</button>
          </div>
        </div>
      `;

      const style = document.createElement('style');
      style.textContent = `
        .confirm-overlay{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);z-index:9999}
        .confirm-modal{width:380px;max-width:90%;background:#0f1724;color:#fff;border-radius:12px;overflow:hidden;box-shadow:0 10px 30px rgba(2,6,23,0.7);font-family:Inter,Arial,sans-serif}
        .confirm-header{padding:16px 18px;background:linear-gradient(90deg,#4f46e5,#06b6d4);font-weight:600}
        .confirm-body{padding:18px;color:#d1d5db}
        .confirm-actions{display:flex;gap:10px;justify-content:flex-end;padding:14px;background:rgba(255,255,255,0.02)}
        .confirm-btn{min-width:84px;padding:8px 12px;border-radius:8px;border:0;cursor:pointer;font-weight:600}
        .confirm-cancel{background:transparent;color:#9ca3af}
        .confirm-ok{background:#ef4444;color:#fff}
        @media (prefers-color-scheme: light){
          .confirm-modal{background:#fff;color:#111}
          .confirm-body{color:#374151}
          .confirm-cancel{color:#374151}
        }
      `;

      document.head.appendChild(style);
      document.body.appendChild(overlay);

      const okBtn = overlay.querySelector('.confirm-ok') as HTMLButtonElement;
      const cancelBtn = overlay.querySelector('.confirm-cancel') as HTMLButtonElement;

      const clean = (result: boolean) => {
        okBtn.removeEventListener('click', onOk);
        cancelBtn.removeEventListener('click', onCancel);
        document.body.removeChild(overlay);
        document.head.removeChild(style);
        resolve(result);
      };

      const onOk = () => clean(true);
      const onCancel = () => clean(false);

      okBtn.addEventListener('click', onOk);
      cancelBtn.addEventListener('click', onCancel);

      // allow click outside to cancel
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) clean(false);
      });
    });
  }
}
