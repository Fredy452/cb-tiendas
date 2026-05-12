<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'email',
        'website',
        'logo_path',
        'img_path',
        'status',
        'is_featured',
        'latitude',
        'longitude',
        'approval_status',
        'approval_date',
        'approval_user_id',
    ];

    public function approvalUser()
    {
        return $this->belongsTo(User::class, 'approval_user_id');
    }

    public function getApprovalStatusBadgeColor(): string
    {
        return match ($this->approval_status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
