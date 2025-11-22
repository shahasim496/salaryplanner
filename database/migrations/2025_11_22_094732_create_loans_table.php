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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('person_name', 100);
            $table->enum('loan_type', ['owed_to_me', 'owed_by_me'])->comment('owed_to_me: someone owes me, owed_by_me: I owe someone');
            $table->decimal('total_loaned', 10, 2)->default(0)->comment('Sum of all loan entries');
            $table->decimal('total_paid', 10, 2)->default(0)->comment('Sum of all payments');
            $table->decimal('remaining_amount', 10, 2)->default(0);
            $table->string('status', 20)->default('Active')->comment('Active, Paid, Partial');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->unique(['user_id', 'person_name', 'loan_type']);
            $table->index(['user_id', 'loan_type']);
            $table->index('person_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
