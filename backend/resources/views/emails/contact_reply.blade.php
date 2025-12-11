<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e0e0e0; border-radius: 10px;">
    <h2 style="color: #1e40af;">Hello {{ $contact->name }},</h2>

    <p>Thank you for your message:</p>
    <blockquote style="background:#f9f9f9; padding:15px; border-left:4px solid #3b82f6; margin:20px 0;">
        {{ $contact->message }}
    </blockquote>

    <p><strong>Our reply:</strong></p>
    <div style="background:#eff6ff; padding:20px; border-radius:8px; color:#1e40af;">
        {!! nl2br(e($replyMessage)) !!}
    </div>

    <p style="margin-top:30px;">Best regards,<br><strong>CareNova Team</strong></p>
</div>