<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('standard')->after('status');
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->string('bog_order_id')->nullable()->after('payment_status');
            $table->unsignedTinyInteger('installment_months')->nullable()->after('bog_order_id');
            $table->string('installment_type')->nullable()->after('installment_months');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'bog_order_id',
                'installment_months',
                'installment_type',
            ]);
        });
    }
};
