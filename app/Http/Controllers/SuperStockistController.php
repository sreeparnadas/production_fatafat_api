<?php

namespace App\Http\Controllers;

use App\Http\Resources\StockistResource;
use App\Models\StockistToTerminal;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperStockistController extends Controller
{

    public function get_all_super_stockist(){

        $data = User::whereUserTypeId(5)->get();

        return response()->json(['success'=>1, 'data' => StockistResource::collection($data)], 200);
//        return response()->json(['success'=>1, 'data' => $data], 200);
    }

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

        if ($user){
            $stockistToTerminal = new StockistToTerminal();
            $stockistToTerminal->super_stockist_id = $user->id;
            $stockistToTerminal->save();
        }

        return response()->json(['success'=>1, 'data' => $user], 200);
    }

    public function update_super_stockist(Request $request){
        $requestedData = (object)$request->json()->all();

        $user = User::find($requestedData->id);
        $user->user_name = $requestedData->userName;
        $user->save();

        return response()->json(['success'=>1, 'data' => $user], 200);
    }
}
