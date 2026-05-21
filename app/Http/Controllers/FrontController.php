<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Kostum;

class FrontController extends Controller
{
    public function index()
    {
        $galeris = Galeri::where('status_aktif', true)
            ->orderBy('urutan')
            ->latest()
            ->get();
        $kostums = Kostum::where('status', 'tersedia')->get();

        return view('front.welcome', compact('galeris', 'kostums'));
    }
}
