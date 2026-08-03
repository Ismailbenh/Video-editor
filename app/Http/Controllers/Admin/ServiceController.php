<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Services', [
            'services' => Service::orderBy('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:30'],
            'active' => ['boolean'],
            'tiers' => ['required', 'array'],
            'tiers.*.name' => ['required', 'string'],
            'tiers.*.price' => ['required', 'numeric'],
            'tiers.*.turnaround' => ['nullable', 'string'],
            'tiers.*.features' => ['nullable', 'array'],
        ]);

        $data['slug'] = Str::slug($data['name']).'-'.Str::random(5);
        $data['category'] = 'youtube';
        $data['icon'] = $data['icon'] ?? '';
        $data['active'] = $data['active'] ?? true;

        Service::create($data);

        return redirect()->back();
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:30'],
            'active' => ['boolean'],
            'tiers' => ['required', 'array'],
            'tiers.*.name' => ['required', 'string'],
            'tiers.*.price' => ['required', 'numeric'],
            'tiers.*.turnaround' => ['nullable', 'string'],
            'tiers.*.features' => ['nullable', 'array'],
        ]);

        $service->update($data);

        return redirect()->back();
    }

    public function toggle(Service $service): RedirectResponse
    {
        $service->update(['active' => ! $service->active]);

        return redirect()->back();
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->back();
    }
}