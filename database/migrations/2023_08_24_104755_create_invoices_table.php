<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Domain\Payment\Enums\InvoiceStatus;
use Domain\Shared\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();          
            $table->char('status', 10)->default(InvoiceStatus::Pending->value);
            # Theses two are for the sake of performance optimization and are technical redundancy.
            $table->unsignedDecimal('total_price', 10, 2);
            $table->unsignedInteger('total_items');

            $table->string('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
