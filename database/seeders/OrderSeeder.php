<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $orders = [
            [
                'customer_name' => 'Andi',
                'phone' => '081234567890',
                'items' => 'Kemeja x2, Celana x1',
                'status' => 'received',
                'received_at' => $now,
                'due_at' => $now->copy()->addDays(2),
                'quantity' => 3,
                'total_price' => 45000,
                'notes' => 'Prioritas biasa',
            ],
            [
                'customer_name' => 'Budi',
                'phone' => '081298765432',
                'items' => 'Selimut x1',
                'status' => 'washing',
                'received_at' => $now->copy()->subHours(3),
                'due_at' => $now->copy()->addDays(1),
                'quantity' => 1,
                'total_price' => 30000,
                'notes' => null,
            ],
            [
                'customer_name' => 'Siti',
                'phone' => '081200011122',
                'items' => 'Gamis x1, Jilbab x2',
                'status' => 'ready',
                'received_at' => $now->copy()->subDay(),
                'due_at' => $now->copy()->addHours(6),
                'completed_at' => $now->copy()->subHour(),
                'quantity' => 3,
                'total_price' => 60000,
                'notes' => 'Siap diambil',
            ],
        ];

        foreach ($orders as $data) {
            // Pastikan ada kode agar kompatibel dengan MySQL strict mode
            $data['code'] = $data['code'] ?? Str::upper(Str::random(8));
            Order::create($data);
        }
    }
}