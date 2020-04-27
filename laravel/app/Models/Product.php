<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Models
 * @version April 14, 2020, 11:00 pm UTC
 *
 * @property string license
 * @property string support
 * @property string technology
 * @property number tickets
 * @property integer sales
 * @property number earnings
 */
class Product extends Model
{
    use SoftDeletes;

    public $table = 'products';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'name_product',
        'quantity_in_stock',
        'price_unit_purchased',
        'price_unit_sold'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name_product' => 'string',
        'quantity_in_stock' => 'integer',
        'price_unit_purchased' => 'float',
        'price_unit_sold' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name_product' => 'required',
        'quantity_in_stock' => 'required',
        'price_unit_purchased' => 'required',
        'price_unit_sold' => 'required'
    ];

    
}
