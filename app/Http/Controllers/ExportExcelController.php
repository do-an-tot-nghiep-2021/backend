<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use Illuminate\Http\Request;

class ExportExcelController extends Controller
{
    public function export(Request $request)
    {
        return (new OrderExport($request->date))->download('order.xlsx');
    }
}
