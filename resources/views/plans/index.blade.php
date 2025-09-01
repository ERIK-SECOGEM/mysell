<x-app-layout>
    <div class="max-w-md mx-auto mt-16 bg-white rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
            Suscríbete a nuestro plan
        </h2>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form id="subscription-form" action="{{ route('subscription.create') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="payment_method" id="payment_method">

            {{-- Campo tarjeta con Stripe Elements --}}
            <div id="card-element" class="p-3 border border-gray-300 rounded-xl"></div>
            <div id="card-errors" class="text-red-500 text-sm mt-2"></div>

            {{-- Botón --}}
            <button id="card-button"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-200 ease-in-out">
                Suscribirme
            </button>
        </form>
    </div>

    {{-- Stripe JS --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('cashier.key') }}");
        const elements = stripe.elements();

        const cardElement = elements.create('card', {
            classes: {
                base: 'text-gray-700',
            }
        });
        cardElement.mount('#card-element');

        const form = document.getElementById('subscription-form');
        const cardButton = document.getElementById('card-button');
        const paymentMethodInput = document.getElementById('payment_method');
        const errorDiv = document.getElementById('card-errors');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            cardButton.disabled = true;
            cardButton.innerText = 'Procesando...';

            const {paymentMethod, error} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                errorDiv.textContent = error.message;
                cardButton.disabled = false;
                cardButton.innerText = 'Suscribirme';
            } else {
                paymentMethodInput.value = paymentMethod.id;
                form.submit();
            }
        });
    </script>
</x-app-layout>
