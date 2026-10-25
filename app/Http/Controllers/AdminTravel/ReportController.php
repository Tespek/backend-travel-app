<?php

namespace App\Http\Controllers\AdminTravel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    private function operatorId(): int
    {
        return auth()->user()->operator_id;
    }

    /**
     * Tampilkan laporan keuangan / pendapatan penjualan tiket.
     */
    public function index(Request $request)
    {
        $operatorId = $this->operatorId();
        $period     = $request->query('period', 'monthly'); // weekly | monthly | yearly
        $year       = (int) $request->query('year', now()->year);
        $month      = (int) $request->query('month', now()->month);

        // ── Base query (hanya tiket lunas milik operator ini) ──────────────────
        $baseQuery = fn () => Booking::where('operator_id', $operatorId)
            ->where('payment_status', 'paid');

        // ─────────────────────────────────────────────────────────────────────
        // SUMMARY CARDS  (all-time keseluruhan, lalu per periode yang dipilih)
        // ─────────────────────────────────────────────────────────────────────
        $totalRevenueAllTime  = ($baseQuery)()->sum('total_price');
        $totalTicketsAllTime  = ($baseQuery)()->count();

        // ── KPI periode yang sedang dilihat ───────────────────────────────────
        [$periodStart, $periodEnd] = $this->periodRange($period, $year, $month);

        $periodQuery = ($baseQuery)()
            ->whereBetween('paid_at', [$periodStart, $periodEnd]);

        $periodRevenue = (clone $periodQuery)->sum('total_price');
        $periodTickets = (clone $periodQuery)->count();
        $periodAvgTicket = $periodTickets > 0
            ? round($periodRevenue / $periodTickets)
            : 0;

        // ── Perbandingan dengan periode sebelumnya (untuk badge tren) ─────────
        [$prevStart, $prevEnd] = $this->previousPeriodRange($period, $year, $month);

        $prevRevenue = ($baseQuery)()
            ->whereBetween('paid_at', [$prevStart, $prevEnd])
            ->sum('total_price');

        $revenueGrowth = $prevRevenue > 0
            ? round((($periodRevenue - $prevRevenue) / $prevRevenue) * 100, 1)
            : null;

        // ─────────────────────────────────────────────────────────────────────
        // CHART DATA
        // ─────────────────────────────────────────────────────────────────────
        [$chartLabels, $chartRevenues, $chartTickets] = $this->buildChartData(
            $period, $year, $month, $operatorId
        );

        // ─────────────────────────────────────────────────────────────────────
        // TABEL RINCIAN TRANSAKSI (paginated)
        // ─────────────────────────────────────────────────────────────────────
        $transactions = (clone $periodQuery)
            ->with(['schedule.route.originCity', 'schedule.route.destinationCity'])
            ->orderByDesc('paid_at')
            ->paginate(15)
            ->withQueryString();

        // ─────────────────────────────────────────────────────────────────────
        // PENDAPATAN PER RUTE (top-routes breakdown)
        // ─────────────────────────────────────────────────────────────────────
        $topRoutes = (clone $periodQuery)
            ->with(['schedule.route.originCity', 'schedule.route.destinationCity'])
            ->get()
            ->groupBy(fn ($b) => optional(optional(optional($b->schedule)->route)->originCity)->name
                . ' → '
                . optional(optional(optional($b->schedule)->route)->destinationCity)->name)
            ->map(fn ($group) => [
                'route'   => $group->first(),
                'tickets' => $group->count(),
                'revenue' => $group->sum('total_price'),
            ])
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        // Available years for the year selector (starting from first booking)
        $firstBookingYear = Booking::where('operator_id', $operatorId)
            ->where('payment_status', 'paid')
            ->min(\Illuminate\Support\Facades\DB::raw('YEAR(paid_at)'));
        $availableYears = range($firstBookingYear ?: now()->year, now()->year);

        return view('admin-travel.reports.index', compact(
            'period',
            'year',
            'month',
            'totalRevenueAllTime',
            'totalTicketsAllTime',
            'periodRevenue',
            'periodTickets',
            'periodAvgTicket',
            'revenueGrowth',
            'prevRevenue',
            'chartLabels',
            'chartRevenues',
            'chartTickets',
            'transactions',
            'topRoutes',
            'periodStart',
            'periodEnd',
            'availableYears',
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Rentang waktu awal & akhir sesuai periode.
     *
     * @return array{Carbon, Carbon}
     */
    private function periodRange(string $period, int $year, int $month): array
    {
        return match ($period) {
            'weekly'  => [now()->startOfWeek(), now()->endOfWeek()],
            'yearly'  => [Carbon::create($year)->startOfYear(), Carbon::create($year)->endOfYear()],
            default   => [ // monthly
                Carbon::create($year, $month)->startOfMonth(),
                Carbon::create($year, $month)->endOfMonth(),
            ],
        };
    }

    /**
     * Rentang waktu periode SEBELUMNYA (untuk perbandingan tren).
     *
     * @return array{Carbon, Carbon}
     */
    private function previousPeriodRange(string $period, int $year, int $month): array
    {
        return match ($period) {
            'weekly'  => [
                now()->subWeek()->startOfWeek(),
                now()->subWeek()->endOfWeek(),
            ],
            'yearly'  => [
                Carbon::create($year - 1)->startOfYear(),
                Carbon::create($year - 1)->endOfYear(),
            ],
            default   => [ // monthly
                Carbon::create($year, $month)->subMonth()->startOfMonth(),
                Carbon::create($year, $month)->subMonth()->endOfMonth(),
            ],
        };
    }

    /**
     * Bangun label & data chart sesuai periode.
     *
     * @return array{string[], float[], int[]}
     */
    private function buildChartData(string $period, int $year, int $month, int $operatorId): array
    {
        $labels   = [];
        $revenues = [];
        $tickets  = [];

        $base = fn () => Booking::where('operator_id', $operatorId)->where('payment_status', 'paid');

        if ($period === 'weekly') {
            // Tampilkan 7 hari (Senin s.d. Minggu minggu ini)
            $startOfWeek = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $day        = $startOfWeek->copy()->addDays($i);
                $labels[]   = $day->isoFormat('ddd, D MMM');
                $dayBookings = ($base)()->whereDate('paid_at', $day)->get();
                $revenues[] = (float) $dayBookings->sum('total_price');
                $tickets[]  = $dayBookings->count();
            }
        } elseif ($period === 'monthly') {
            // Tampilkan setiap hari dalam bulan yang dipilih
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $day        = Carbon::create($year, $month, $d);
                $labels[]   = (string) $d;
                $dayBookings = ($base)()->whereDate('paid_at', $day)->get();
                $revenues[] = (float) $dayBookings->sum('total_price');
                $tickets[]  = $dayBookings->count();
            }
        } else {
            // yearly → 12 bulan
            for ($m = 1; $m <= 12; $m++) {
                $dt         = Carbon::create($year, $m);
                $labels[]   = $dt->isoFormat('MMM');
                $monthBookings = ($base)()
                    ->whereYear('paid_at', $year)
                    ->whereMonth('paid_at', $m)
                    ->get();
                $revenues[] = (float) $monthBookings->sum('total_price');
                $tickets[]  = $monthBookings->count();
            }
        }

        return [$labels, $revenues, $tickets];
    }
}
