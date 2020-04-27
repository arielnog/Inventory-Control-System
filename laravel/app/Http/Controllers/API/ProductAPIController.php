<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Repositories\ReportProductsRepository;
use Illuminate\Http\Request;
use Response;

/**
 * Class ProductController
 * @package App\Http\Controllers\API
 */

class ProductAPIController extends Controller
{
    /** @var  ProductRepository */
    private $productRepository;
    private $reportproductRepository;

    public function __construct(ProductRepository $productRepo, ReportProductsRepository $reportproductRepo)
    {
        $this->productRepository = $productRepo;
        $this->reportproductRepository = $reportproductRepo;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the Product.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $products = $this->productRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return response()->json( $products );
    }

    /**
     * Store a newly created Product in storage.
     * POST /products
     *
     * @param CreateProductAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $expense = 0;

        $product = $this->productRepository->create($input);

        if($input['quantity_in_stock'] > 0){

            $expense = $input['quantity_in_stock'] * $product->price_unit_purchased;

            $report = [
                       'product_id' => $product->id, 
                       'profit_or_expense' => $expense,
                       'quantity' => $input['quantity_in_stock'], 
                       'type' => 'Compra'];

            $reportNote = $this->reportproductRepository->create($report);
        }

        return response()->json('Product saved successfully');
    }

    /**
     * Display the specified Product.
     * GET|HEAD /products/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return response()->json('Product not found');
        }

        return response()->json('Product retrieved successfully');
    }

    /**
     * Update the specified Product in storage.
     * PUT/PATCH /products/{id}
     *
     * @param int $id
     * @param UpdateProductAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();

        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return response()->json('Product not found');
        }

        $product = $this->productRepository->update($input, $id);
        
        return response()->json('Product updated successfully');
    }

    /**
     * Remove the specified Product from storage.
     * DELETE /products/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return response()->json('Product not found');
        }

        $product->delete();

        return response()->json('Product deleted successfully');
    }

    public function productsAddQuantity($id, Request $request){
        $input = $request->all();
        $expense = 0;

        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return response()->json('Product not found');
        }

        $difference = $input['quantity_in_stock'] - $product->quantity_in_stock;

        $product = $this->productRepository->update($input, $id);


        if($difference > 0){

            $expense = $input['quantity_add_report'] * $product->price_unit_purchased;

            $report = [
                       'product_id' => $product->id, 
                       'profit_or_expense' => $expense,
                       'quantity' => $difference, 
                       'type' => 'Compra'];

            $reportNote = $this->reportproductRepository->create($report);
        }

        return response()->json('Product add quantity successfully');
    }

    public function productsSell($id, Request $request){
        $input = $request->all();
        $profit = 0;

        /** @var Product $product */
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return response()->json('Product not found');
        }

        $difference = $product->quantity_in_stock - $input['quantity'];

        if($input['quantity'] > 0){
            $product = $this->productRepository->update(['quantity_in_stock' => $difference], $id);
            $profit = $input['quantity'] * $product->price_unit_sold;
            $expense = $input['quantity'] * $product->price_unit_purchased;
            $profit = $profit - $expense;

            if($input['discount'] > 0){
                $profit = $profit - $input['discount'];
            }

            $report = [
                           'product_id' => $product->id, 
                           'quantity' => $input['quantity'],
                           'discount' => $input['discount'],
                           'profit_or_expense' => $profit,
                           'cnpj' => $input['cnpj'],
                           'cpf' => $input['cpf'],
                           'name_buyer' => $input['name_buyer'],
                           'type' => 'Venda'];
    
            $reportNote = $this->reportproductRepository->create($report);
            return response()->json('Product add quantity successfully');

        }

        return response()->json('You cannot sell 0 products.');
    }


}
