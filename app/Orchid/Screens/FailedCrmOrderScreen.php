<?php

namespace App\Orchid\Screens;

use App\Jobs\SendOrderToCrmJob;
use App\Models\FailedCrmOrder;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class FailedCrmOrderScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'failedOrders' => FailedCrmOrder::with('order')->latest()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Заявки в CRM';
    }

    public function description(): ?string
    {
        return 'Список заявок, которые не удалось отправить в CRM';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('failedOrders', [
                TD::make('order.id', 'ID заявки'),
                TD::make('order.phone', 'Телефон'),
                TD::make('order.vehicle_id', 'ID автомобиля'),
                TD::make('message', 'Ошибка'),
                TD::make('created_at', 'Дата ошибки'),

                TD::make('Действия')
                    ->alignRight()
                    ->render(function (FailedCrmOrder $failedOrder) {
                        return Button::make('Переотправить')
                            ->method('retrySendOrder', ['id' => $failedOrder->id])
                            ->icon('refresh');
                    }),
            ]),
        ];
    }

    public function retrySendOrder(int $id)
    {
        $failedOrder = FailedCrmOrder::findOrFail($id);
        $order = $failedOrder->order;

        // Повторная отправка заявки
        SendOrderToCrmJob::dispatch($order);

        // Удаление записи о неудачной отправке
        $failedOrder->delete();

        return redirect()->route('platform.failed.crm.orders')->with('success', 'Заявка отправлена в CRM');
    }
}
