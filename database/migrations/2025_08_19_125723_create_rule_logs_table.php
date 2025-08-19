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
        Schema::create('rule_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('rules')->cascadeOnDelete();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->dateTime('triggered_at')->index();
            $table->json('stats_snapshot');
            $table->decimal('before_budget', 10, 2);
            $table->decimal('after_budget', 10, 2);
            $table->decimal('before_cpm', 10, 4);
            $table->decimal('after_cpm', 10, 4);
            $table->json('actions_applied');
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_logs');
    }
};
