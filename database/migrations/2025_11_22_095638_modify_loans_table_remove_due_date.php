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
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('loans', 'loan_date')) {
                $table->dropColumn('loan_date');
            }
            if (Schema::hasColumn('loans', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('loans', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('loans', 'paid_amount')) {
                $table->dropColumn('paid_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->date('due_date')->nullable();
            $table->date('loan_date');
            $table->text('description')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
        });
    }
};
