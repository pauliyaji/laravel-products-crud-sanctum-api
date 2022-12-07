<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status'=> '200',
            'products'=>$products,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:products',
            'slug'=> 'required',
            'price' =>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages(),
            ]);
        }else
        {
            $product = Product::create($request->all());
            return response()->json([
                'status' => 200,
                'message' => 'Product successfully added'
            ]);
        }


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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        if($product){
            return response()->json([
                'status'=>200,
                'product'=>$product,
            ]);
        }else{
            return response()->json([
                'status'=> 401,
                'message'=> 'Product with '+ $id + "not found",
            ]);
        }
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
        $product = Product::find($id);
        if($product){
            /* $product->name = $request->input('name');
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->update(); */
            $product->update($request->all());

            return response()->json([
                'status'=> 200,
                'message'=> 'Product successfully updated',
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Product not found',
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //return Product::destroy($id);
        $product = Product::find($id);
        if($product){
            $product->delete();
            return response()->json([
                'status'=> 200,
                'message'=>'Record successfully deleted',
            ]);

        }else{
            return response()->json([
                'status'=>401,
                'message'=>'Product with id'.' '. $id.  ' not found',
            ]);
        }

    }

    public function search($name){

        $search = Product::where('name', 'like','%'.$name.'%')->get();
        if($search->count() > 0){
            return response()->json([
                'status'=> 200,
                'product'=>$search,
            ]);
        }else{
            return response()->json([
                'status'=>401,
                'message'=>'No product matching your search parameter',
            ]);
        }
    }
}
