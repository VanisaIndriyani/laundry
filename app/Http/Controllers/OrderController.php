<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function trackForm()
    {
        return view('track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        $order = Order::where('code', strtoupper($request->input('code')))->first();
        if (!$order) {
            return back()->withErrors(['code' => 'Kode tidak ditemukan'])->withInput();
        }
        return view('track', compact('order'));
    }
}