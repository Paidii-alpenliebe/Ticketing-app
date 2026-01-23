<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'tipe', 'harga', 'stok'];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    // Relasi ke Event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Relasi ke Detail Order
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }

    // Relasi Many-to-Many ke Order melalui Detail Order
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'detail_orders')
            ->withPivot('jumlah', 'subtotal_harga');
    }
}