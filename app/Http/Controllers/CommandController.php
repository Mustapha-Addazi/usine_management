<?php

namespace App\Http\Controllers;

use App\Models\command;
use App\Models\product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product=product::has("command")->get();
        $command=command::with("product")->paginate(5);
        return Inertia::render("Products/command")->with([
            "products"=>$product,
            "commands"=>$command
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product=product::all();
        return Inertia::render('Products/CreateCommand')->with([
            'products'=>$product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'status'=>'required',
            'commanded' => [
                'required',
                'numeric',
                'min:0'
            ],
            'notif'=>'min:0'
        ]);
        $command = Command::where('product_id', $validatedData['product_id'])->first();   
       
        if($command){ 
            $command->status=$validatedData['status']; 
        $command->product_id=$validatedData['product_id'];
        if($command->status==="confirmed"){
            $command->commanded+=$validatedData['commanded'];
            $command->on_hold=0;
        }else{
            $command->on_hold=$validatedData['commanded'];
        }
    }else{
       $command= new command();
       $command->status=$validatedData['status']; 
       $command->product_id=$validatedData['product_id'];
        if($command->status==="confirmed"){
            $command->commanded=$validatedData['commanded'];
            $command->on_hold=0;
        }else{
            $command->commanded=0;
            $command->on_hold=$validatedData['commanded'];
        }}
        $command->save();
        return redirect()->route('command.index')->with([
            'success'=>'Command created successfully!',
             'class'=> 'alert alert-success'
        ]);
        }
        

    /**
     * Display the specified resource.
     */
    public function show(command $command)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(command $command)
    {
        $productID=$command->product_id;
        $products=product::all();
        $product=product::findOrFail($productID);
        return Inertia::render('Products/editcommand')->with([
            'product'=>$product,
            'command'=>$command,
            'products'=>$products
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, command $command)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'status'=>'required',
            'commanded' => [
                'required',
                'numeric',
                'min:0'
            ],
            'notif'=>'min:1'
        ]);
        $command->product_id=$validatedData['product_id'];
         $command->status=$validatedData['status'];
        if($command->status==="confirmed"){
            $command->commanded+=$validatedData['commanded'];
            $command->on_hold=0;
        }else{
            $command->on_hold=$validatedData['commanded'];
        }
        $command->notif=$validatedData['notif'];
       
        $command->save();
        return redirect()->route('command.index')->with([
            'success'=>'Command updated successfully!',
             'class'=> 'alert alert-success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(command $command)
    {
        $command->delete();
        return redirect()->route('command.delete')->with([
         'success'=>'Command deleted successfully!',
             'class'=> 'alert alert-success'
        ]);

    }
}
