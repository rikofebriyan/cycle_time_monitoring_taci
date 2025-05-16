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
        Schema::create('02_crimping_connector_ct', function (Blueprint $table) {
            $table->id(); // Creates the 'id' column as an auto-incrementing bigint
            $table->timestamp('TIMESTAMP')->nullable(); // Creates the 'TIMESTAMP' column
            $table->string('JIG_NUMBER', 50)->nullable(); // Creates the 'JIG_NUMBER' column
            $table->string('MODEL', 50)->nullable(); // Creates the 'MODEL' column
            $table->integer('CT')->nullable(); // Creates the 'CT' column

            $table->primary('id'); // Setting 'id' as the primary key
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('02_crimping_connector_ct');
    }
};
