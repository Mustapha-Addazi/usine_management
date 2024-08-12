<?php

namespace App\Http\Controllers;

use App\Models\command;
use App\Models\product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {  
        $routeName = $request->route()->getName();
        if ($routeName == 'home') {
            $products=product::paginate(5);
            $commands=command::all();
        return Inertia::render('Products/home',[
        'products'=>$products,
        'commands'=>$commands
        ]);
        } else {
            $products=product::paginate(5);
            return Inertia::render('Products/product',[
            'products'=>$products
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/CreatProduct');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'type' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'cover' => 'required|min:1',
            'unityweight' => 'required|numeric|min:1',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->type = $request->type;
        if($request->type=="Fluid"){
          $product->unit="metre";
          $product->covering = $request->cover/1000;
          $product->weightunit = $request->unityweight;
        }elseif($request->type=="Solid"){
            $product->unit = $request->unit;
            $product->covering = $request->cover;
            $product->weightunit = $request->unityweight;
        }else{
                $product->unit=$request->unit;
                $product->covering=$request->cover;
                $product->weightunit=1;
        }
        $product->save();

        return redirect()->route('product')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product=product::findOrFail($id);
       return Inertia::render('Products/editproduct')->with([
            'product'=>$product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255|unique:products,name,' . $product->id,
        'type' => 'required|string|max:255',
        'unit' => 'required|string|max:255',
        'cover' => 'required|min:1',
        'unityweight' => 'required|numeric|min:1',
    ]);
    
    $product->name = $request->name;
    $product->type = $request->type;
    if($request->type=='Fluid'){
        $product->unit="metre";
        $product->covering = $request->cover/1000; 
        $product->weightunit = $request->unityweight;
      }elseif($request->type=='Solid'){
          $product->unit = $request->unit;
          $product->covering = $request->cover;
          $product->weightunit = $request->unityweight;
      }else{
        $product->unit=$request->unit;
        $product->covering=$request->cover;
        $product->weightunit=1;
      }
   
    $product->covering_day= $product->stock*$request->unityweight/$request->cover;
    $product->save();

    return redirect()->route('product.put')->with('success', 'Produit updated avec succès !');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = product::findOrFail($id);
        $product->delete();
    
        return redirect()->route('deleteproduct')
            ->with([
                'success' => 'product deleted successfully!',
                'class' => 'alert alert-success'
            ]);
    }
    public function getProductsByType($Type)
    {
        if ($Type == 0) {
            $products = product::paginate(5);
        } else {
           $products=product::Where('type',$Type)->paginate(5);
        }
        
        return Inertia::render('Products/product', [
            'products' => $products,
        ]);
    }

}
