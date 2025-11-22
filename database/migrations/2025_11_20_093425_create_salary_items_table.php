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
        Schema::create('salary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_plan_id')->constrained('salary_plans')->onDelete('cascade');
            $table->string('item_name', 100);
            $table->string('item_type', 20)->comment('Income, Deduction');
            $table->string('category', 50)->nullable()->comment('Basic, Allowance, Bonus, Tax, Insurance, etc.');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('priority')->default(0)->comment('Higher priority items shown first');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->index('salary_plan_id');
            $table->index('item_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_items');
    }
};
