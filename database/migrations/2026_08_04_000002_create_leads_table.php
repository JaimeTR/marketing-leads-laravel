<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 120)->unique();
            $table->string('company', 120)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('campaign', 100);
            $table->unsignedTinyInteger('ai_score')->nullable();
            $table->string('ai_temperature', 20)->nullable();
            $table->text('ai_summary')->nullable();
            $table->text('ai_recommended_action')->nullable();
            $table->timestamp('ai_analyzed_at')->nullable();
            $table->timestamps();

            $table->index('campaign');
            $table->index('created_at');
            $table->index(['campaign', 'created_at']);
            $table->index(['name', 'company', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
