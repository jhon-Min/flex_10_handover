<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\Note;
use Validator;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class NoteController extends BaseController
{

    /**
     * @group Products
     * Notes
     * Add notes to product.
     * @bodyParam product Integer required Product ID ( Table : "products" )
     * @bodyParam description String required Note (description)
     * @bodyParam user_name String required User name
     *  
     */
    public function store(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                "product" => "required|integer|exists:products,id",
                "description" => "required|string|min:2",
            ]);

            if ($validator->fails()) {
                return $this->sendError('Invalid input', $validator->errors()->all(), 401);
            }

            $note_array = [
                'user_id' => Auth::id(),
                'product_id' => $request->product,
                'description' => $request->description,
            ];

            $save_note = Note::create($note_array);
            
            if($save_note->id) {
                $note =  Note::with(['user'])->find($save_note->id);
                return $this->sendResponse($note, "Note saved.");
            } else {
                return $this->sendError("Note save Failed", [], 401);
            }
             
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage(), [], 401);
        }
    }
}
