<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        if ($user instanceof User && $user->hasRole('emprendedor')) {
            $data['user_id'] = Auth::id();
            $data['email'] = $data['email'] ?? $user->email;
            $data['status'] = 'pending';
            $data['is_featured'] = false;
            $data['approval_date'] = null;
            $data['approval_user_id'] = null;
        }

        return $data;
    }
}
