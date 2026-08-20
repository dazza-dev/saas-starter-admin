<?php

namespace App\Jobs;

use App\Models\Tenant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenantAdminUser implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Tenant $tenant
    ) {}

    /**
     * Crea el usuario administrador dentro de la base de datos del tenant recién provisionado.
     */
    public function handle(): void
    {
        $this->tenant->run(function () {
            $role = DB::table('roles')->where('name', 'admin')->first();

            if (! $role) {
                return;
            }

            $userId = DB::table('users')->insertGetId([
                'uuid' => (string) Str::uuid(),
                'first_name' => $this->tenant->first_name ?: $this->tenant->name,
                'last_name' => $this->tenant->last_name,
                'email' => $this->tenant->email,
                'phone' => $this->tenant->phone,
                'username' => $this->tenant->email,
                'password' => Hash::make($this->tenant->password),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('role_user')->insert([
                'user_id' => $userId,
                'role_id' => $role->id,
            ]);
        });

        $this->clearSensitiveData();
    }

    /**
     * Borra la contraseña en claro del atributo `data` del tenant en cuanto deja de hacer falta.
     */
    private function clearSensitiveData(): void
    {
        unset($this->tenant->password);
        $this->tenant->save();
    }
}
