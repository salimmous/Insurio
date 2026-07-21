<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Account Locked — Insurio</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; margin: 0; padding: 0; }
  .container { max-width: 540px; margin: 40px auto; }
  .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #dc2626, #b91c1c); padding: 32px; }
  .header h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 0 0 4px; }
  .header p { color: #fca5a5; font-size: 14px; margin: 0; }
  .body { padding: 36px; }
  .greeting { color: #1e293b; font-size: 15px; font-weight: 600; margin-bottom: 12px; }
  .message { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 24px; }
  .alert-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
  .alert-box p { font-size: 14px; color: #991b1b; margin: 0; }
  .steps { color: #64748b; font-size: 14px; line-height: 1.8; }
  .footer { border-top: 1px solid #f1f5f9; padding: 24px 36px; }
  .footer p { font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.6; }
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <h1>🔒 Account Locked</h1>
      <p>Insurio Enterprise Security Alert</p>
    </div>
    <div class="body">
      <p class="greeting">Hello, {{ $user->name }}</p>
      <p class="message">Your Insurio account has been temporarily locked due to multiple consecutive failed login attempts. This is a security measure to protect your account.</p>

      <div class="alert-box">
        <p>Your account will automatically unlock in <strong>30 minutes</strong>. You may then log in again with your correct credentials.</p>
      </div>

      <p class="steps">
        If you forgot your password, you can reset it by clicking <strong>"Forgot Password"</strong> on the login page.<br><br>
        If you did not attempt to log in, your email may be exposed. Consider contacting your agency administrator immediately.
      </p>
    </div>
    <div class="footer">
      <p>This is an automated security message from Insurio Enterprise. Do not reply to this email.</p>
    </div>
  </div>
</div>
</body>
</html>
