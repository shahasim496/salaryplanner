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
        Schema::create('daily_spendings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_plan_id')->constrained('salary_plans')->onDelete('cascade');
            $table->string('category', 50)->comment('Category from plan expenses');
            $table->decimal('amount', 10, 2);
            $table->date('spending_date');
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->index('salary_plan_id');
            $table->index('category');
            $table->index('spending_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_spendings');
    }
};
