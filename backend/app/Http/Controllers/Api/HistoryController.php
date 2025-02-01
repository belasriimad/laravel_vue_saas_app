<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\History;
use App\Models\Product;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function store(Request $request)
    {
        //check if product already exists in user history
        $product = History::where(
            [
                'product_id' => $request->product_id,
                'user_id' => $request->user()->id
            ]
        )->first();
        //if no then add the product
        if(!$product) {
            History::create([
                'product_id' => $request->product_id,
                'user_id' => $request->user()->id
            ]);
            //return user with new history
            return UserResource::make($request->user());
        }else {
            //return the user with the same history
            return UserResource::make($request->user());
        }
    }
}
