<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class OrderController extends Controller
{
    /**
     * Show the order/checkout page.
     */
    public function index(): Response
    {
        return Inertia::render('Order', [
            'services' => Service::where('active', true)->orderBy('id')->get(),
        ]);
    }

    /**
     * Create the order (unpaid) and redirect the browser to a Stripe-hosted
     * Checkout page to collect payment. We never touch raw card details
     * ourselves - that's a PCI-compliance requirement, not a preference.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'tier_name' => ['required', 'string', 'max:100'],
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'deadline' => ['nullable', 'date'],
            'file_link' => ['nullable', 'string', 'max:500'],
        ]);

        $service = Service::findOrFail($data['service_id']);

        // Re-derive the price server-side from the service's own tier data so a
        // tampered client request can't check out at an arbitrary price.
        $tier = collect($service->tiers)->firstWhere('name', $data['tier_name']);
        abort_if(! $tier, 422, 'Invalid tier selected.');

        $order = Order::create([
            'user_id' => auth()->id(),
            'service_id' => $service->id,
            'service_name' => $service->name,
            'tier_name' => $tier['name'],
            'price' => $tier['price'],
            'client_name' => $data['client_name'],
            'client_email' => $data['client_email'],
            'description' => $data['description'],
            'deadline' => $data['deadline'] ?? null,
            'file_link' => $data['file_link'] ?? null,
            'status' => 'New',
            'payment_status' => 'pending',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'customer_email' => $order->client_email,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => (int) round($order->price * 100),
                    'product_data' => [
                        'name' => "{$order->service_name} - {$order->tier_name}",
                    ],
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('order.success', $order).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('order').'?canceled=1',
        ]);

        $order->update(['stripe_session_id' => $session->id]);

        // Full browser redirect to Stripe's own domain - Inertia::location()
        // forces this instead of an XHR-based Inertia visit.
        return Inertia::location($session->url);
    }

    /**
     * Stripe redirects the customer back here after checkout. We verify the
     * session server-side with Stripe (never trust the redirect alone)
     * before marking the order paid and letting it appear for the editor.
     */
    public function success(Request $request, Order $order): Response
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $sessionId = $request->query('session_id');

        if ($sessionId && $sessionId === $order->stripe_session_id && $order->payment_status !== 'paid') {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $order->update(['payment_status' => 'paid']);
            }
        }

        return Inertia::render('Order', [
            'services' => Service::where('active', true)->orderBy('id')->get(),
            'confirmedOrder' => $order->payment_status === 'paid' ? $order->fresh() : null,
        ]);
    }
}
