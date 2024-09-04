<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function form(Request $request): View
    {
        $data = $request->validate([
            'ip' => 'nullable|ip',
            'city' => 'nullable|string|max:255',
            'visit_time' => 'nullable|date',
        ]);

        // Создание записи в базе данных
        $visit = Visit::create($data);


        return view('dashboard', [
            'user' => 'vadim',
        ]);
    }

}
