<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index() 
    {
        return view('admin.users.index')->with([
            'users' => User::latest()->get()
        ]);
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        //delete user
        $user->delete();
        return redirect()->route('admin.users.index')->with([
            'success' => 'User deleted successfully'
        ]);
    }
}
