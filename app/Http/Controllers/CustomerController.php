<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;

class CustomerController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
        ]);

        // Jangan auto-login; arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Silakan login untuk melanjutkan.');
    }

    public function showLoginForm()
    {
        // If already logged in, redirect to dashboard
        if (session('customer_id')) {
            return redirect('/dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $type = $request->input('type', 'customer');
        
        if ($type === 'customer') {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'password.required' => 'Password wajib diisi',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors(['customer' => $validator->errors()->first()])
                    ->withInput();
            }

            $user = User::where('email', $request->email)
                       ->first();

            if ($user && Hash::check($request->password, $user->password)) {
                session([
                    'customer_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                ]);

                return redirect()->route('track')->with('success', 'Login berhasil! Anda bisa melacak pesanan Anda.');
            }

            return redirect()->back()
                ->withErrors(['customer' => 'Email atau password salah'])
                ->withInput();
        }

        return redirect()->back()->withErrors(['customer' => 'Tipe login tidak valid']);
    }

    public function logout()
    {
        session()->forget(['customer_id', 'customer_name', 'customer_email']);
        return redirect('/')->with('success', 'Anda telah logout');
    }

    public function dashboard()
    {
        if (!session('customer_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        // Get customer orders statistics
        $customerId = session('customer_id');
        $orders = collect(); // Placeholder - will be replaced with actual database query
        
        $totalOrders = $orders->count();
        $pendingOrders = $orders->whereIn('status', ['pending', 'processing'])->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        
        // Get recent orders (limit 5)
        $recentOrders = $orders->sortByDesc('created_at')->take(5);
        
        return view('customer.dashboard', [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'orders' => $recentOrders
        ]);
    }

    public function profile()
    {
        if (!session('customer_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        return view('customer.profile');
    }
    
    public function updateProfile(Request $request)
    {
        if (!session('customer_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date'
        ]);
        
        // Update session data
        session([
            'customer_name' => $request->name,
            'customer_email' => $request->email,
            'customer_phone' => $request->phone,
            'customer_address' => $request->address
        ]);
        
        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function showTrackForm()
    {
        if (!session('customer_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
        return view('customer.track');
    }
    
    public function trackOrder(Request $request)
    {
        if (!session('customer_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $request->validate([
            'order_code' => 'required|string'
        ]);
        
        // Cari order berdasarkan kode di tabel orders
        $orderCode = strtoupper($request->order_code);
        $order = Order::where('code', $orderCode)->first();
        
        if (!$order) {
            return back()->with('error', 'Pesanan dengan kode "' . $orderCode . '" tidak ditemukan. Pastikan kode pesanan benar.')->withInput();
        }
        
        return view('customer.track', compact('order'));
    }

    private function ensureCustomer()
    {
        if (!session('customer_id')) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }
    }
}