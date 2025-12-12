<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kostum;

class FrontController extends Controller
{
    public function index()
{
    $kostums = Kostum::where('status', 'tersedia')->get();
    return view('front.welcome', compact('kostums'));
}
}
