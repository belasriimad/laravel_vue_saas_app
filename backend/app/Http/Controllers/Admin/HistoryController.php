<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.histories.index')->with([
            'histories' => History::latest()->get()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(History $history)
    {
        $history->delete();
        return redirect()->route('admin.histories.index')->with([
            'success' => 'History deleted successfully'
        ]);
    }
}
