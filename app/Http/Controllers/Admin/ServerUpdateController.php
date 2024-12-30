<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Services\SshService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServerUpdateController extends Controller
{
    private SshService $sshService;

    public function __construct(SshService $sshService)
    {
        $this->sshService = $sshService;
    }

    public function update(Request $request, Server $server)
    {
        try {
            if (!$this->sshService->connect($server)) {
                return response()->json([
                    'success' => false,
                    'message' => 'SSH bağlantısı kurulamadı'
                ]);
            }

            // Seçili paketler var mı kontrol et
            $packages = $request->input('packages', []);

            if (!empty($packages)) {
                // Seçili paketleri güncelle
                $commands = [
                    'sudo apt-get update'
                ];

                // Her paket için güncelleme komutu ekle
                foreach ($packages as $package) {
                    $commands[] = "sudo DEBIAN_FRONTEND=noninteractive apt-get install -y {$package}";
                }
            } else {
                // Tüm paketleri güncelle
                $commands = [
                    'sudo apt-get update',
                    'sudo DEBIAN_FRONTEND=noninteractive apt-get upgrade -y'
                ];
            }

            $output = [];
            foreach ($commands as $command) {
                $result = $this->sshService->executeCommand($command);
                if (!$result['success']) {
                    throw new \Exception($result['error']);
                }
                $output[] = $result['output'];
            }

            $this->sshService->disconnect();

            $message = !empty($packages)
                ? count($packages) . " adet paket başarıyla güncellendi"
                : "Tüm güncellemeler başarıyla yüklendi";

            return response()->json([
                'success' => true,
                'message' => $message,
                'output' => $output
            ]);

        } catch (\Exception $e) {
            Log::error('Güncelleme hatası: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Güncelleme sırasında bir hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
}
