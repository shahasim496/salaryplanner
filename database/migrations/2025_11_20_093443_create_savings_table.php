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
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_plan_id')->constrained('salary_plans')->onDelete('cascade');
            $table->string('saving_name', 100);
            $table->string('saving_type', 20)->comment('Fixed, Variable, Emergency, Investment');
            $table->decimal('planned_amount', 10, 2);
            $table->decimal('actual_amount', 10, 2)->nullable();
            $table->decimal('accumulated_amount', 10, 2)->default(0)->comment('Total saved so far');
            $table->text('description')->nullable();
            $table->string('target_goal', 255)->nullable();
            $table->decimal('target_amount', 10, 2)->nullable();
            $table->date('target_date')->nullable();
            $table->boolean('is_achieved')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->index('salary_plan_id');
            $table->index('saving_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
