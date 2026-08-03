<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #1c1c1f;">
    <h2>New message from your website contact form</h2>
    <p><strong>Name:</strong> {{ $senderName }}</p>
    <p><strong>Email:</strong> {{ $senderEmail }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $messageBody }}</p>
</body>
</html>