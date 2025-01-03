@extends('adminlte::page')

@section('title', 'Güvenlik - ' . $server->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="text-dark">
                <i class="fas fa-shield-alt text-primary mr-2"></i>
                {{ $server->name }} - Güvenlik
            </h1>
        </div>
        <a href="{{ route('admin.servers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <!-- UFW Durum Kartı -->
    <div class="col-md-4">
        <div class="card card-outline card-primary shadow-sm hover-shadow">
            <div class="card-header bg-white">
                <h3 class="card-title d-flex align-items-center">
                    <i class="fas fa-shield-virus text-primary mr-2"></i>
                    UFW Durumu
                </h3>
            </div>
            <div class="card-body">
                <div class="status-item mb-4">
                    <label class="text-muted mb-2">Firewall Durumu</label>
                    <div class="d-flex align-items-center">
                        <div class="status-indicator mr-2"></div>
                        <span id="ufw-status" class="badge badge-pill badge-secondary px-3 py-2">Yükleniyor...</span>
                    </div>
                </div>
                <div class="status-item mb-4">
                    <label class="text-muted mb-2">Loglama</label>
                    <div class="d-flex align-items-center">
                        <div class="status-indicator mr-2"></div>
                        <span id="ufw-logging" class="badge badge-pill badge-secondary px-3 py-2">Yükleniyor...</span>
                    </div>
                </div>
                <div class="btn-group w-100">
                    <button type="button" class="btn btn-success btn-flat" id="btn-ufw-enable">
                        <i class="fas fa-power-off mr-1"></i> Etkinleştir
                    </button>
                    <button type="button" class="btn btn-danger btn-flat" id="btn-ufw-disable">
                        <i class="fas fa-ban mr-1"></i> Devre Dışı
                    </button>
                    <button type="button" class="btn btn-warning btn-flat" id="btn-ufw-reset">
                        <i class="fas fa-redo mr-1"></i> Sıfırla
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- UFW Kurallar Kartı -->
    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm hover-shadow">
            <div class="card-header bg-white">
                <h3 class="card-title d-flex align-items-center">
                    <i class="fas fa-list-ul text-primary mr-2"></i>
                    Firewall Kuralları
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm btn-flat" id="btn-add-rule">
                        <i class="fas fa-plus mr-1"></i> Yeni Kural
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="rules-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="80">No</th>
                                <th>Kural</th>
                                <th class="text-center" width="120">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- JavaScript ile doldurulacak -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Yeni Kural Modalı -->
<div class="modal fade" id="addRuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle text-primary mr-2"></i>
                    Yeni Kural Ekle
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="text-muted">Kural Tipi</label>
                    <select class="form-control form-control-border" id="rule-type">
                        <option value="port">Port</option>
                        <option value="app">Uygulama</option>
                        <option value="ip">IP Adresi</option>
                    </select>
                </div>
                <div class="form-group" id="port-inputs">
                    <label class="text-muted">Port</label>
                    <input type="number" class="form-control form-control-border" id="port-number">
                    <select class="form-control form-control-border mt-2" id="port-protocol">
                        <option value="tcp">TCP</option>
                        <option value="udp">UDP</option>
                    </select>
                </div>
                <div class="form-group d-none" id="app-inputs">
                    <label class="text-muted">Uygulama</label>
                    <select class="form-control form-control-border" id="app-name">
                        <!-- JavaScript ile doldurulacak -->
                    </select>
                </div>
                <div class="form-group d-none" id="ip-inputs">
                    <label class="text-muted">IP Adresi</label>
                    <input type="text" class="form-control form-control-border" id="ip-address">
                </div>
                <div class="form-group">
                    <label class="text-muted">İzin</label>
                    <select class="form-control form-control-border" id="rule-action">
                        <option value="allow">İzin Ver</option>
                        <option value="deny">Reddet</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> İptal
                </button>
                <button type="button" class="btn btn-primary btn-flat" id="btn-save-rule">
                    <i class="fas fa-save mr-1"></i> Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease-in-out;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #6c757d;
    }
    .status-indicator.active {
        background-color: #28a745;
    }
    .status-indicator.inactive {
        background-color: #dc3545;
    }
    .status-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
    }
    .form-control-border {
        border-radius: 0.25rem;
        border: 1px solid #ced4da;
        padding: 0.375rem 0.75rem;
    }
    .form-control-border:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .modal-content {
        border: none;
        border-radius: 0.5rem;
    }
    .btn-flat {
        border-radius: 0.25rem;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
// Global değişkenler
let ws = null;

// WebSocket bağlantısı
function initWebSocket() {
    const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
    const url = `${protocol}://{{ $server->ip_address }}:{{ $server->ws_port }}/?token={{ auth()->user()->api_token }}`;
    ws = new WebSocket(url);

    ws.onopen = function() {
        console.log('WebSocket bağlantısı açıldı');
        refreshStatus();
    };

    ws.onmessage = function(event) {
        const response = JSON.parse(event.data);
        handleResponse(response);
    };

    ws.onerror = function(error) {
        console.error('WebSocket hatası:', error);
        Swal.fire({
            icon: 'error',
            title: 'Bağlantı Hatası',
            text: 'WebSocket bağlantısı kurulamadı!'
        });
    };

    ws.onclose = function() {
        console.log('WebSocket bağlantısı kapandı');
        // 5 saniye sonra yeniden bağlan
        setTimeout(initWebSocket, 5000);
    };
}

// Yanıt işleme
function handleResponse(response) {
    try {
        if (!response.success) {
            handleError(response);
            return;
        }

        switch(response.command) {
            case 'ufw-status':
                updateStatus(response.data);
                break;

            case 'ufw-list-rules':
                updateRules(response.data);
                break;

            case 'ufw-app-list':
                updateAppList(response.data);
                break;

            case 'ufw-enable':
                Toast.fire({
                    icon: 'success',
                    title: 'Firewall başarıyla etkinleştirildi',
                    text: response.stdout || ''
                });
                refreshStatus();
                break;

            case 'ufw-disable':
                Toast.fire({
                    icon: 'success',
                    title: 'Firewall başarıyla devre dışı bırakıldı',
                    text: 'Sistem şu anda korumasız durumda!'
                });
                refreshStatus();
                break;

            case 'ufw-reset':
                Toast.fire({
                    icon: 'success',
                    title: 'Firewall başarıyla sıfırlandı',
                    text: 'Tüm kurallar varsayılan ayarlara döndürüldü'
                });
                refreshStatus();
                break;

            case 'ufw-add-rule':
                if (response.stdout) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Kural başarıyla eklendi',
                        text: response.stdout
                    });
                    refreshStatus();
                } else if (response.stderr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Kural eklenemedi',
                        text: response.stderr
                    });
                }
                break;

            case 'ufw-delete-rule':
                if (response.stdout) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Kural başarıyla silindi',
                        text: response.stdout
                    });
                    refreshStatus();
                } else if (response.stderr) {
                    Toast.fire({
                        icon: 'error',
                        title: 'Kural silinemedi',
                        text: response.stderr
                    });
                }
                break;

            case 'ufw-app-info':
                if (response.data) {
                    Swal.fire({
                        title: response.data.title,
                        html: `
                            <p><strong>Açıklama:</strong> ${response.data.description}</p>
                            <p><strong>Portlar:</strong> ${response.data.ports.join(', ')}</p>
                        `,
                        confirmButtonText: 'Tamam'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Uygulama bilgisi alınamadı'
                    });
                }
                break;

            default:
                Toast.fire({
                    icon: 'info',
                    title: 'İşlem tamamlandı',
                    text: response.stdout || ''
                });
                refreshStatus();
                break;
        }
    } catch (e) {
        console.error('WebSocket mesaj hatası:', e);
        Toast.fire({
            icon: 'error',
            title: 'İşlem başarısız',
            text: 'Beklenmeyen bir hata oluştu. Lütfen tekrar deneyin.'
        });
    }
}

// Hata durumları için özel mesajlar
function handleError(response) {
    let errorMessage = response.error || 'Bir hata oluştu';

    // Özel hata durumları
    if (response.command === 'ufw-add-rule' && response.stderr?.includes('already exists')) {
        errorMessage = 'Bu kural zaten mevcut!';
    }
    else if (response.stderr?.includes('permission denied')) {
        errorMessage = 'Bu işlem için yetkiniz yok!';
    }

    Swal.fire({
        icon: 'error',
        title: 'Hata',
        text: errorMessage,
        confirmButtonText: 'Tamam'
    });
}

// Durum güncelleme
function updateStatus(data) {
    $('#ufw-status')
        .text(data.status === 'active' ? 'Aktif' : 'Pasif')
        .removeClass('badge-secondary badge-success badge-danger')
        .addClass(data.status === 'active' ? 'badge-success' : 'badge-danger');

    $('#ufw-logging')
        .text(data.logging === 'on' ? 'Açık' : 'Kapalı')
        .removeClass('badge-secondary badge-success badge-danger')
        .addClass(data.logging === 'on' ? 'badge-success' : 'badge-danger');
}

// Kuralları güncelle
function updateRules(rules) {
    const tbody = $('#rules-table tbody').empty();

    rules.forEach(rule => {
        tbody.append(`
            <tr>
                <td>${rule.number}</td>
                <td>${rule.rule}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="deleteRule('${rule.number}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
    });
}

// Uygulama listesini güncelle
function updateAppList(apps) {
    const select = $('#app-name').empty();
    apps.forEach(app => {
        select.append(`<option value="${app}">${app}</option>`);
    });
}

// Durum yenileme
function refreshStatus() {
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({ command: 'ufw-status' }));
        ws.send(JSON.stringify({ command: 'ufw-list-rules' }));
        ws.send(JSON.stringify({ command: 'ufw-app-list' }));
    }
}

// Kural silme
function deleteRule(number) {
    Swal.fire({
        title: 'Kural Sil',
        text: `${number} numaralı kural silinecek. Bu işlem geri alınamaz. Emin misiniz?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'İptal',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            ws.send(JSON.stringify({
                command: 'ufw-delete-rule',
                rule: number
            }));
        }
    });
}

// Toast bildirimi
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

// Sayfa yüklendiğinde
$(document).ready(function() {
    // WebSocket bağlantısını başlat
    initWebSocket();

    // UFW kontrolleri
    $('#btn-ufw-enable').click(() => {
        Swal.fire({
            title: 'Firewall Etkinleştir',
            text: 'Firewall etkinleştirilecek. Emin misiniz?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Evet, Etkinleştir',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                ws.send(JSON.stringify({ command: 'ufw-enable' }));
            }
        });
    });

    $('#btn-ufw-disable').click(() => {
        Swal.fire({
            title: 'Firewall Devre Dışı Bırak',
            text: 'Firewall devre dışı bırakılacak. Bu işlem güvenlik risklerine yol açabilir. Emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Evet, Devre Dışı Bırak',
            cancelButtonText: 'İptal',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                ws.send(JSON.stringify({ command: 'ufw-disable' }));
            }
        });
    });

    $('#btn-ufw-reset').click(() => {
        Swal.fire({
            title: 'Firewall Sıfırla',
            text: 'Tüm firewall kuralları varsayılan ayarlara sıfırlanacak. Bu işlem geri alınamaz. Emin misiniz?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Evet, Sıfırla',
            cancelButtonText: 'İptal',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                ws.send(JSON.stringify({ command: 'ufw-reset' }));
            }
        });
    });

    // Kural tipi değiştiğinde
    $('#rule-type').change(function() {
        const type = $(this).val();
        $('#port-inputs, #app-inputs, #ip-inputs').addClass('d-none');
        $(`#${type}-inputs`).removeClass('d-none');
    });

    // Yeni kural ekleme
    $('#btn-add-rule').click(() => {
        $('#addRuleModal').modal('show');
    });

    // Kural kaydetme
    $('#btn-save-rule').click(() => {
        const type = $('#rule-type').val();
        const action = $('#rule-action').val();
        let rule = '';

        switch(type) {
            case 'port':
                const port = $('#port-number').val();
                const protocol = $('#port-protocol').val();
                rule = `${action} ${port}/${protocol}`;
                break;
            case 'app':
                const app = $('#app-name').val();
                rule = `${action} ${app}`;
                break;
            case 'ip':
                const ip = $('#ip-address').val();
                rule = `${action} from ${ip}`;
                break;
        }

        ws.send(JSON.stringify({
            command: 'ufw-add-rule',
            rule: rule
        }));

        $('#addRuleModal').modal('hide');
    });

    // Her 30 saniyede bir yenile
    setInterval(refreshStatus, 30000);
});
</script>
@stop
