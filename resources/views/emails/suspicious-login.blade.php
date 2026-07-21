<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Suspicious Login — Insurio</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f8fafc; margin: 0; padding: 0; }
  .container { max-width: 540px; margin: 40px auto; }
  .card { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #d97706, #b45309); padding: 32px; }
  .header h1 { color: #fff; font-size: 20px; font-weight: 700; margin: 0 0 4px; }
  .header p { color: #fde68a; font-size: 14px; margin: 0; }
  .body { padding: 36px; }
  .greeting { color: #1e293b; font-size: 15px; font-weight: 600; margin-bottom: 12px; }
  .message { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 24px; }
  .detail-row { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
  .detail-label { font-size: 12px; font-weight: 600; color: #94a3b8; text-transform: uppercase; min-width: 80px; margin-top: 2px; }
  .detail-value { font-size: 13px; color: #334155; }
  .cta { margin-top: 28px; text-align: center; }
  .btn { display: inline-block; background: #b45309; color: #fff; padding: 12px 28px; border-radius: 10px; font-weight: 600; font-size: 14px; text-decoration: none; }
  .footer { border-top: 1px solid #f1f5f9; padding: 24px 36px; }
  .footer p { font-size: 12px; color: #94a3b8; margin: 0; line-height: 1.6; }
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <h1>⚠️ Suspicious Login Detected</h1>
      <p>Insurio Enterprise Security Alert</p>
    </div>
    <div class="body">
      <p class="greeting">Hello, {{ $user->name }}</p>
      <p class="message">We detected a login to your Insurio account from an unusual location. This might be you using a VPN or accessing from a new network.</p>

      <div class="detail-row">
        <span class="detail-label">Time</span>
        <span class="detail-value">{{ $time }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">IP</span>
        <span class="detail-value">{{ $ip }}</span>
      </div>

      <div class="cta">
        <a href="{{ route('admin.security') }}" class="btn">Review Security Center</a>
      </div>
    </div>
    <div class="footer">
      <p>If this was you, you can ignore this email. If you don't recognize this login, click "Review Security Center" to revoke sessions immediately.</p>
    </div>
  </div>
</div>
</body>
</html>
