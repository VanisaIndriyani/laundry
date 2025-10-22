<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class AdminController extends Controller
{
    public function loginForm()
    {
        if (session('is_admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);
        $pass = $request->input('password');
        $expected = env('ADMIN_PASSWORD', 'secret');
        if ($pass === $expected) {
            session(['is_admin' => true]);
            return redirect()->route('admin.dashboard');
         }
        return back()->withErrors(['password' => 'Password salah']);
    }

    public function logout()
    {
        session()->forget('is_admin');
        return redirect()->route('admin.login');
    }

    private function ensureAdmin()
    {
        if (!session('is_admin')) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(redirect()->route('admin.login'));
        }
    }

    public function reports(Request $request)
    {
        $this->ensureAdmin();

        $perPage = (int)$request->input('per_page', 25);
        $perPage = $perPage > 0 ? $perPage : 25;

        $startInput = $request->input('start_date');
        $endInput = $request->input('end_date');
        $status = $request->input('status');

        $start = $startInput ? \Carbon\Carbon::parse($startInput)->startOfDay() : now()->startOfMonth();
        $end = $endInput ? \Carbon\Carbon::parse($endInput)->endOfDay() : now()->endOfDay();

        $base = Order::query()->whereBetween('created_at', [$start, $end]);
        if ($status) {
            $valid = ['received','washing','drying','ironing','ready','picked_up'];
            if (in_array($status, $valid, true)) {
                $base->where('status', $status);
            }
        }

        $orders = (clone $base)->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        $summary = [
            'total_orders' => (clone $base)->count(),
            'total_quantity' => (clone $base)->sum('quantity'),
            'total_income' => (clone $base)->sum('total_price'),
        ];

        $statusCounts = (clone $base)
            ->select('status', DB::raw('COUNT(*) as cnt'))
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $daily = (clone $base)
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_price) as income'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.reports', [
            'orders' => $orders,
            'summary' => $summary,
            'statusCounts' => $statusCounts,
            'daily' => $daily,
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
            'perPage' => $perPage,
            'status' => $status,
        ]);
    }
    public function orders(Request $request)
    {
        $this->ensureAdmin();
        $perPage = (int)$request->input('per_page', 20);
        if ($perPage <= 0) { $perPage = 20; }

        $query = Order::query()->latest();

        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function ($builder) use ($q) {
                $builder->where('code', 'like', "%$q%")
                    ->orWhere('customer_name', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $valid = ['received','washing','drying','ironing','ready','picked_up'];
            if (in_array($status, $valid, true)) {
                $query->where('status', $status);
            }
        }

        $orders = $query->paginate($perPage)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'items' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'due_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        $data['status'] = 'received';
        $order = Order::create($data);
        return redirect()->route('admin.orders')->with('success', "Order {$order->code} dibuat");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->ensureAdmin();
        $request->validate([
            'status' => 'required|in:received,washing,drying,ironing,ready,picked_up',
        ]);
        $order->status = $request->input('status');
        if ($order->status === 'ready' && !$order->completed_at) {
            $order->completed_at = now();
        }
        if ($order->status === 'picked_up') {
            if (!$order->completed_at) {
                $order->completed_at = now();
            }
        }
        $order->save();
        return back()->with('success', 'Status diperbarui');
    }

    public function dashboard(Request $request)
    {
        $this->ensureAdmin();
        $days = (int)($request->input('days', 30));
        if ($days <= 0) { $days = 30; }

        $startDate = now()->startOfDay()->subDays($days - 1);
        $endDate = now()->endOfDay();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();
        $allOrders = Order::all();

        $chartLabels = [];
        $incomeData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $chartLabels[] = $date->format('D');

            $dailyOrders = $orders->filter(function ($o) use ($key) {
                return $o->created_at && $o->created_at->format('Y-m-d') === $key;
            });

            $income = $dailyOrders->sum(function ($o) {
                return (float)($o->total_price ?? 0);
            });

            $incomeData[] = round($income, 2);
        }

        $totalIncome = $orders->sum(function ($o) {
            return (float)($o->total_price ?? 0);
        });
        $totalOrders = $orders->count();

        $statusCounts = [
            'pending' => $allOrders->whereIn('status', ['received','washing','drying','ironing'])->count(),
            'ready' => $allOrders->where('status', 'ready')->count(),
            'completed' => $allOrders->where('status', 'picked_up')->count(),
        ];

        $recentOrders = Order::latest()->take(10)->get();

        $orderChange = '+12.5';
        $revenueChange = '+8.2';

        return view('admin.dashboard', [
            'labels' => $chartLabels,
            'incomeData' => $incomeData,
            'days' => $days,
            'totalIncome' => $totalIncome,
            'totalOrders' => $totalOrders,
            'statusCounts' => $statusCounts,
            'recentOrders' => $recentOrders,
            'orderChange' => $orderChange,
            'revenueChange' => $revenueChange,
        ]);
    }

    public function customers(Request $request)
    {
        $this->ensureAdmin();
    
        $q = trim($request->input('q', ''));
        $perPage = (int) $request->input('per_page', 20);
        $perPage = $perPage > 0 ? $perPage : 20;
    
        // Gunakan base query terpisah agar tidak saling mempengaruhi
        $base = Order::query();
        if ($q !== '') {
            $base->where(function ($query) use ($q) {
                $query->where('customer_name', 'like', "%$q%")
                      ->orWhere('phone', 'like', "%$q%")
                      ->orWhere('code', 'like', "%$q%");
            });
        }
    
        // Query pelanggan teragregasi
        $customersQuery = (clone $base)
            ->select([
                'customer_name',
                'phone',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_price) as total_spent'),
                DB::raw('MAX(created_at) as last_order_at'),
            ])
            ->groupBy('customer_name', 'phone')
            ->orderByDesc('last_order_at');
    
        $customers = $customersQuery
            ->paginate($perPage)
            ->appends($request->query());
    
        // Ringkasan memakai base query tanpa ORDER BY alias
        $summary = [
            'total_customers' => (clone $base)
                ->select('customer_name', 'phone')
                ->groupBy('customer_name', 'phone')
                ->get()->count(),
            'total_orders' => (clone $base)->count(),
            'total_spent' => (clone $base)->sum('total_price'),
        ];
    
        return view('admin.customers.index', compact('customers', 'q', 'perPage', 'summary'));
    }
}