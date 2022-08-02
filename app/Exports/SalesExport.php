<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SalesExport implements FromView
{
    public function __construct(protected string $start_date, protected string $end_date)
    {
    }

    public function view(): View
    {
        return view('exports.sales', [
            'sales'     => Transaction::with('cashier', 'customer')->whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->get(),
            'total'     => Transaction::whereDate('created_at', '>=', $this->start_date)->whereDate('created_at', '<=', $this->end_date)->sum('grand_total')
        ]);
    }
}
