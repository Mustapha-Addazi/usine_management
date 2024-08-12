<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class consumption extends Model
{
    use HasFactory;

  protected $fillable=['product_id','consumed','date','pf'];

    public function product(){
       return $this->belongsTo(product::class,'product_id');
    }
    protected $dates = ['created_at', 'updated_at'];

    // Accessor pour formater la date
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour()->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour()->format('Y-m-d H:i:s');
    }
}
