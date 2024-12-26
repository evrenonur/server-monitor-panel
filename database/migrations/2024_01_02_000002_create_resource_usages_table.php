<?php

namespace Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->float('cpu_usage')->comment('CPU kullanım yüzdesi');
            $table->float('memory_usage')->comment('Bellek kullanım yüzdesi');
            $table->float('memory_total')->comment('Toplam bellek (GB)');
            $table->float('memory_used')->comment('Kullanılan bellek (GB)');
            $table->float('memory_free')->comment('Boş bellek (GB)');
            $table->json('disk_usages')->comment('Disk kullanım bilgileri');
            $table->timestamp('created_at')->useCurrent();
            $table->index(['server_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_usages');
    }
}; 