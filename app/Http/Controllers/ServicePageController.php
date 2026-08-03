<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Inertia\Inertia;
use Inertia\Response;

class ServicePageController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('ServicesPage', [
            'services' => Service::where('active', true)->orderBy('id')->get(),
        ]);
    }
}