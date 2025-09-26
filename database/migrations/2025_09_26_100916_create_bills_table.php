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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('month');
            $table->unsignedBigInteger('bill_category_id');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('flat_id');
            $table->unsignedBigInteger('house_owner_id');
            $table->timestamps();

            $table->foreign('flat_id')
                ->references('id')
                ->on('flats')
                ->onDelete('cascade');

            $table->foreign('bill_category_id')
                ->references('id')
                ->on('bill_categories')
                ->onDelete('cascade');

            $table->foreign('house_owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // create a composite key using flat_id + month to force unique
            $table->unique(['flat_id', 'month', 'bill_category_id'], 'unique_bill_per_flat_month_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
