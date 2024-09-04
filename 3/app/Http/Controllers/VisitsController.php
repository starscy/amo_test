<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VisitsController extends Controller
{
    public function getVisitsData()
    {
        // Получение данных для графиков
        $visits = Visit::selectRaw('COUNT(*) as count, HOUR(visit_time) as hour')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Получение данных для круговой диаграммы
        $cities = Visit::selectRaw('COUNT(*) as count, city')
            ->groupBy('city')
            ->get();

        return response()->json([
            'visits' => $visits,
            'cities' => $cities,
        ]);
    }
}
