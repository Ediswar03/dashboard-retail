<?php

namespace App\Http\Controllers;

use App\Models\RetailTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Top 15 negara saja untuk dropdown (38 negara total, hindari dropdown kepanjangan)
        $countries = RetailTransaction::select('country')
            ->groupBy('country')
            ->orderByRaw('SUM(total_price) DESC')
            ->limit(15)
            ->pluck('country');

        $minDate = RetailTransaction::min('invoice_date');
        $maxDate = RetailTransaction::max('invoice_date');

        return view('dashboard', [
            'countries' => $countries,
            'minDate' => $minDate ? substr($minDate, 0, 10) : null,
            'maxDate' => $maxDate ? substr($maxDate, 0, 10) : null,
        ]);
    }

    /**
     * Endpoint AJAX: data agregat sesuai filter aktif.
     * Semua agregasi dilakukan di level SQL (bukan diambil mentah ke PHP)
     * agar tetap responsif meskipun tabel berisi 500rb+ baris.
     */
    public function data(Request $request)
    {
        $query = RetailTransaction::query();

        if ($request->filled('country') && $request->country !== 'all') {
            $query->where('country', $request->country);
        }
        if ($request->filled('region') && $request->region !== 'all') {
            if ($request->region === 'uk') {
                $query->where('country', 'United Kingdom');
            } elseif ($request->region === 'intl') {
                $query->where('country', '!=', 'United Kingdom');
            }
        }
        if ($request->filled('start_date')) {
            $query->whereDate('invoice_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('invoice_date', '<=', $request->end_date);
        }
        if ($request->filled('search_product')) {
            $query->where('description', 'like', '%' . $request->search_product . '%');
        }

        $base = $query->clone();

        $summary = [
            'total_revenue' => (float) $base->clone()->sum('total_price'),
            'total_invoices' => $base->clone()->distinct('invoice_no')->count('invoice_no'),
            'total_customers' => $base->clone()->distinct('customer_id')->count('customer_id'),
            'total_products' => $base->clone()->distinct('stock_code')->count('stock_code'),
            'total_countries' => $base->clone()->distinct('country')->count('country'),
            'total_units' => (int) $base->clone()->sum('quantity'),
        ];
        $summary['avg_order'] = $summary['total_invoices'] > 0 ? $summary['total_revenue'] / $summary['total_invoices'] : 0;
        $returns = $base->clone()->where('quantity', '<', 0)->count();
        $total_rows = $base->clone()->count();
        $summary['return_rate'] = $total_rows > 0 ? round(($returns / $total_rows) * 100, 2) : 0;

        $monthlyRaw = $base->clone()
            ->select(
                DB::raw("DATE_FORMAT(invoice_date, '%Y-%m') as p"),
                DB::raw('SUM(total_price) as rev'),
                DB::raw('COUNT(DISTINCT invoice_no) as ord'),
                DB::raw('SUM(quantity) as units')
            )
            ->groupBy('p')
            ->orderBy('p')
            ->get();
            
        $monthly = [];
        $prevRev = 0;
        foreach($monthlyRaw as $m) {
            $g = $prevRev > 0 ? (($m->rev - $prevRev) / $prevRev) * 100 : 0;
            $monthly[] = [
                'p' => $m->p,
                'rev' => (float)$m->rev,
                'ord' => (int)$m->ord,
                'units' => (int)$m->units,
                'g' => round($g, 2)
            ];
            $prevRev = $m->rev;
        }

        $countries = $base->clone()
            ->select('country as c', DB::raw('SUM(total_price) as rev'), DB::raw('COUNT(DISTINCT invoice_no) as ord'))
            ->groupBy('c')
            ->orderByDesc('rev')
            ->get();

        $products = $base->clone()
            ->select('description as n', DB::raw('SUM(total_price) as rev'), DB::raw('SUM(quantity) as qty'), DB::raw('COUNT(DISTINCT invoice_no) as ord'))
            ->groupBy('n')
            ->orderByDesc('rev')
            ->limit(20)
            ->get();

        $hourly = $base->clone()
            ->select(DB::raw('HOUR(invoice_date) as h'), DB::raw('COUNT(DISTINCT invoice_no) as ord'))
            ->groupBy('h')
            ->orderBy('h')
            ->get();

        $dow = $base->clone()
            ->select(DB::raw('DAYOFWEEK(invoice_date) as dow_idx'), DB::raw('DAYNAME(invoice_date) as d'), DB::raw('SUM(total_price) as rev'))
            ->groupBy('dow_idx', 'd')
            ->orderBy('dow_idx')
            ->get()->map(function($item) {
                return ['d' => substr($item->d, 0, 3), 'rev' => (float)$item->rev];
            });

        return response()->json([
            'summary' => $summary,
            'monthly' => $monthly,
            'countries' => $countries,
            'products' => $products,
            'hourly' => $hourly,
            'dow' => $dow,
        ]);
    }
}
