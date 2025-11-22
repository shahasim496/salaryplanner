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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_plan_id')->constrained('salary_plans')->onDelete('cascade');
            $table->string('expense_name', 100);
            $table->string('category', 50)->comment('Food, Transport, Bills, Entertainment, Shopping, Health, Education, etc.');
            $table->decimal('planned_amount', 10, 2);
            $table->decimal('actual_amount', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->index('salary_plan_id');
            $table->index('category');
            $table->index('is_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
