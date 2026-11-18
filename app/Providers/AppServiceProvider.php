<?php

namespace App\Providers;

use App\Models\BatchObat;
use App\Models\Obat;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('backend.layout.app', function ($view) {
            // 1. Stok Kritis (50% dari stok maksimal)
            $stokKritis = Obat::whereRaw('stok <= (stok_maksimal * 0.50)')
                ->orderBy('stok')
                ->take(5)
                ->get();

            // 2. Prioritas Tinggi (SAW >= 0.75)
            $prioritasTinggi = Obat::where('nilai_saw', '>=', 0.75)
                ->orderByDesc('nilai_saw')
                ->take(5)
                ->get();
            
            $batchExpired = BatchObat::with('obat')
                ->where('stok', '>', 0)
                ->where('expired_date', '<', now()->format('Y-m-d'))
                ->orderBy('expired_date', 'asc')
                ->get();

            $view->with([
                'totalNotif' => $stokKritis->count() + $prioritasTinggi->count() + $batchExpired->count(),
                'stokKritis' => $stokKritis,
                'prioritasTinggi' => $prioritasTinggi,
                'batchExpired' => $batchExpired,
            ]);
        });
    }
}
