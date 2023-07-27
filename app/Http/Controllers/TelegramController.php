<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Services\UserService;

class TelegramController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function subscribeTG(Request $request)
    {
        // Отримуємо відповідь від Телеграм
        $updates = Telegram::getWebhookUpdates();

        // Перевіряємо, що це команда /start, записуємо user_id в telegram_id
        if ($updates->getMessage() && $updates->getMessage()->getText() === '/start') {
            $userId = $updates->getMessage()->getChat()->getId();

            // Отримуємо аутентифікованого користувача
            $user = auth()->user();

            // Оновлюємо поле telegram_id користувача
            $this->userService->update($user->id,['telegram_id' => $userId]);
        }

        // Перевіряємо, що це команда /stop, записуємо null в telegram_id
        if ($updates->getMessage() && $updates->getMessage()->getText() === '/stop') {
            $userId = $updates->getMessage()->getChat()->getId();

            // Отримуємо аутентифікованого користувача
            $user = auth()->user();

            // Оновлюємо поле telegram_id користувача
            $this->userService->update($user->id,['telegram_id' => null] );
        }
    }
}
