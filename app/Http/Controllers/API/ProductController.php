<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Image;
use Artisan;
use Storage;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param $imgPath
     */
    private $imgPath='products/';
    public function index(Request $request)
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
            //'img_path'=>'nullable|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'required|numeric',
            'details' => 'sometimes|max:200',
            'status' => 'required',

        ],[
            'details.max'=>'The description less than 200 characters',
        ]);
        $data=[
            'title'=>$request->title,
            'price'=>$request->price,
            'details'=>($request->details?$request->details:NULL),
            'status'=>$request->status,
        ];
        if($request->has('img_path')){
            $imageName=$this->imageprocess($request->img_path);
            $data['img_path']=$imageName;
        }
        $product=Product::create($data);
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
     * @param  \Illuminate\Http\Request  $request
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



        $query = Product::query();
        if($title!=null){
            $query->where('title','LIKE',"%{$title}%");
        }
        if($from!=null && $to!=null){
            $query->whereBetween('price',[$from,$to]);
        }
        if($date!=null){
            $query->whereDate('created_at',$date);
        }

        $products=$query->paginate(2);
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
            //'img_path'=>'nullable|mimes:jpeg,png,jpg,gif,svg',
            'price' => 'required|numeric',
            'details' => 'sometimes|max:200',
            'status' => 'required',

        ],[
            'details.max'=>'The description less than 200 characters',
        ]);

        $data=[
            'title'=>$request->title,
            'price'=>$request->price,
            'details'=>($request->details?$request->details:NULL),
            'status'=>$request->status,
        ];
        if($request->has('imageEditMode') && $request->imageEditMode){
            if (Storage::disk('public')->exists($this->imgPath.$product->img_path)) {
                Storage::disk('public')->delete($this->imgPath.$product->img_path);
            }
            $imageName=$this->imageprocess($request->img_path);
            $data['img_path']=$imageName;
        }
        $product->update($data);
        return response()->json(['success'=>true,200]);
    }
    public function imageprocess($image)
    {
        $exploed1 = explode(";", $image);
        $exploed2 = explode("/", $exploed1[0]);
        $filename =  time().'.'.$exploed2[1];

        $destinationPath = storage_path('app/public/products/');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, TRUE);
        }

        Image::make($image)->resize(215, 215)->save($destinationPath.$filename);
        return $filename;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if (Storage::disk('public')->exists($this->imgPath.$product->img_path)) {
            Storage::disk('public')->delete($this->imgPath.$product->img_path);
        }
        $product->delete();
        return response()->json(['success'=>true,200]);
    }
}
