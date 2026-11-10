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
        Schema::table('payments', function (Blueprint $table) {

            $table->string('receipt_number')
                ->unique()
                ->after('transaction_reference');

            $table->string('status')
                ->default('pending')
                ->after('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropUnique([
                'receipt_number'
            ]);

            $table->dropColumn([
                'receipt_number',
                'status',
            ]);
        });
    }
};