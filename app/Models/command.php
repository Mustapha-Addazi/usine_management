<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class command extends Model
{
    use HasFactory;
    public function product(){
      return   $this->belongsTo(product::class);
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour()->format('d-m-Y H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->addHour()->format('d-m-Y H:i:s');
    }
}
