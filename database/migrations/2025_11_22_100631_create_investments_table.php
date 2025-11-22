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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('investment_name', 100);
            $table->text('description')->nullable();
            $table->decimal('total_invested', 10, 2)->default(0)->comment('Sum of all investment entries');
            $table->decimal('total_profit', 10, 2)->default(0)->comment('Sum of all profits');
            $table->decimal('total_loss', 10, 2)->default(0)->comment('Sum of all losses');
            $table->decimal('current_value', 10, 2)->default(0)->comment('Current investment value');
            $table->decimal('remaining_amount', 10, 2)->default(0)->comment('Total invested - withdrawals');
            $table->string('status', 20)->default('Active')->comment('Active, Closed');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->index(['user_id', 'status']);
            $table->index('investment_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
