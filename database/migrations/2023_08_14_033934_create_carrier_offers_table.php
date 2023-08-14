<?php

use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('carrier_offers', function (Blueprint $table) {
            $table->foreignIdFor(Quote::class, 'quote_id')->constrained();
            $table->string('name')->index();
            $table->string('service')->index();
            $table->timestamp('estimated_date');
            $table->timestamp('expiration');
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier_offers');
    }
};
