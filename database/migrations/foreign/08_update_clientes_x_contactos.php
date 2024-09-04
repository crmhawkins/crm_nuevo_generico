<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clients_x_contacts', function (Blueprint $table) {

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('contact_id')->references('id')->on('contacts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients_x_contacts');

    }
};