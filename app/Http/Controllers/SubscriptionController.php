<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        return view('plans.index');
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        $paymentMethodId = $request->payment_method;

        // Asignar método de pago como default
        $user->updateDefaultPaymentMethod($paymentMethodId);

        // Crear suscripción
        $subscription = $user->newSubscription('default', 'price_1S0lrRKcd3oTru9Y6ADbb2Wf') // <-- tu price real de Stripe
            ->create($paymentMethodId);

        return redirect()->route('dashboard')
            ->with('success', 'Suscripción creada correctamente.');
    }

    public function cancel()
    {
        $user = Auth::user();

        $subscription = $user->subscription('default');

        if (!$subscription) {
            return back()->with('error', 'No tienes una suscripción activa.');
        }

        // Cancela al final del ciclo actual
        $subscription->cancel();

        return back()->with('success', 'Tu suscripción se canceló. Seguirás teniendo acceso hasta el final del periodo.');
    }

    public function cancelNow()
    {
        $user = Auth::user();

        $subscription = $user->subscription('default');

        if (!$subscription) {
            return back()->with('error', 'No tienes una suscripción activa.');
        }

        // Cancela inmediatamente
        $subscription->cancelNow();

        return back()->with('success', 'Tu suscripción fue cancelada inmediatamente.');
    }

    public function resume()
    {
        $user = Auth::user();

        $subscription = $user->subscription('default');

        if (!$subscription) {
            return back()->with('error', 'No tienes una suscripción para reactivar.');
        }

        if (!$subscription->onGracePeriod()) {
            return back()->with('error', 'Tu suscripción no está en periodo de gracia.');
        }

        $subscription->resume();

        return back()->with('success', 'Tu suscripción fue reactivada correctamente.');
    }
}
