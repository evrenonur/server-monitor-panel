<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ana sistem bilgileri
        Schema::create('system_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->string('hostname');
            $table->string('architecture');
            $table->string('processor');
            $table->string('python_version');
            $table->timestamp('system_timestamp');
            $table->timestamps();
        });

        // İşletim sistemi bilgileri
        Schema::create('operating_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('version');
            $table->string('os_id');
            $table->string('version_id');
            $table->timestamps();
        });

        // Network arayüzleri
        Schema::create('network_interfaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->string('name'); // lo, eth0 vs.
            $table->string('ip_address');
            $table->string('netmask');
            $table->string('mac_address');
            $table->timestamps();
        });

        // CPU bilgileri
        Schema::create('cpu_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->integer('cores');
            $table->float('usage_percent');
            $table->timestamps();
        });

        // Memory bilgileri
        Schema::create('memory_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->float('total_gb');
            $table->float('used_gb');
            $table->float('free_gb');
            $table->float('usage_percent');
            $table->timestamps();
        });

        // Disk bilgileri
        Schema::create('disk_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->string('device');
            $table->string('mountpoint');
            $table->float('total_gb');
            $table->float('used_gb');
            $table->float('free_gb');
            $table->float('usage_percent');
            $table->timestamps();
        });

        // Güncelleme bilgileri
        Schema::create('update_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_info_id')->constrained()->onDelete('cascade');
            $table->integer('count');
            $table->timestamps();
        });

        // Güncelleme paketleri
        Schema::create('update_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('update_info_id')->constrained('update_infos')->onDelete('cascade');
            $table->string('package');
            $table->string('current_version');
            $table->string('new_version');
            $table->string('architecture');
            $table->string('distribution');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('update_packages');
        Schema::dropIfExists('update_infos');
        Schema::dropIfExists('disk_infos');
        Schema::dropIfExists('memory_infos');
        Schema::dropIfExists('cpu_infos');
        Schema::dropIfExists('network_interfaces');
        Schema::dropIfExists('operating_systems');
        Schema::dropIfExists('system_infos');
    }
}; 