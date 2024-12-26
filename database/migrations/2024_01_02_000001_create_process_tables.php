<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('process_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->integer('total_processes');
            $table->integer('running');
            $table->integer('sleeping');
            $table->integer('stopped');
            $table->integer('zombie');
            $table->timestamps();
        });

        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('process_info_id')->constrained()->onDelete('cascade');
            $table->integer('pid');
            $table->string('name');
            $table->string('username');
            $table->float('cpu_percent');
            $table->float('memory_percent');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processes');
        Schema::dropIfExists('process_infos');
    }
}; 