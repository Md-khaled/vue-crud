<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return $products=Product::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:100|unique:products,title',
            'price' => 'required|numeric',
            'details' => 'sometimes|max:200',
            'status' => 'required',

        ],[
            'details.max'=>'The description less than 200 characters',
        ]);

        $product=Product::create([
            'title'=>$request->title,
            'price'=>$request->price,
            'details'=>($request->details?$request->details:NULL),
            'status'=>$request->status,
        ]);
        return response()->json(['product'=>$product,200]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Display the search product.
     *
     * @param  \Illuminate\Http\Request  $product
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'title' => 'nullable|string|max:100',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'date' => 'nullable|date',
       ]);

        $title=$request->title;
        $from=$request->price_from;
        $to=$request->price_to;
        $date=$request->date;

        $products=Product::query();

        if ($request->title){
            $products->where('title', 'LIKE', "%{$request->title}%");
        }

        if ($request->date){
            $products->whereDate('created_at'$request->date);
        }
        $products->get();
        return $products;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'title' => 'required|string|max:100|unique:products,title,'.$product->id,
            'price' => 'required|numeric',
            'details' => 'sometimes|max:200',
            'status' => 'required',

        ],[
            'details.max'=>'The description less than 200 characters',
        ]);
        $product->update([
            'title'=>$request->title,
            'price'=>$request->price,
            'details'=>($request->details?$request->details:NULL),
            'status'=>$request->status,
        ]);
        return response()->json(['success'=>true,200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success'=>true,200]);
    }
}
