<?php

namespace App\Jobs;

use App\Mail\FailSentOrderToCrmMail;
use App\Mail\NewOrderMail;
use App\Models\Order;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class SendOrderToCrmJob implements ShouldQueue
{
    use Queueable;

    private const MAX_TIMEOUT_MINUTES_VALUE = 5;
    private const HTTP_TIMEOUT_SECONDS_VALUE = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendToCrm($this->order);
    }

    private function sendToCrm(Order $order): void
    {
        $data = [
            'phone' => $order->phone,
            'VIN' => $order->vehicle->vin,
        ];

        // Попытки отправки в течение 5 минут
        $start = now();
        while (now()->diffInMinutes($start) < self::MAX_TIMEOUT_MINUTES_VALUE) {
            try {
                Http::withOptions(['timeout' => self::HTTP_TIMEOUT_SECONDS_VALUE]);
                $response = Http::post(config('tapir.crm_url'), [
                    'json' => $data,
                ]);

                if ($response->getStatusCode() === Response::HTTP_OK) {
                    $order->update(['is_sent' => true]);
                    Mail::to(config('mail.from.address'))->send(new NewOrderMail($order));
                    return; // Успешная отправка
                }
            } catch (RequestException $e) {
                // Логирование ошибки
                Log::error('Ошибка отправки в CRM: ' . $e->getMessage());
            }
        }

        // Если не удалось отправить за 5 минут, отправляем уведомление администратору
        Mail::to(config('tapir.failure_order_email'))->send(new FailSentOrderToCrmMail($order));

        Log::error('Не удалось отправить заявку в CRM после 5 минут попыток.');
    }
}
