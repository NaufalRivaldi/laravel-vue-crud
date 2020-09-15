<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Validation of item.
     *
     */
    protected function validatorItem($data){
        return Validator::make($data, [
            'name'  => 'required|max:50',
            'qty'   => 'required|numeric',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.master');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validatorItem($request->all());

        if($validator->fails()){
            $errors = $validator->errors();
            return response()->json([
                "status" => false,
                "error" => [
                    "name" => $errors->first('name'),
                    "qty" => $errors->first('qty'),
                ]
            ]);
        }

        Item::create([
            "name" => $request->name,
            "qty" => $request->qty,
            "description" => $request->description,
        ]);

        return response()->json([
            "status" => true,
            "message" => "Success"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::find($id);

        return response()->json($item);
    }

    /**
     * Data of items
     *
     */
    public function getData(){
        $items = Item::all();

        return response()->json($items);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validatorItem($request->all());

        if($validator->fails()){
            $errors = $validator->errors();
            return response()->json([
                "status" => false,
                "error" => [
                    "name" => $errors->first('name'),
                    "qty" => $errors->first('qty'),
                ]
            ]);
        }

        $item = Item::find($id);
        $item->name = $request->name;
        $item->qty = $request->qty;
        $item->description = $request->description;
        $item->save();

        return response()->json([
            "status" => true,
            "message" => "Success"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();

        return response()->json([
            "status" => true,
            "message" => "Success"
        ]);
    }
}
