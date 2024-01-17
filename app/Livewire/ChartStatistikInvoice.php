<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ChartStatistikInvoice extends Component
{
    public function render()
    {
        return view('livewire.chart-statistik-invoice', [
            'income' => $this->incomeInvoice(),
            'countInvoice' => $this->countInvoice()
        ]);
    }

    public function incomeInvoice(){

        $income = Cache::get('chart-invoice-income');

        if(!$income){
            $invoices = \App\Models\invoice\Invoice::with('invoiceCost')
            ->whereBetween('created_at', [date('Y-m-d', strtotime(now()->startOfYear())), date('Y-m-d', strtotime(now()->endOfYear()))])
            ->where('status', '!=', 'batal')
            ->whereHas('invoiceCost', function($query){
                $query->where('status', '!=', 'belum bayar');
            })
            ->get();

            $invoices = $invoices->groupBy(function($invoice){
                return \Carbon\Carbon::parse($invoice->created_at)->format('F');
            });

            // income
            $income = $invoices->map(function($invoiceByMouth){
                return $invoiceByMouth->sum(function($invoice){
                    return $invoice->invoiceCost->biaya_total;
                });
            })->toArray();

            Cache::put('chart-invoice-income', $income, 5);
        }
        
        return [
            "income" => array_values($income),
        ];
    }

    public function countInvoice(){

        $invoices = \App\Models\invoice\Invoice::whereBetween('created_at', [date('Y-m-d', strtotime(now()->startOfYear())), date('Y-m-d', strtotime(now()->endOfYear()))])->get();

        $countAllMonth = $invoices->groupBy(function($invoice){
            return \Carbon\Carbon::parse($invoice->created_at)->format('n');
        })->map(function($invoice){
            return $invoice->count();
        })
        ->toArray();
        $countAllMonth = $this->fillDataMonth($countAllMonth);

        $countThisDay = $invoices->filter(function ($invoice) {
            return \Carbon\Carbon::parse($invoice->created_at)->isSameDay(now());
        })->count();

        $countThisMonth = $countAllMonth[date('n', strtotime(now()))];

        return [
            'count-month' => $countThisMonth,
            'count-today' => $countThisDay,
            'count-all-month' => [
                'total' => array_values($countAllMonth)
            ]
        ];
    }

    private function fillDataMonth(array $datas): array
    {
        $month = [];
        for($i = 1; $i <= 12; $i++){
            isset($datas[$i]) ? $month[$i] = $datas[$i] : $month[$i] = null;
        }

        return $month;
    }
}
