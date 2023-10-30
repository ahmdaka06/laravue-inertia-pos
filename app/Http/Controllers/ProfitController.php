<?php

namespace App\Http\Controllers;

use App\Exports\ProfitsExport;
use Inertia\Inertia;
use App\Models\Profit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProfitController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Apps/Profits/Index');
    }

    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        $this->validate($request, [
            'start_date'  => 'required',
            'end_date'    => 'required',
        ]);

        //get data profits by range date
        $profits = Profit::with('transaction')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total profit by range date
        $total = Profit::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('total');

        return Inertia::render('Apps/Profits/Index', [
            'profits'   => $profits,
            'total'     => (int) $total
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProfitsExport($request->start_date, $request->end_date), 'profits : '.$request->start_date.' — '.$request->end_date.'.xlsx');
    }

    public function pdf(Request $request)
    {
        //get data proftis by range date
        $profits = Profit::with('transaction')->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->get();

        //get total profit by range date
        $total = Profit::whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date)->sum('total');

        //load view PDF with data
        $pdf = Pdf::loadView('exports.profits', compact('profits', 'total'));

        //download PDF
        return $pdf->download('profits : '.$request->start_date.' — '.$request->end_date.'.pdf');
    }
}
