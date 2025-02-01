<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Get all histories
     */
    public function index() 
    {
        return view('admin.histories.index')->with([
            'histories' => History::latest()->get()
        ]);
    }

    /**
     * Delete history
     */
    public function destroy(History $history)
    {
        //delete history
        $history->delete();
        return redirect()->route('admin.histories.index')->with([
            'success' => 'History deleted successfully'
        ]);
    }
}
