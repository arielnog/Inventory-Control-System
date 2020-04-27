<?php

namespace App\Repositories;

use App\Models\ReportProducts;
use App\Repositories\BaseRepository;

/**
 * Class ReportProductsRepository
 * @package App\Repositories
 * @version April 24, 2020, 1:42 am UTC
*/

class ReportProductsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quantity',
        'name_buyer',
        'cpf',
        'cnpj',
        'discount',
        'profit',
        'type'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ReportProducts::class;
    }
}
