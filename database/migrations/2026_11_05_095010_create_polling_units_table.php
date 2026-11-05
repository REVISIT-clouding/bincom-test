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
        Schema::create('polling_unit', function (Blueprint $table) {
            $table->id('polling_unit_id');
            $table->uuid()->unique();
            $table->foreignId('lga_id')->constrained()->onDelete('cascade');
            $table->foreignId('ward_id')->constrained()->onDelete('cascade');
            $table->foreignId('uniquewardid')->constrained()->onDelete('cascade');
            $table->string('polling_unit_number', 50);
            $table->string('polling_unit_name', 50);
            $table->string('polling_unit_description')->nullable();
            $table->decimal('lat', 225);
            $table->decimal('long', 225);
            $table->string('entered_by_user', 50)->nullable();
            $table->string('user_ip_address', 50);
            $table->timestamp('date_entered');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polling_units');
    }
};
