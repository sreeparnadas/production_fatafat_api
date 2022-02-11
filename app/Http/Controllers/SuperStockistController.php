<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperStockistController extends Controller
{
    public function create_super_stockist(Request $request){
        $requestedData = (object)$request->json()->all();

        $user = new User();
        $user->user_name = $requestedData->userName;
        $user->email = $requestedData->pin;
        $user->password = md5($requestedData->pin);
        $user->user_type_id = 5;
        $user->opening_balance = 0;
        $user->closing_balance = 0;
        $user->save();

        return response()->json(['success'=>1, 'message' => $user], 200);
    }
}
