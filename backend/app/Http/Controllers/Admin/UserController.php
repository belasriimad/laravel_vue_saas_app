<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index')->with([
            'users' => User::latest()->get()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(!$user->subscriptions->count()) {
            $user->delete();
            return redirect()->route('admin.users.index')->with([
                'success' => 'User deleted successfully'
            ]);
        }else {
            return redirect()->route('admin.users.index')->with([
                'error' => 'User can not be deleted because he has a subscription!'
            ]);
        }
    }
}
