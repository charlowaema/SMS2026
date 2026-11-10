
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semesters', function (Blueprint $table) {

            $table->foreignId('academic_year_id')
                  ->nullable()
                  ->constrained('academic_years')
                  ->nullOnDelete();

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->boolean('is_active')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {

            $table->dropConstrainedForeignId('academic_year_id');

            $table->dropColumn([
                'start_date',
                'end_date',
                'is_active'
            ]);

        });
    }
};
