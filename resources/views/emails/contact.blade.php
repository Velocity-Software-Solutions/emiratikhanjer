<!doctype html>
<html lang="en">
  <body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#111;">
    <h2>New Contact Message</h2>
    <p><strong>Name:</strong> {{ e($d['name']) }}</p>
    <p><strong>Email:</strong> {{ e($d['email']) }}</p>
    <p><strong>Message:</strong></p>
    <pre style="white-space:pre-wrap">{{ e($d['message']) }}</pre>
  </body>
</html>
