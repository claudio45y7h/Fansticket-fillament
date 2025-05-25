<?php

namespace App\Traits;

use App\Models\Action;

trait RecordsActivity
{
    protected static function bootRecordsActivity()
    {
        static::created(function ($model) {
            $modelName = class_basename($model);
            $description = "New {$modelName} was created with ID: {$model->id}";

            // Personalizar descripción según el modelo
            switch ($modelName) {
                case 'User':
                    $description = "New user registered - Email: {$model->email}";
                    break;
                
                case 'Order':
                    $description = "New order created - Customer Email: {$model->user->email}, Total: $" . number_format($model->total, 2);
                    break;

                case 'Event':
                    $description = "New event created - Name: {$model->name}";
                    break;

                case 'Ticket':
                    $description = "New ticket created for event: {$model->event->name}";
                    break;
            }

            Action::create([
                'action' => "{$modelName} Created",
                'description' => $description
            ]);
        });

        // Registro de actualizaciones
        static::updated(function ($model) {
            $modelName = class_basename($model);
            $description = "{$modelName} updated with ID: {$model->id}";

            // Personalizar descripción de actualizaciones según el modelo
            switch ($modelName) {
                case 'Order':
                    if ($model->isDirty('status')) {
                        $description = "Order status changed to: {$model->status} - Customer: {$model->user->email}";
                    }
                    break;

                case 'Event':
                    if ($model->isDirty('name')) {
                        $description = "Event name updated to: {$model->name}";
                    }
                    break;
            }

            Action::create([
                'action' => "{$modelName} Updated",
                'description' => $description
            ]);
        });
    }
}
