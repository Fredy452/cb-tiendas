<?php

namespace App\Observers;

use App\Mail\StoreCreated;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();

        if ($user instanceof User
            && $user->hasRole('emprendedor')
            && $store->user_id === $user->getKey()) {
            $this->sendStatusEmail($store, changesSubmitted: true, recipient: $user->email);

            return;
        }

        if ($store->wasChanged('status')) {
            $this->sendStatusEmail($store);
        }
    }

    private function sendStatusEmail(Store $store, bool $changesSubmitted = false, ?string $recipient = null): void
    {
        $recipient ??= $store->email;

        if (blank($recipient)) {
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
                'recipient' => $recipient,
            ]);

            Mail::to($recipient)->send(new StoreCreated($store, $store->status, changesSubmitted: $changesSubmitted));

            Log::info('Correo de estado de tienda enviado', [
                'store_id' => $store->getKey(),
                'store_status' => $store->status,
                'recipient' => $recipient,
            ]);
        } catch (\Throwable $exception) {
            Log::error('No se pudo enviar el correo de estado de tienda', [
                'store_id' => $store->getKey(),
                'store_status' => $store->status,
                'recipient' => $recipient,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
