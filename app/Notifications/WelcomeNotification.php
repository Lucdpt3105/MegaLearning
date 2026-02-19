<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    protected $loginMethod;

    /**
     * @param string $loginMethod 'google' or 'email'
     */
    public function __construct(string $loginMethod = 'email')
    {
        $this->loginMethod = $loginMethod;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('🎉 Chào mừng bạn đến MegaLearning!')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Cảm ơn bạn đã đăng ký tài khoản MegaLearning.');

        if ($this->loginMethod === 'google') {
            $mail->line('Bạn đã đăng ký thành công bằng tài khoản Google.');
        } else {
            $mail->line('Bạn đã đăng ký thành công bằng email.');
        }

        $mail->line('Với MegaLearning, bạn có thể:')
            ->line('📚 Tham gia các khóa học trực tuyến')
            ->line('📝 Làm bài kiểm tra và xem kết quả')
            ->line('💬 Trao đổi với giáo viên và bạn học')
            ->action('Bắt đầu học ngay', url('/student/dashboard'))
            ->line('Chúc bạn có trải nghiệm học tập tuyệt vời!')
            ->salutation('Trân trọng, Đội ngũ MegaLearning');

        return $mail;
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
