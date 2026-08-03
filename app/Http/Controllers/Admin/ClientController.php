<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    /**
     * jobCount / totalPaid / lastActive are computed live from each
     * client's orders rather than stored on the client row - storing them
     * separately would just be another copy that can drift out of sync,
     * same mistake we already fixed once for services/portfolio.
     */
    public function index(): Response
    {
        $clients = Client::orderBy('name')->get()->map(function (Client $client) {
            $paidOrders = Order::where('client_email', $client->email)
                ->where('payment_status', 'paid');

            return [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'status' => $client->status,
                'jobCount' => (clone $paidOrders)->count(),
                'totalPaid' => (int) (clone $paidOrders)->sum('price'),
                'lastActive' => optional((clone $paidOrders)->latest('created_at')->first())
                    ->created_at?->format('Y-m-d') ?? $client->updated_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Admin/Clients', [
            'clients' => $clients,
            'orders' => Order::where('payment_status', 'paid')
                ->orderByDesc('created_at')
                ->get()
                ->map(fn (Order $order) => [
                    'id' => $order->id,
                    'clientEmail' => $order->client_email,
                    'service' => $order->service_name,
                    'tier' => $order->tier_name,
                    'price' => $order->price,
                    'status' => $order->status,
                ]),
        ]);
    }
}