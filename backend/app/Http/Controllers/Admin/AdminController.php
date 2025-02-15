<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\History;
use App\Models\Product;
use App\Models\Negative;
use App\Models\Positive;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthAdminRequest;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function index()
    {
        $negatives = Negative::all();
        $positives = Positive::all();
        $products = Product::all();
        $histories = History::all();
        $users = User::all();
        $plans = Plan::all();
        $subscriptions = Subscription::all();

        return view('admin.dashboard')->with([
            'negatives' => $negatives,
            'positives' => $positives,
            'products' => $products,
            'histories' => $histories,
            'users' => $users,
            'subscriptions' => $subscriptions,
            'plans' => $plans
        ]);
    }

    /**
     * Display the login form for the admin
     */
    public function login()
    {
        if(auth()->guard('admin')->check())
        {
            return redirect()->route('admin.index');
        }

        return view('admin.login');
    }

    /**
     * Login the admin
     */
    public function auth(AuthAdminRequest $request)
    {
        if($request->validated())
        {
            if(auth()->guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                $request->session()->regenerate();
                return redirect()->route('admin.index');
            }else {
                throw ValidationException::withMessages([
                    'email' => 'These credentials do not match our records.'
                ]);
            }
            
        }
    }

    /**
     * Logout the admin
     */
    public function logout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
