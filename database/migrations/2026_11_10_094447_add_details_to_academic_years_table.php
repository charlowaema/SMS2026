
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->boolean('is_active')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {

            $table->dropColumn([
                'start_date',
                'end_date',
                'is_active'
            ]);

        });
    }
};
