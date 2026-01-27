<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'total_harga' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'event_id',
        'payment_type_id', // Tambahkan ini agar bisa disimpan ke database
        'order_date',
        'total_harga',
    ];

    /**
     * Relasi ke Tipe Pembayaran (PENTING untuk Fix Error)
     */
    public function paymentType()
    {
        // Menghubungkan kolom payment_type_id ke model PaymentType
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tikets()
    {
        return $this->belongsToMany(Tiket::class, 'detail_orders')
            ->withPivot('jumlah', 'subtotal_harga');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
}