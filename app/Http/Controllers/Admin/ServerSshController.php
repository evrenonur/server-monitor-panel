<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\User;
use Illuminate\Support\Str;

class ServerSshController extends Controller
{
    public function show(Server $server)
    {
        $token = Str::random(60);
        // Şu anki kullanıcıya ait 'api_token' güncelleniyor.
        User::where('id', auth()->user()->id)->update(['api_token' => $token]);
        auth()->user()->setAttribute('api_token', $token);
        return view('admin.servers.ssh', [
            'server' => $server,

        ]);
    }
}
