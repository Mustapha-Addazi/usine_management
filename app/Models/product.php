<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
   protected $table = 'products'; // Assurez-vous que le nom de la table est correctement défini

   // Exemple de méthode pour récupérer le nom du produit à partir de son ID
   public static function getProductName($productId)
   {
       $product = Product::find($productId);
       return $product ? $product->name : 'Unknown';
   }
   protected $dates = ['created_at', 'updated_at'];

   // Accessor pour formater la date
   public function getCreatedAtAttribute($value)
   {
       return Carbon::parse($value)->format('d-m-Y H:i:s');
   }
    use HasFactory;
    public function consumption(){
       return $this->hasMany(consumption::class);
    }
    public function reception(){
       return $this->hasMany(reception::class);
    }
    public function command(){
         return $this->hasMany(command::class);
    }
}
