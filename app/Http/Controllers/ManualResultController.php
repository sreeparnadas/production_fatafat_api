<?php

namespace App\Http\Controllers;

use App\Http\Resources\ManualResultResource;
use App\Models\DrawMaster;
use App\Models\ManualResult;
use App\Models\ResultMaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManualResultController extends Controller
{

    public function index()
    {
        //
    }

    public function save_manual_result(Request $request)
    {
        $rules = array(
            'drawMasterId'=>['required','exists:draw_masters,id',
                    function($attribute, $value, $fail){
                        $existingManual=ManualResult::where('draw_master_id', $value)->where('game_date','=',Carbon::today())->first();
                        if($existingManual){
                            $fail($value.' Duplicate entry');
                        }
                    }
                ],
            'numberCombinationId'=>'required|exists:number_combinations,id',
        );
        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json(['success'=>0,'data'=>null,'error'=>$validator->messages()], 406,[],JSON_NUMERIC_CHECK);
        }
        $requestedData = (object)$request->json()->all();

        $drawMasterTemp = DrawMaster::whereGameId($requestedData->gameId)->whereId($requestedData->drawMasterId)->first();
        if ($drawMasterTemp->is_draw_over === 'yes'){

            $manualResult = new ManualResult();
            $manualResult->draw_master_id = $requestedData->drawMasterId;
            $manualResult->number_combination_id = $requestedData->numberCombinationId;
            $manualResult->game_id = $requestedData->gameId;
            $manualResult->game_date = Carbon::today();
            $manualResult->save();

            $resultMaster = new ResultMaster();
            $resultMaster->draw_master_id = $requestedData->drawMasterId;
            $resultMaster->number_combination_id = $requestedData->numberCombinationId;
            $resultMaster->game_id = $requestedData->gameId;
            $resultMaster->game_date = Carbon::today();
            $resultMaster->save();

            return response()->json(['success'=>1,'data'=> new ManualResultResource($manualResult)], 200,[],JSON_NUMERIC_CHECK);
        }else{

            $manualResult = new ManualResult();
            $manualResult->draw_master_id = $requestedData->drawMasterId;
            $manualResult->number_combination_id = $requestedData->numberCombinationId;
            $manualResult->game_id = $requestedData->gameId;
            $manualResult->game_date = Carbon::today();
            $manualResult->save();

            return response()->json(['success'=>1,'data'=> new ManualResultResource($manualResult)], 200,[],JSON_NUMERIC_CHECK);
        }

//        DB::beginTransaction();
//        try{
//
//            $manualResult = new ManualResult();
//            $manualResult->draw_master_id = $requestedData->drawMasterId;
//            $manualResult->number_combination_id = $requestedData->numberCombinationId;
//            $manualResult->game_id = $requestedData->gameId;
//            $manualResult->game_date = Carbon::today();
//            $manualResult->save();
//
//            DB::commit();
//        }catch (\Exception $e){
//            DB::rollBack();
//            return response()->json(['success'=>0, 'data' => null, 'error'=>$e->getMessage()], 500);
//        }
//
//        return response()->json(['success'=>1,'data'=> new ManualResultResource($manualResult)], 200,[],JSON_NUMERIC_CHECK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ManualResult  $manualResult
     * @return \Illuminate\Http\Response
     */
    public function show(ManualResult $manualResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ManualResult  $manualResult
     * @return \Illuminate\Http\Response
     */
    public function edit(ManualResult $manualResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ManualResult  $manualResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManualResult $manualResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ManualResult  $manualResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManualResult $manualResult)
    {
        //
    }
}
