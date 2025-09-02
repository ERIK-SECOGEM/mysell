<h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
    {{ __('Gestión de suscripción') }}
</h3>

{{-- Mensajes --}}
@if (session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 text-sm">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 p-3 rounded-lg bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 text-sm">
        {{ session('error') }}
    </div>
@endif

@php
    $subscription = Auth::user()->subscription('default');
@endphp

@if (Auth::user()->subscribed('default'))
    @if ($subscription->onGracePeriod())
        <p class="text-yellow-600 dark:text-yellow-400 mb-4">
            Tu suscripción fue cancelada, pero aún tienes acceso hasta:
            <strong>{{ $subscription->ends_at->toFormattedDateString() }}</strong>
        </p>

        <form action="{{ route('subscription.resume') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-xl transition">
                Reactivar suscripción
            </button>
        </form>
    @else
        <p class="text-green-600 dark:text-green-400 mb-4">
            Actualmente tienes una suscripción activa.
        </p>

        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('subscription.cancel') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                    class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-xl transition">
                    Cancelar al final del periodo
                </button>
            </form>

            <form action="{{ route('subscription.cancel.now') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                    class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-xl transition">
                    Cancelar inmediatamente
                </button>
            </form>
        </div>
    @endif
@else
    <p class="text-gray-600 dark:text-gray-400 mb-4">
        No tienes una suscripción activa.
    </p>

    <a href="{{ route('plans.index') }}"
        class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl transition">
        Suscribirme ahora
    </a>
@endif