<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueConsumption implements Rule
{
    protected $productId;
    protected $date;

    /**
     * Create a new rule instance.
     *
     * @param  int  $productId
     * @param  string  $date
     * @return void
     */
    public function __construct($productId, $date)
    {
        $this->productId = $productId;
        $this->date = $date;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if a consumption record with the same product_id and date exists
        return !DB::table('consumptions')
            ->where('product_id', $this->productId)
            ->whereDate('date', $this->date)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A consumption record for this product on this date already exists.';
    }
}
