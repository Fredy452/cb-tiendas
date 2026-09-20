<?php

namespace App\Observers;

use App\Mail\StoreCreated;
use App\Models\Store;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StoreObserver
{
    public function created(Store $store): void
    {
        $this->sendStatusEmail($store);
    }

    public function updated(Store $store): void
    {
        if ($store->wasChanged('status')) {
            $this->sendStatusEmail($store);
        }
    }

    private function sendStatusEmail(Store $store): void
    {
        if (blank($store->email)) {
            Log::warning('Correo de estado omitido: la tienda no tiene email', [
                'store_id' => $store->getKey(),
                'store_status' => $store->status,
            ]);

            return;
        }

        try {
            Log::info('Enviando correo de estado de tienda', [
                'store_id' => $store->getKey(),
                'store_status' => $store->status,
                'recipient' => $store->email,
            ]);

            Mail::to($store->email)->send(new StoreCreated($store, $store->status));

            Log::info('Correo de estado de tienda enviado', [
                'store_id' => $store->getKey(),
                'store_status' => $store->status,
                'recipient' => $store->email,
            ]);
        } catch (\Throwable $exception) {
            Log::error('No se pudo enviar el correo de estado de tienda', [
                'store_id' => $store->getKey(),
                'store_status' => $store->status,
                'recipient' => $store->email,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
