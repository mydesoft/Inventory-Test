<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function getAllInventory(){
    	$invs = Inventory::orderBy('created_at', 'desc')->get();
    	if (!count($invs) > 0) {
	    	return response()->json([
	    			'success' => true,
	    			'data' => [],
	     		], 200);
    	}

    	return response()->json([
    			'success' => true,
    			'data' => $invs,
    			'nbHits' => $invs->count()
    		], 200);
    }


    public function createInventory(Request $request){
    	$validator = $this->validateRequest();
   		if ($validator->fails()) {
    		return response()->json([
    			'status' => false,
    			'msg' => $validator->errors()->all(),
    		], 406);
    	}
	    $inventory = Inventory::create([
	    	'name' => $request->name,
	    	'price' => $request->price,
	    	'quantity' => $request->quantity
	    ]);

	    return response()->json([
	    			'success' => true,
	    			'data' => $inventory,
	     		], 201);
    }


    public function getSingleInventory($id){
    	$inv = Inventory::where('id', $id)->first();
    	if (!$inv) {
    		return response()->json([
    			'status' => false,
    			'data' => [],
    		], 404);
    	}

    	return response()->json([
    			'status' => true,
    			'data' => $inv,
    		], 200);

    }

    public function updateInventory(Request $request, $id)
    {
    
    	$inv = Inventory::where('id', $id)->first();
    	if (!$inv) {
    		return response()->json([
    			'status' => false,
    			'msg' => 'No record found',
    		], 404);
    	}
	    $inventory = $inv->update([
	    	'name' => $request->name ?? $inv->name,
	    	'price' => $request->price ?? $inv->price,
	    	'quantity' => $request->quantity ?? $inv->quantity,
	    ]);

		if ($inventory) {
			$updatedInventory= Inventory::where('id', $id)->first();
			return response()->json([
    			'status' => true,
    			'data' => $updatedInventory,
    		], 200);
		}
    }


    public function deleteInventory($id){
    	$inv = Inventory::where('id', $id)->first();
    	if (!$inv) {
    		return response()->json([
    			'status' => false,
    			'msg' => 'No record found',
    		], 404);
    	}

    	$inv->delete();
    	return response()->json([
    			'status' => true,
    			'msg' => 'Inventory deleted',
    			'data' => [],
    		], 200);
    }


    public function validateRequest(){
    	return Validator::make(request()->all(), [
    		'name' => 'required',
    		'price' => 'required',
    		'quantity' => 'required',
    	]);
    }
}
