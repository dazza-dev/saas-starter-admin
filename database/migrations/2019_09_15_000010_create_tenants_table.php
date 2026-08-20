<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            // El id es el identificador del tenant y da nombre a su base de datos.
            $table->string('id')->primary();
            // Nombre de la organización cliente.
            $table->string('name', 191);
            // Identificación fiscal, opcional.
            $table->string('tax_id', 191)->nullable();
            // Persona de contacto, que es también el usuario administrador que se crea al alta.
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->string('email', 191)->nullable()->unique();
            $table->string('phone', 191)->nullable();
            $table->unsignedBigInteger('tenant_type_id')->nullable()->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
