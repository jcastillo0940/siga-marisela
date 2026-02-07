<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class FixEnrollmentBalances extends Command
{
    /**
     * El nombre y firma del comando.
     */
    protected $signature = 'enrollments:fix-balances';

    /**
     * La descripción del comando.
     */
    protected $description = 'Sincroniza los totales de PaymentPlan con el final_price de Enrollment';

    /**
     * Ejecuta el comando.
     */
    public function handle()
    {
        $this->info('Iniciando corrección de balances...');

        $enrollments = Enrollment::whereHas('paymentPlan')->get();
        $count = 0;

        foreach ($enrollments as $enrollment) {
            $plan = $enrollment->paymentPlan;
            $realTotal = $enrollment->final_price;

            // Verificamos si hay discrepancia
            if (abs($plan->total_amount - $realTotal) > 0.01) {
                $this->warn("Corrigiendo ID {$enrollment->id}: Plan era \${$plan->total_amount}, ahora será \${$realTotal}");

                DB::transaction(function () use ($plan, $realTotal) {
                    // Actualizamos el monto total del plan
                    $plan->total_amount = $realTotal;
                    $plan->save();

                    // Forzamos el recálculo del balance basado en los pagos existentes
                    // Esto usa el método updateBalance() de tu modelo PaymentPlan
                    $plan->updateBalance(); 
                });

                $count++;
            }
        }

        $this->info("Proceso terminado. Se corrigieron {$count} registros.");
    }
}