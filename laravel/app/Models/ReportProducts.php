<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReportProducts
 * @package App\Models
 * @version April 24, 2020, 1:42 am UTC
 *
 * @property integer quantity
 * @property string name_buyer
 * @property string cpf
 * @property string cnpj
 * @property number discount
 */
class ReportProducts extends Model
{
    use SoftDeletes;

    public $table = 'report_products';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'type',
        'name_product',
        'product_id',
        'quantity',
        'name_buyer',
        'cpf',
        'cnpj',
        'discount',
        'profit_or_expense'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
        'id' => 'integer',
        'quantity' => 'integer',
        'name_buyer' => 'string',
        'cpf' => 'string',
        'cnpj' => 'string',
        'discount' => 'float',
        'profit_or_expense' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'quantity' => 'required',
        'name_buyer' => 'required',
        'type' => 'required'
    ];
    
    public function products()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    
}
