<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioPageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('PortfolioPage', [
            'portfolioItems' => PortfolioItem::orderByDesc('id')->get(),
        ]);
    }
}