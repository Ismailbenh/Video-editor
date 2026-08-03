<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    /**
     * Only orders that have actually been paid for are real work - unpaid
     * checkout attempts never make it into the editor's queue.
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Orders', [
            'orders' => Order::where('payment_status', 'paid')
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:New,In Progress,Review,Delivered,Paid'],
        ]);

        $order->update($data);

        return redirect()->back();
    }

    public function updateNotes(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $order->update($data);

        return redirect()->back();
    }

    /**
     * Accepting an order moves it out of the "New" queue and, the first
     * time this client is accepted, creates their Client record. If they've
     * ordered before, this just leaves their existing record alone - their
     * job count / total paid / last active are all computed live from their
     * orders, not stored here, so there's nothing to keep in sync.
     */
    public function accept(Order $order): RedirectResponse
    {
        Client::firstOrCreate(
            ['email' => $order->client_email],
            ['name' => $order->client_name, 'status' => 'Ongoing']
        );

        if ($order->status === 'New') {
            $order->update(['status' => 'In Progress']);
        }

        return redirect()->back();
    }
}