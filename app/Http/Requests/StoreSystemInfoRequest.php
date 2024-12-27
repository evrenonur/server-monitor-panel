<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSystemInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'system' => 'required|array',
            'system.hostname' => 'required|string',
            'system.os' => 'required|array',
            'system.os.name' => 'required|string',
            'system.os.version' => 'required|string',
            'system.os.id' => 'required|string',
            'system.os.version_id' => 'required|string',
            'system.architecture' => 'required|string',
            'system.processor' => 'required|string',
            'system.python_version' => 'required|string',

            'network' => 'required|array',
            'network.interfaces' => 'required|array',

            'resources' => 'required|array',
            'resources.cpu' => 'required|array',
            'resources.cpu.cores' => 'required|integer',
            'resources.cpu.usage_percent' => 'required|numeric',
            'resources.memory' => 'required|array',
            'resources.memory.total_gb' => 'required|numeric',
            'resources.memory.used_gb' => 'required|numeric',
            'resources.memory.free_gb' => 'required|numeric',
            'resources.memory.usage_percent' => 'required|numeric',
            'resources.disks' => 'required|array',

            'timestamp' => 'required|string',

            'updates' => 'nullable|array',
            'updates.count' => 'nullable|integer',
            'updates.packages' => 'nullable|array',
            'updates.packages.*.package' => 'required|string',
            'updates.packages.*.current_version' => 'required|string',
            'updates.packages.*.new_version' => 'required|string',
            'updates.packages.*.architecture' => 'required|string',
            'updates.packages.*.distribution' => 'required|string',

            // Process validasyonları
            'processes' => 'nullable|array',
            'processes.total_processes' => 'required|integer',
            'processes.stats' => 'required|array',
            'processes.stats.running' => 'required|integer',
            'processes.stats.sleeping' => 'required|integer',
            'processes.stats.stopped' => 'required|integer',
            'processes.stats.zombie' => 'required|integer',
            'processes.processes' => 'required|array',
            'processes.processes.*.pid' => 'required|integer',
            'processes.processes.*.name' => 'required|string',
            'processes.processes.*.username' => 'required|string',
            'processes.processes.*.cpu_percent' => 'required|numeric',
            'processes.processes.*.memory_percent' => 'required|numeric',
            'processes.processes.*.status' => 'required|string|in:running,sleeping,stopped,zombie',

            'services' => 'nullable|array',
            'services.services' => 'nullable|array',
            'services.services.*.name' => 'required|string',
            'services.services.*.load_state' => 'required|string',
            'services.services.*.active_state' => 'required|string',
            'services.services.*.sub_state' => 'required|string',
            'services.services.*.description' => 'nullable|string',
            'services.services.*.main_pid' => 'required|string',
            'services.services.*.load_error' => 'nullable|string',
            'services.services.*.fragment_path' => 'nullable|string',
            'services.stats' => 'required|array',
            'services.stats.active' => 'required|integer',
            'services.stats.inactive' => 'required|integer',
            'services.stats.failed' => 'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'system.required' => 'Sistem bilgileri gereklidir',
            'system.hostname.required' => 'Sunucu adı gereklidir',
            'system.os.required' => 'İşletim sistemi bilgileri gereklidir',
            'system.os.name.required' => 'İşletim sistemi adı gereklidir',
            'system.os.version.required' => 'İşletim sistemi versiyonu gereklidir',
            'system.architecture.required' => 'Sistem mimarisi gereklidir',
            'system.processor.required' => 'İşlemci bilgisi gereklidir',
            'system.python_version.required' => 'Python versiyonu gereklidir',
            'network.required' => 'Ağ bilgileri gereklidir',
            'network.interfaces.required' => 'Ağ arayüzleri gereklidir',
            'resources.required' => 'Sistem kaynakları bilgileri gereklidir',
            'resources.cpu.required' => 'CPU bilgileri gereklidir',
            'resources.cpu.cores.required' => 'CPU çekirdek sayısı gereklidir',
            'resources.cpu.usage_percent.required' => 'CPU kullanım yüzdesi gereklidir',
            'resources.memory.required' => 'Bellek bilgileri gereklidir',
            'resources.memory.total_gb.required' => 'Toplam bellek miktarı gereklidir',
            'resources.memory.used_gb.required' => 'Kullanılan bellek miktarı gereklidir',
            'resources.memory.free_gb.required' => 'Boş bellek miktarı gereklidir',
            'resources.memory.usage_percent.required' => 'Bellek kullanım yüzdesi gereklidir',
            'resources.disks.required' => 'Disk bilgileri gereklidir',
            'timestamp.required' => 'Zaman damgası gereklidir',
            'processes.required' => 'Süreç bilgileri gereklidir',
            'processes.total_processes.required' => 'Toplam süreç sayısı gereklidir',
            'processes.stats.required' => 'Süreç istatistikleri gereklidir',
            'processes.stats.running.required' => 'Çalışan süreç sayısı gereklidir',
            'processes.stats.sleeping.required' => 'Uyuyan süreç sayısı gereklidir',
            'processes.stats.stopped.required' => 'Durmuş süreç sayısı gereklidir',
            'processes.stats.zombie.required' => 'Zombi süreç sayısı gereklidir',
            'processes.processes.required' => 'Süreç listesi gereklidir',
            'processes.processes.*.pid.required' => 'Süreç ID gereklidir',
            'processes.processes.*.name.required' => 'Süreç adı gereklidir',
            'processes.processes.*.username.required' => 'Kullanıcı adı gereklidir',
            'processes.processes.*.cpu_percent.required' => 'CPU kullanım yüzdesi gereklidir',
            'processes.processes.*.memory_percent.required' => 'Bellek kullanım yüzdesi gereklidir',
            'processes.processes.*.status.required' => 'Süreç durumu gereklidir',
            'processes.processes.*.status.in' => 'Geçersiz süreç durumu',
            'services.required' => 'Servis bilgileri gereklidir',
            'services.services.*.name.required' => 'Servis adı gereklidir',
            'services.services.*.load_state.required' => 'Servis yükleme durumu gereklidir',
            'services.services.*.active_state.required' => 'Servis aktiflik durumu gereklidir',
            'services.services.*.sub_state.required' => 'Servis alt durumu gereklidir',
            'services.services.*.main_pid.required' => 'Servis PID bilgisi gereklidir',
            'services.stats.active.required' => 'Aktif servis sayısı gereklidir',
            'services.stats.inactive.required' => 'Pasif servis sayısı gereklidir',
            'services.stats.failed.required' => 'Hatalı servis sayısı gereklidir'
        ];
    }
}
