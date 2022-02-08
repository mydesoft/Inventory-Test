<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function readInventory(){
    	$invs = Inventory::orderBy('created_at', 'desc')->get();
    	if (!count($invs) > 0) {
	    	return response()->json([
	    			'success' => true,
	    			'data' => [],
	     		], 200);
    	}

    	return response()->json([
    			'success' => true,
    			'data' => $invs
    		], 200);
    }

 


public function addToCart(Request $request){
	$validator = $this->validateRequest();
	if ($validator->fails()) {
		return response()->json([
			'success' => false,
			'msg' => $validator->errors()->all()
		]);
		
	}

	$inventory = Inventory::where('name', $request->inventory_name)->first();
	
	if (!$inventory) {
		return response()->json([
	    			'success' => false,
	    			'msg' => 'No inventory for this '. $request->inventory_name,
	     		], 404);
	}

	if ($inventory->quantity < $request->quantity) {
		return response()->json([
	    			'success' => false,
	    			'msg' => 'Invalid Quantity',
	     		], 406);
	}

	$newInventoryQuantity = $inventory->quantity - $request->quantity;



	$inventory->update(['quantity' => $newInventoryQuantity]);

	$cart = Cart::create([
		'inventory_name' => $request->inventory_name,
		'quantity' => $request->quantity,
		'price' => $request->price
	]);

	return response()->json([
	    'success' => true,
	    'msg' => 'Inventory added to cart',
	    'data' => $cart,
	     ], 200);
}


 public function validateRequest(){
    	return Validator::make(request()->all(), [
    		'inventory_name' => 'required',
    		'price' => 'required',
    		'quantity' => 'required',
    	]);
    }

}