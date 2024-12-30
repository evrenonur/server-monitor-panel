<?php

namespace App\Services;

use phpseclib3\Net\SSH2;
use App\Models\Server;
use Illuminate\Support\Facades\Log;

class SshService
{
    private SSH2 $ssh;
    private Server $server;

    public function connect(Server $server): bool
    {
        $this->server = $server;

        try {
            $this->ssh = new SSH2($server->ip_address, $server->ssh_port);

            if (!$this->ssh->login($server->username, $server->password)) {
                Log::error('SSH bağlantısı başarısız: ' . $server->name);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('SSH bağlantı hatası: ' . $e->getMessage());
            return false;
        }
    }

    public function executeCommand(string $command): array
    {
        try {
            $output = $this->ssh->exec($command);
            return [
                'success' => true,
                'output' => $output
            ];
        } catch (\Exception $e) {
            Log::error('Komut çalıştırma hatası: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function disconnect(): void
    {
        if (isset($this->ssh)) {
            $this->ssh->disconnect();
        }
    }
}
