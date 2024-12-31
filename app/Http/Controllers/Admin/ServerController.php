<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $validated = $this->validateServer($request);

        $server = new Server();
        $server->name = $validated['name'];
        $server->ip_address = $validated['ip_address'];
        $server->ssh_port = $validated['ssh_port'];
        $server->ws_port = $validated['ws_port'];
        $server->username = $validated['username'];
        $server->password = $validated['password'];
        $server->api_key = Str::random(64);
        $server->is_active = $validated['is_active'];
        $server->save();

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
        $validated = $this->validateServer($request);

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

    public function test(Server $server)
    {
        return view('admin.servers.test', compact('server'));
    }

    /**
     * Docker detaylarını göster
     *
     * @param Server $server
     * @return \Illuminate\View\View
     */
    public function docker(Server $server)
    {
        return view('admin.servers.docker', compact('server'));
    }

    protected function validateServer(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'ssh_port' => 'required|integer|min:1|max:65535',
            'ws_port' => 'required|integer|min:1|max:65535',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
    }
}
