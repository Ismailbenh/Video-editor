<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortfolioItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Portfolio', [
            'portfolioItems' => PortfolioItem::orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:youtube,reels,batch,clipping'],
            'client' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'string', 'max:7'],
        ];

        $rules['thumbnail'] = $request->hasFile('thumbnail')
            ? ['nullable', 'image', 'max:2048']
            : ['nullable', 'string', 'max:2048'];

        $data = $request->validate($rules);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('portfolio', 'public');
        }

        PortfolioItem::create($data);

        return redirect()->back();
    }

    public function update(Request $request, PortfolioItem $portfolioItem): RedirectResponse
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:youtube,reels,batch,clipping'],
            'client' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'string', 'max:7'],
        ];

        $rules['thumbnail'] = $request->hasFile('thumbnail')
            ? ['nullable', 'image', 'max:2048']
            : ['nullable', 'string', 'max:2048'];

        $data = $request->validate($rules);

        if ($request->hasFile('thumbnail')) {
            if ($portfolioItem->thumbnail && Storage::disk('public')->exists($portfolioItem->thumbnail)) {
                Storage::disk('public')->delete($portfolioItem->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('portfolio', 'public');
        }

        $portfolioItem->update($data);

        return redirect()->back();
    }

    public function destroy(PortfolioItem $portfolioItem): RedirectResponse
    {
        $portfolioItem->delete();

        return redirect()->back();
    }
}