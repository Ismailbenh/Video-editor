<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'service_name',
        'tier_name',
        'price',
        'client_name',
        'client_email',
        'description',
        'deadline',
        'file_link',
        'status',
        'payment_status',
        'stripe_session_id',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'deadline' => 'date',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
