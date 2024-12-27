<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'server_id',
        'name',
        'load_state',
        'active_state',
        'sub_state',
        'description',
        'main_pid',
        'load_error',
        'fragment_path',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
