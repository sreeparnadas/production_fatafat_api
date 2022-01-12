<?php

namespace App\Http\Controllers;

use App\Http\Resources\SingleNumbers;
use App\Models\NumberCombination;
use App\Models\SingleNumber;
use Illuminate\Http\Request;
use App\Http\Resources\NumberCombinationsResource;
use Illuminate\Support\Facades\DB;

class NumberCombinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = NumberCombination::get();
        return response()->json(['success'=>1,'data'=> NumberCombinationsResource::collection($result)], 200,[],JSON_NUMERIC_CHECK);
    }

    public function getNumbersBySingleNumber($id){
        $result = NumberCombination::where('single_number_id',$id)->get();
        return response()->json(['success'=>1,'data'=> NumberCombinationsResource::collection($result)], 200,[],JSON_NUMERIC_CHECK);
    }
    public function getAllInMatrix(){
        $singleNumbers = SingleNumber::orderBy('single_order')->get();

        return response()->json(['success'=>1,'data'=> SingleNumbers::collection($singleNumbers)], 200,[],JSON_NUMERIC_CHECK);
    }

    public function reportTest(Request $request){
        $requestedData = (object)$request->json()->all();
        $draw_id = $requestedData->drawId;
        $game_id = $requestedData->gameId;
        $retArray = [];
        $singleNumbers = SingleNumber::get();
        foreach ($singleNumbers as $x){
           $y = array(
               'singleNumber'=>$x->id,

//               'quantity'=> DB::select("select ifnull(max(play_details.quantity),0) as quantity from play_masters
//                    inner join play_details on play_details.play_master_id = play_masters.id
//                    where play_masters.draw_master_id =  ".$draw_id."  and play_masters.game_id = ".$game_id." and play_details.game_type_id = 1")[0]->quantity,

               'numberCombinations'=> DB::select("select number_combinations.id as number_combination ,number_combinations.visible_triple_number ,ifnull(table1.quantity,0) as quantity from (select play_details.number_combination_id, sum(play_details.quantity) as quantity from play_masters
                    inner join play_details on play_details.play_master_id = play_masters.id
                    where play_masters.draw_master_id = ".$draw_id." and play_masters.game_id = ".$game_id."
                    group by play_details.number_combination_id) as table1
                    right outer join number_combinations on table1.number_combination_id = number_combinations.id
                    where number_combinations.single_number_id = ".$x->id."
                    order by table1.quantity desc"),
           );

           array_push($retArray,(object)$y);

//            $data = DB::select("select number_combinations.id as number_combination , ifnull(table1.quantity,0) as quantity from (select play_details.number_combination_id, sum(play_details.quantity) as quantity from play_masters
//            inner join play_details on play_details.play_master_id = play_masters.id
//            where play_masters.draw_master_id = ".$draw_id." and play_masters.game_id = ".$game_id."
//            group by play_details.number_combination_id) as table1
//            right outer join number_combinations on table1.number_combination_id = number_combinations.id
//            where number_combinations.single_number_id = ".$x->id."
//            order by table1.quantity desc");
        }

        return response()->json(['success'=>1,'data'=> $retArray], 200,[],JSON_NUMERIC_CHECK);
    }
}
