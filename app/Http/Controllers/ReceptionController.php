<?php

namespace App\Http\Controllers;

use App\Models\command;
use App\Models\product;
use App\Models\reception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $receptions = Reception::with('product')->paginate(10);
        $products = Product::has('reception')->get();
        return Inertia::render('Products/reception',[
            'receptions'=>$receptions,
            'products'=>$products
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product=product::all();
        return Inertia::render('Products/CreatReception',[
        'products'=>$product]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $command = Command::where('product_id', $request->product_id)->first();
    
        if ($command === null) {
            // Redirection si la commande n'existe pas
            return Inertia::render('Products/CreateReception', [
                'failed' => 'This products is not commanded yet!'
            ]);
        }
    
        // Validation des données
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'recipied' => [
                'required',
                'numeric',
                'min:1'
            ],
            function ($attribute, $value, $fail) use ($command) {
                if ($command && $value > $command->commanded) {
                    $fail('La quantité consommée doit être inférieure au stock disponible.');
                }
            },
        ]);
    
    
        // Vérifier si la commande existe encore après la validation
        $command = Command::where('product_id', $validatedData['product_id'])->first();
    if($command->commanded<$validatedData['recipied']){
        return Inertia::render('Products/CreateReception', [
            'failed' => 'This products is not commanded yet!'
        ]);
    }
        // Création d'une nouvelle instance de Reception
        $reception = new Reception();
        $reception->product_id = $validatedData['product_id'];
        $reception->recipied = $validatedData['recipied'];
    
        // Mise à jour du stock du produit
        $product = Product::findOrFail($request->product_id);
        $product->stock += $request->recipied;
        $product->covering_day = $product->stock * $product->weightunit / $product->covering;
        $product->save();
    
        // Mise à jour de la commande
        $command->commanded -= $validatedData['recipied'];
        $command->save();
    
        // Sauvegarde de la réception
        $reception->save();
    
        // Redirection avec un message de succès
        return redirect()->route('reception')->with([
            'success' => 'Reception created successfully!',
            'class' => 'alert alert-success'
        ]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(reception $reception)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $reception= reception::findOrFail($id);
        $prod=$reception->product;
        $product=product::all();
        return Inertia::render('Products/editreception')->with([
            'products'=>$product,
            'reception'=>$reception,
            'prod'=>$prod
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    { 
        $command = Command::where('product_id', $request->product_id)->first();
    
        if ($command === null) {
            $reception= reception::findOrFail($id);
        $prod=$reception->product;
        $product=product::all();
        return Inertia::render('Products/editreception')->with([
            'products'=>$product,
            'reception'=>$reception,
            'prod'=>$prod
        ]);
        }
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'recipied' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);
        $command = Command::where('product_id', $validatedData['product_id'])->first();
        if($command->commanded<$validatedData['recipied']){
            $reception= reception::findOrFail($id);
            $prod=$reception->product;
            $product=product::all();
            return Inertia::render('Products/editreception')->with([
                'products'=>$product,
                'reception'=>$reception,
                'prod'=>$prod
            ]);
        }
        $reception=reception::findOrFail($id);
        $oldProduct=product::findOrFail($reception->product_id);
        $oldcommand = Command::where('product_id', $oldProduct->id)->first();
        $oldcommand->commanded+=$reception->recipied;
        $oldcommand->save();
        $oldProduct->stock-=$reception->recipied;
        $oldProduct->covering_day=$oldProduct->stock*$oldProduct->weightunit/$oldProduct->covering;
        $oldProduct->save();
        $newProduct = Product::find($validatedData['product_id']);
        $newCommand = Command::where('product_id', $newProduct->id)->first();
        $newProduct->stock+=$validatedData['recipied'];
        $newCommand->commanded -= $validatedData['recipied'];
        $newCommand->save();
        $newProduct->covering_day=$newProduct->stock*$newProduct->weightunit/$newProduct->covering;
        $newProduct->save();
        $reception->product_id=$validatedData['product_id'];
        $reception->recipied=$validatedData['recipied'];
        $reception->save();
        return redirect()->route('reception.put')->with([
            'success' => 'Reception updated successfully!',
             'class' => 'alert alert-success'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $reception = reception::findOrFail($id);
        $product = product::findOrFail($reception->product_id);
        $command = Command::where('product_id', $reception->product_id)->first();
        $command->commanded+=$reception->recipied;
        $product->stock -=$reception->recipied;
        $product->covering_day=$product->stock*$product->weightunit/$product->covering;
        $product->save();
        $command->save();
        $reception->delete();
    
        return redirect()->route('deletereception')
            ->with([
                'success' => 'Reception deleted successfully!',
                'class' => 'alert alert-success'
            ]);
    }
    
    public function getReceptionsByProduct($productId){
        
        if ($productId == 0) {
            $receptions = reception::with('product')->paginate(10);
        } else {
            $product = Product::findOrFail($productId);
            $receptions = $product->reception()->with('product')->paginate(10);
        }
    $products = Product::has('reception')->get();
        return Inertia::render('Products/reception', [
            'receptions' => $receptions,
            'products' => $products
        ]);
    }
        public function getReceptionsByOrder($column,$direction){
      
            $products = Product::has('reception')->get();
    
            $receptions = reception::with('product')->orderBy($column,$direction)->paginate(10);
            
            return Inertia::render('Products/reception', [
                'receptions' => $receptions,
                'products' => $products
            ]);
    }
    
}
