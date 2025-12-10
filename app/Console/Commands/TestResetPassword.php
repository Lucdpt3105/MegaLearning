<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class TestResetPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:reset-password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test gửi email đặt lại mật khẩu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Đang gửi email đặt lại mật khẩu tới: {$email}");

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->info('✅ Đã gửi email thành công!');
            $this->info('Vui lòng kiểm tra hộp thư của bạn.');
        } else {
            $this->error('❌ Gửi email thất bại!');
            $this->error('Lỗi: ' . $status);
        }

        return Command::SUCCESS;
    }
}
