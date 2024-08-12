<?php

namespace App\Http\Controllers;

use App\Models\consumption;
use App\Models\product;
use App\Rules\UniqueConsumption;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $consumption=consumption::with('product')->paginate(30);
        $products = Product::has('consumption')->get();
        return Inertia::render('Products/Consumption',[
        'consumptions'=>$consumption,
        'products'=>$products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return Inertia::render('Products/CreateConsumption',[
        'products'=>$products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'pf'=>'required|numeric|min:0',
            'date' => [
                'required',
                'date',
                new UniqueConsumption($request->product_id, $request->date),
            ],
            'consumed' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $product = Product::find($request->product_id);
                    if ($product && $value > $product->stock) {
                        $fail('The consumed quantity must be less than the available stock.');
                    }
                },
            ],
        ]);
    
        // Création d'une nouvelle instance de Consumption
        $consumption = new Consumption();
        $consumption->product_id = $validatedData['product_id'];
        $consumption->consumed = $validatedData['consumed'];
        $consumption->date= $validatedData['date'];
        $product = Product::find($request->product_id);  
        if($product->type !=='Fluid'){   
        $consumption->pf=$validatedData['consumed']*$product->weightunit/($validatedData['pf']*1000);}
        else{
            $consumption->pf=$validatedData['consumed']*$product->weightunit/($validatedData['pf']);

        }
        $consumption->save();
        $product->stock -= $request->consumed;
        $product->cs = Consumption::where('product_id', $request->product_id)->avg('pf');
        $product->covering_day=$product->stock*$product->weightunit/$product->covering;
        $product->save();
        // Sauvegarde dans la base de données
        
    
        // Redirection avec un message de succès
        return redirect()->route('consumption')->with([
            'success'=>'Consumption created successfully!',
             'class'=> 'alert alert-success'
        ]);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(consumption $consumption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $products = Product::all();
        $consumption = Consumption::findOrFail($id);
        return Inertia::render('Products/editconsumption',[
        'products'=>$products,
        'consumption'=>$consumption
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'pf'=>'required|numeric|min:0',
            'date' => [
                'required',
                'date',
                new UniqueConsumption($request->product_id, $request->date),
            ],
            'consumed' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $product = Product::find($request->product_id);
                    if ($product && $value > $product->stock) {
                        $fail('The consumed quantity must be less than the available stock.');
                    }
                },
            ],
        ]);
     $consumption = Consumption::findOrFail($id);
         // Récupération des anciens et nouveaux produits
    $oldProduct = product::findOrFail($consumption->product_id);
    $newProduct = Product::find($validatedData['product_id']);

    // Ajouter la quantité consommée précédemment au stock du produit ancien
    $oldProduct->stock += $consumption->consumed;
    $oldProduct->covering_day = $oldProduct->stock*$oldProduct->weightunit / $oldProduct->covering;
    
    $newProduct->stock -= $validatedData['consumed'];
    $newProduct->covering_day = $newProduct->stock*$newProduct->weightunit / $newProduct->covering;
    

    // Mise à jour de la consommation
    $consumption->product_id = $validatedData['product_id'];
    $consumption->consumed = $validatedData['consumed'];
    $consumption->date=$validatedData['date'];
    $consumption->pf=$validatedData['consumed']*$newProduct->weightunit/($validatedData['pf']*1000);
    $consumption->save();
    $newProduct->cs = Consumption::where('product_id', $newProduct->id)->avg('pf');
    $oldProduct->cs = Consumption::where('product_id', $oldProduct->id)->avg('pf');
    $newProduct->save();

    return redirect()->route('consumption.put')->with([
        'success' => 'Consumption updated successfully!',
        'class' => 'alert alert-success'
    ]);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $consumption = Consumption::findOrFail($id);
        $product=product::findOrFail($consumption->product_id);
        $product->stock += $consumption->consumed;
        $product->covering_day=$product->stock*$product->weightunit/$product->covering;
        $product->cs = Consumption::where('product_id', $product->id)->avg('pf');
        $product->save();
        $consumption->delete();
    
        return redirect()->route('consumption.delete')->with([
                'success' => 'Consumption deleted successfully!',
                'class' => 'alert alert-success'
            ]);
    }
    public function getConsumptionsByProduct($productId)
    {
        if ($productId == 0) {
            $consumptions = Consumption::with('product')->paginate(30);
        } else {
            $product = Product::findOrFail($productId);
            $consumptions = $product->consumption()->with('product')->paginate(30);
        }
        
        $products = Product::has('consumption')->get();
        
        return Inertia::render('Products/Consumption', [
            'consumptions' => $consumptions,
            'products' => $products,
        ]);
    }
    public function getConsumptionsByOrder($column, $direction)
    {
       
        $consumptions = Consumption::with('product')->orderBy($column, $direction)->paginate(30);
         $products = Product::has('consumption')->get();
        return Inertia::render('Products/Consumption', [
            'consumptions' => $consumptions,
            'products' => $products,
        ]);
    }
    public function search(Request $request)
{
    $term = $request->term;

    // Rechercher le produit par nom
    $product = Product::where('name', 'like', '%' . $term . '%')->first();
    $productid = $product ? $product->id : null;

    // Rechercher les consommations
    $consumption = Consumption::with('product')
        ->where('id', 'like', '%' . $term . '%')
        ->orWhere('consumed', 'like', '%' . $term . '%')
        ->orWhere(function ($query) use ($productid) {
            if ($productid) {
                $query->where('product_id', $productid);
            }
        })
        ->paginate(30);

    // Récupérer tous les produits ayant des consommations
    $products = Product::has('consumption')->get();

    return Inertia::render('Products/Consumption', [
        'consumptions' => $consumption,
        'products' => $products,
    ]);
}

public function showChart(Request $request)
{  
    $startDate = $request->input('startDate');
    $endDate = $request->input('endDate');
    $unit = $request->input('unit', 'day'); // Par défaut 'day'
    $products = Product::with(['consumption' => function($query) use ($startDate, $endDate, $unit) {
        if ($startDate && $endDate) {
            switch ($unit) {
                case 'month':
                    $query->whereBetween('date', [
                        Carbon::parse($startDate)->startOfMonth(),
                        Carbon::parse($endDate)->endOfMonth()
                    ]);
                    break;
                case 'year':
                    $query->whereBetween('date', [
                        Carbon::parse($startDate)->startOfYear(),
                        Carbon::parse($endDate)->endOfYear()
                    ]);
                    break;
                default:
                    $query->whereBetween('date', [$startDate, $endDate]);
            }
        }
    }])->get();

    $consumptionsByProduct = $products->map(function ($product) use ($unit) {
        $groupedConsumptions = $product->consumption->groupBy(function($item) use ($unit) {
            switch ($unit) {
                case 'month':
                    return Carbon::parse($item->date)->format('Y-m');
                case 'year':
                    return Carbon::parse($item->date)->format('Y');
                default:
                    return Carbon::parse($item->date)->format('Y-m-d');
            }
        });

        $consumptions = $groupedConsumptions->map(function($consumptionsGroup) {
            return $consumptionsGroup->sum('consumed');
        });

        return [
            'name' => $product->name,
            'consumptions' => $consumptions
        ];
    });

    return Inertia::render('statistic/chartconsum', [
        'consumptionsByProduct' => $consumptionsByProduct,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'unit' => $unit
    ]);
}


}
