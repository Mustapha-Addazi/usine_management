<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reception extends Model
{
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $dates = ['created_at', 'updated_at'];

    // Accessor pour formater la date
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour()->format('d-m-Y H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour()->format('d-m-Y H:i:s');
    }
}
