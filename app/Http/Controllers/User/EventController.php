<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PaymentType; // 1. WAJIB Import Model ini
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $event->load(['tikets', 'kategori', 'user']);

        $paymentTypes = PaymentType::all();

        return view('events.show', compact('event', 'paymentTypes'));
    }
}