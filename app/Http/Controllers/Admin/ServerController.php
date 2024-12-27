<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Service;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all();
        return view('admin.servers.index', compact('servers'));
    }

    public function create()
    {
        return view('admin.servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'ssh_port' => 'required|integer|between:1,65535',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $server = Server::create($validated);

        return redirect()
            ->route('admin.servers.index')
            ->with('success', 'Sunucu başarıyla eklendi. API Key: ' . $server->api_key);
    }

    public function edit(Server $server)
    {
        return view('admin.servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'ssh_port' => 'required|integer|between:1,65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $server->update($validated);

        return redirect()
            ->route('admin.servers.index')
            ->with('success', 'Sunucu başarıyla güncellendi.');
    }

    public function destroy(Server $server)
    {
        $server->delete();
        return redirect()
            ->route('admin.servers.index')
            ->with('success', 'Sunucu başarıyla silindi.');
    }

    public function show(Server $server)
    {
        $services = Service::where('server_id', $server->id)
            ->orderBy('created_at', 'desc')
            ->limit(1000) // Son 1000 kayıt
            ->get();

        return view('admin.servers.show', compact('server', 'services'));
    }
}
