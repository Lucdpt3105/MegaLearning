<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

echo "\n🔍 Checking Email Configuration...\n";
echo str_repeat("=", 60) . "\n";

// Display current config
echo "📧 MAIL CONFIGURATION:\n";
echo "   Mailer: " . Config::get('mail.default') . "\n";
echo "   Host: " . Config::get('mail.mailers.smtp.host') . "\n";
echo "   Port: " . Config::get('mail.mailers.smtp.port') . "\n";
echo "   Username: " . Config::get('mail.mailers.smtp.username') . "\n";
echo "   Encryption: " . Config::get('mail.mailers.smtp.encryption') . "\n";
echo "   From Address: " . Config::get('mail.from.address') . "\n";
echo "   From Name: " . Config::get('mail.from.name') . "\n";
echo str_repeat("=", 60) . "\n\n";

// Ask for test email
echo "📨 Enter email address to send test email (or press Enter to skip): ";
$testEmail = trim(fgets(STDIN));

if (empty($testEmail)) {
    echo "\n✅ Configuration looks good! Test email skipped.\n";
    exit(0);
}

// Validate email
if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    echo "\n❌ Invalid email address!\n";
    exit(1);
}

echo "\n📤 Sending test email to: $testEmail\n";

try {
    Mail::raw('This is a test email from MegaLearning! 🎓

If you received this email, your SMTP configuration is working correctly.

System Information:
- Sent from: ' . Config::get('mail.from.address') . '
- Sent at: ' . now()->format('Y-m-d H:i:s') . '
- Application: ' . Config::get('app.name') . '

Regards,
MegaLearning Team', function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('✅ MegaLearning - Test Email');
    });

    echo "\n✅ SUCCESS! Test email sent successfully!\n";
    echo "📬 Check your inbox at: $testEmail\n";
    echo "💡 Don't forget to check SPAM folder if you don't see it.\n\n";
    
    exit(0);
} catch (\Exception $e) {
    echo "\n❌ ERROR sending email!\n";
    echo "Error message: " . $e->getMessage() . "\n\n";
    
    echo "🔧 TROUBLESHOOTING:\n";
    echo "1. Check your MAIL_USERNAME and MAIL_PASSWORD in .env\n";
    echo "2. Make sure 'Less secure app access' is enabled (for Gmail)\n";
    echo "3. Or use App Password instead of regular password\n";
    echo "4. Check if MAIL_ENCRYPTION=tls and MAIL_PORT=587\n\n";
    
    exit(1);
}
