<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'status',
    ];

    /**
     * A client's orders are matched by email, not a foreign key - the same
     * person can submit an order before ever having a client record (their
     * very first order is what creates it, via OrderController::accept()).
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'client_email', 'email');
    }
}