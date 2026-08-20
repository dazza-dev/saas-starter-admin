<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class UpdateTenantSettings implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Tenant $tenant
    ) {}

    /**
     * Copia a la tabla `settings` del tenant los datos que este panel ya conoce.
     */
    public function handle(): void
    {
        $this->tenant->run(function () {
            $settings = [
                'app_name' => $this->tenant->name,
                'email' => $this->tenant->email,
                'notification_email' => $this->tenant->email,
            ];

            foreach ($settings as $name => $value) {
                DB::table('settings')
                    ->where('name', $name)
                    ->update(['value' => $value]);
            }
        });
    }
}
