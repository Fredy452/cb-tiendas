<?php

namespace App\Filament\Resources\Stores\Pages;

use App\Filament\Resources\Stores\StoreResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        if ($user instanceof User && $user->hasRole('emprendedor')) {
            unset($data['user_id'], $data['is_featured']);
            $data['status'] = 'pending';
            $data['approval_date'] = null;
            $data['approval_user_id'] = null;
        } elseif (($data['status'] ?? null) === 'approved' && $this->record->status !== 'approved') {
            $data['approval_date'] = now();
            $data['approval_user_id'] = Auth::id();
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        $user = Auth::user();

        if ($user instanceof User && $user->hasRole('emprendedor')) {
            return [ViewAction::make()];
        }

        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
