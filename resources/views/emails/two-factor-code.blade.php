<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Verification Code — Insurio</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; margin: 0; padding: 0; }
  .container { max-width: 540px; margin: 40px auto; }
  .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #2563eb, #4f46e5); padding: 32px; text-align: center; }
  .header-icon { width: 56px; height: 56px; background: rgba(255,255,255,0.2); border-radius: 14px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; }
  .header h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 0; }
  .header p { color: #bfdbfe; font-size: 14px; margin: 4px 0 0; }
  .body { padding: 40px 36px; }
  .greeting { color: #1e293b; font-size: 16px; font-weight: 600; margin-bottom: 8px; }
  .message { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 28px; }
  .code-box { background: #f1f5f9; border-radius: 12px; padding: 24px; text-align: center; margin-bottom: 28px; border: 2px dashed #cbd5e1; }
  .code { font-size: 40px; font-weight: 800; letter-spacing: 0.2em; color: #1e293b; font-family: 'Courier New', monospace; }
  .expires { font-size: 12px; color: #94a3b8; margin-top: 8px; }
  .warning { background: #fef9c3; border: 1px solid #fde047; border-radius: 10px; padding: 14px 16px; font-size: 13px; color: #713f12; margin-bottom: 24px; }
  .footer { border-top: 1px solid #f1f5f9; padding: 24px 36px; }
  .footer p { font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.6; }
  .brand { text-align: center; margin-bottom: 20px; color: #64748b; font-size: 13px; font-weight: 600; }
</style>
</head>
<body>
<div class="container">
  <div class="brand">🛡️ Insurio Enterprise Security</div>
  <div class="card">
    <div class="header">
      <div class="header-icon">🔑</div>
      <h1>Verification Code</h1>
      <p>Two-Factor Authentication</p>
    </div>
    <div class="body">
      <p class="greeting">Hello, {{ $user->name }}</p>
      <p class="message">You requested a two-factor authentication code to sign in to your Insurio account. Enter the code below on the verification screen.</p>

      <div class="code-box">
        <div class="code">{{ $code }}</div>
        <div class="expires">⏱ Expires at {{ $expiresAt }} (10 minutes)</div>
      </div>

      <div class="warning">
        ⚠️ If you didn't request this code, your account credentials may be compromised. <a href="{{ route('admin.security') }}" style="color:#92400e;">Visit Security Center</a> immediately.
      </div>
    </div>
    <div class="footer">
      <p>This is an automated security message from Insurio Enterprise. Do not share this code with anyone — Insurio will never ask for your verification code.</p>
    </div>
  </div>
</div>
</body>
</html>
