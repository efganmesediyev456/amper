<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PriceQuotesDatatable;
use App\DataTables\SocialLinksDataTable;
use App\Http\Controllers\Controller;
use App\Models\PriceQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileUploadHelper;
use App\Models\SocialLink;

class PriceQuoteController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mainService->model = PriceQuote::class;
    }

    public function index(PriceQuotesDatatable $dataTable)
    {
        return $dataTable->render('backend.pages.price_quotes.index');
    }

     public function deletePriceQuote(PriceQuote $price){
        $price->delete();
        return redirect()->back()->withSuccess('Uğurla silindi!');
    }
}
