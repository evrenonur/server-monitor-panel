<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('load_state');
            $table->string('active_state');
            $table->string('sub_state');
            $table->string('description')->nullable();
            $table->string('main_pid');
            $table->text('load_error')->nullable();
            $table->string('fragment_path')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['server_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
