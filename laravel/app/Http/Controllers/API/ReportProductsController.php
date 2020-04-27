<?php

namespace App\Http\Controllers\API;

use App\Repositories\ReportProductsRepository;
use App\Models\ReportProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Flash;
use Response;

class ReportProductsController extends Controller
{
    /** @var  ReportProductsRepository */
    private $reportProductsRepository;

    public function __construct(ReportProductsRepository $reportProductsRepo)
    {
        $this->reportProductsRepository = $reportProductsRepo;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the ReportProducts.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $reportProducts = ReportProducts::with('products')->get();

        return response()->json( $reportProducts );
    }

    /**
     * Store a newly created ReportProducts in storage.
     *
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $reportProduct = $this->reportProductsRepository->create($input);

        return response()->json('Product saved successfully');
    }

    /**
     * Display the specified ReportProducts.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $reportProduct = $this->reportProductsRepository->find($id);

        if (empty($reportProduct)) {
            return response()->json('Product not found');
        }

        return response()->json('Product retrieved successfully');
    }

    /**
     * Show the form for editing the specified ReportProducts.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $reportProduct = $this->reportProductsRepository->find($id);

        if (empty($reportProduct)) {
            Flash::error('Report Products not found');

            return redirect(route('reportProducts.index'));
        }

        return view('report_products.edit')->with('reportProducts', $reportProducts);
    }

    /**
     * Update the specified ReportProducts in storage.
     *
     * @param int $id
     * @param UpdateReportProductsRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $input = $request->all();

        /** @var Product $product */
        $reportProduct = $this->reportProductsRepository->find($id);

        if (empty($reportProduct)) {
            return response()->json('Product not found');
        }

        $reportProduct = $this->reportProductsRepository->update($input, $id);
        
        return response()->json('Product updated successfully');
    }

    /**
     * Remove the specified ReportProducts from storage.
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
        $reportProduct = $this->reportProductsRepository->find($id);

        if (empty($reportProduct)) {
            return response()->json('Product not found');
        }

        $reportProduct->delete();

        return response()->json('Product deleted successfully');
    }

    public function getDataChartVendasCompras(){

        $data = ReportProducts::select('type', DB::raw('count(*) as total'))
        ->groupBy('type')
        ->whereYear('created_at', date('Y'))
        ->get();
        
        return response()->json($data);
    }

    public function getDataChartLucroVendas(){
        $dataOrganized = array();
        $data = ReportProducts::select(
            DB::raw('sum(profit_or_expense) as sums'), 
            DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->where('type', 'Venda')
            ->groupBy('months')
            ->get();

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'];
        
            
        $dataDecode = json_decode($data);
        $result = usort($dataDecode, array($this, "cmp_by_month"));


        foreach($months as $mon){
            $valid = false;
            foreach($dataDecode as $dat){
                if($mon == $dat->months){
                    array_push($dataOrganized, $dat->sums);
                    $valid = true;
                }
            }
            if($valid == false){
                array_push($dataOrganized, 0);
            }
        }

        return response()->json($dataOrganized);
        
    }

    public function cmp_by_month($a, $b)
    {
      return strtotime($a->months) - strtotime($b->months);
    }

    public function getDataChartLineGastosVendas(){
        $dataOrganized = array();
        $dataOrganizedTwo = array();
        $dataDefined = array();
        $data = ReportProducts::select(
            DB::raw('sum(profit_or_expense) as sums'), 
            DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->where('type', 'Venda')
            ->groupBy('months')
            ->get();

        $dataTwo = ReportProducts::select(
            DB::raw('sum(profit_or_expense) as sums'), 
            DB::raw("DATE_FORMAT(created_at,'%M') as months")
            )
            ->where('type', 'Compra')
            ->groupBy('months')
            ->get();

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'];
        
            
        $dataDecode = json_decode($data);
        $dataDecodeTwo = json_decode($dataTwo);
        $result = usort($dataDecode, array($this, "cmp_by_month"));
        $result = usort($dataDecodeTwo, array($this, "cmp_by_month"));


        foreach($months as $mon){
            $valid = false;
            foreach($dataDecode as $dat){
                if($mon == $dat->months){
                    array_push($dataOrganized, $dat->sums);
                    $valid = true;
                }
            }
            if($valid == false){
                array_push($dataOrganized, 0);
            }
        }

        foreach($months as $mon){
            $valid = false;
            foreach($dataDecodeTwo as $dat){
                if($mon == $dat->months){
                    array_push($dataOrganizedTwo, $dat->sums);
                    $valid = true;
                }
            }
            if($valid == false){
                array_push($dataOrganizedTwo, 0);
            }
        }

        array_push($dataDefined, ['Vendas' => $dataOrganized, 'Compra' => $dataOrganizedTwo]);


        return response()->json($dataDefined);
        
    }
}
