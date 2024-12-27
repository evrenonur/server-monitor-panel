<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;

class ServerSshController extends Controller
{
    public function show(Server $server)
    {
        if (!$server->ssh_port) {
            $server->ssh_port = 22;
        }

        return view('admin.servers.ssh', [
            'server' => $server
        ]);
    }
}
