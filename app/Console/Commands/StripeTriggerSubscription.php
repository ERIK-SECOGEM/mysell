<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class StripeTriggerSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * php artisan stripe:trigger-subscription {user_id}
     */
    protected $signature = 'stripe:trigger-subscription {user_id}';

    /**
     * The console command description.
     */
    protected $description = 'Dispara un evento de Stripe (customer.subscription.created) usando el stripe_id del usuario.';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("❌ Usuario con id {$userId} no encontrado.");
            return 1;
        }

        if (!$user->stripe_id) {
            $this->error("❌ El usuario {$user->id} no tiene stripe_id.");
            return 1;
        }

        $customer = $user->stripe_id;

        $this->info("🔄 Disparando evento para customer {$customer}...");

        // Comando Stripe CLI
        $cmd = "stripe trigger customer.subscription.created --override customer_subscription:customer={$customer}";

        exec($cmd, $output, $resultCode);

        foreach ($output as $line) {
            $this->line($line);
        }

        if ($resultCode !== 0) {
            $this->error("❌ Error ejecutando Stripe CLI.");
            return 1;
        }

        $this->info("✅ Evento enviado correctamente.");
        return 0;
    }
}
