<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_pdf')
                ->label('Descargar PDF')
                ->url(fn ($record) => route('orders.download-pdf', $record))
                ->icon('heroicon-o-document-arrow-down')
                ->visible(fn ($record) => $record->status === 'completed')
                ->openUrlInNewTab(),

            Actions\Action::make('send_confirmation')
                ->label('Enviar Confirmación')
                ->action(function () {
                    $order = $this->getRecord();

                    if ($order->status !== 'completed') {
                        \Filament\Notifications\Notification::make()
                            ->warning()
                            ->title('No se puede enviar la confirmación')
                            ->body('La orden debe estar completada para enviar la confirmación.')
                            ->send();
                        return;
                    }

                    try {
                        app(\App\Http\Controllers\OrderController::class)
                            ->sendOrderConfirmation($order, $order->user->email);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Confirmación enviada')
                            ->body('Se ha enviado la confirmación por correo electrónico.')
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Error')
                            ->body('No se pudo enviar la confirmación: ' . $e->getMessage())
                            ->send();
                    }
                })
                ->icon('heroicon-o-envelope')
                ->visible(fn ($record) => $record->status === 'completed')
                ->requiresConfirmation(),

            Actions\DeleteAction::make(),
        ];
    }
}
