<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home', [
            'services' => Service::where('active', true)->orderBy('id')->get(),
            'portfolioItems' => PortfolioItem::orderByDesc('id')->get(),
        ]);
    }
}