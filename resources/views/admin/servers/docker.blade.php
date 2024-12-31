@extends('adminlte::page')

@section('title', 'Docker Detayları - ' . $server->name)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ $server->name }} - Docker Detayları</h1>
        <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <!-- Docker Sistem Bilgileri -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Docker Sistem Bilgileri
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fab fa-docker"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Docker Sürümü</span>
                                <span class="info-box-number" id="docker-version">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-microchip"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">CPU Sayısı</span>
                                <span class="info-box-number" id="cpu-count">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-memory"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Toplam Bellek</span>
                                <span class="info-box-number" id="total-memory">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-box"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Konteynerler</span>
                                <span class="info-box-number">
                                    <span id="running-containers">-</span> çalışıyor /
                                    <span id="total-containers">-</span> toplam
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Docker Durumu -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fab fa-docker mr-1"></i>
                    Docker Durumu
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Durum:</span>
                    <span class="badge badge-success docker-status">Çalışıyor</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Versiyon:</span>
                    <span class="docker-version">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Sürücü:</span>
                    <span class="docker-driver">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>İşletim Sistemi:</span>
                    <span class="docker-os">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Kernel:</span>
                    <span class="docker-kernel">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Konteyner İstatistikleri -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-box mr-1"></i>
                    Konteyner İstatistikleri
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Toplam:</span>
                    <span class="badge badge-secondary container-total">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Çalışan:</span>
                    <span class="badge badge-success container-running">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Duraklatılmış:</span>
                    <span class="badge badge-warning container-paused">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Durdurulmuş:</span>
                    <span class="badge badge-danger container-stopped">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sistem Kaynakları -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-microchip mr-1"></i>
                    Sistem Kaynakları
                </h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>CPU Sayısı:</span>
                    <span class="docker-cpu">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Toplam Bellek:</span>
                    <span class="docker-memory">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Bellek Limiti:</span>
                    <span class="badge badge-info memory-limit">-</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Swap Limiti:</span>
                    <span class="badge badge-info swap-limit">-</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Disk Kullanımı -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-hdd mr-1"></i>
            Disk Kullanımı
        </h3>
    </div>
    <div class="card-body">
        <div class="row disk-usage">
            <!-- Burası JavaScript ile doldurulacak -->
        </div>
    </div>
</div>

<!-- Çalışan Konteynerler -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-box mr-1"></i>
            Çalışan Konteynerler
        </h3>
    </br><small id="containers-last-update">Son Güncelleme: -</small>

    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="containers-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>İsim</th>
                        <th>Image</th>
                        <th>Durum</th>
                        <th>CPU %</th>
                        <th>Bellek</th>
                        <th>Network I/O</th>
                        <th>Block I/O</th>
                        <th>Portlar</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Burası JavaScript ile doldurulacak -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Docker Images -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-images mr-1"></i>
            Docker Images
        </h3>
        <br><small id="images-last-update">Son Güncelleme: -</small>
        <div class="card-tools">
            <button type="button" class="btn btn-success btn-sm" onclick="pullImage()">
                <i class="fas fa-download"></i> İmaj Çek
            </button>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="images-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Repository</th>
                        <th>Tag</th>
                        <th>Boyut</th>
                        <th>Oluşturulma</th>
                        <th>OS/Arch</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JavaScript ile doldurulacak -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- İmaj Detay Modalı -->
<div class="modal fade" id="imageDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">İmaj Detayları</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Temel Bilgiler</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>ID</th>
                                <td id="image-id"></td>
                            </tr>
                            <tr>
                                <th>Repository Tags</th>
                                <td id="image-tags"></td>
                            </tr>
                            <tr>
                                <th>Oluşturulma</th>
                                <td id="image-created"></td>
                            </tr>
                            <tr>
                                <th>Boyut</th>
                                <td id="image-size"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Sistem Bilgileri</h6>
                        <table class="table table-sm">
                            <tr>
                                <th>İşletim Sistemi</th>
                                <td id="image-os"></td>
                            </tr>
                            <tr>
                                <th>Mimari</th>
                                <td id="image-arch"></td>
                            </tr>
                            <tr>
                                <th>Açık Portlar</th>
                                <td id="image-ports"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Çevre Değişkenleri</h6>
                        <pre id="image-env" class="bg-light p-2" style="max-height: 150px; overflow-y: auto;"></pre>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Etiketler</h6>
                        <pre id="image-labels" class="bg-light p-2" style="max-height: 150px; overflow-y: auto;"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Docker Volumes -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-hdd mr-1"></i>
            Docker Volumes
        </h3>
        <br><small id="volumes-last-update">Son Güncelleme: -</small>
        <div class="card-tools">
            <button type="button" class="btn btn-success btn-sm" onclick="createVolume()">
                <i class="fas fa-plus"></i> Volume Oluştur
            </button>
            <button type="button" class="btn btn-warning btn-sm" onclick="pruneVolumes()">
                <i class="fas fa-broom"></i> Temizle
            </button>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table id="volumes-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>İsim</th>
                        <th>Driver</th>
                        <th>Mountpoint</th>
                        <th>Scope</th>
                        <th>Labels</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- JavaScript ile doldurulacak -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<style>
.disk-usage-card {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    margin-bottom: 1rem;
    height: 100%;
}

.disk-usage-card .card-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.disk-usage-card .card-title {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    text-align: left;
    width: 100%;
}

.disk-usage-card .progress {
    height: 10px;
    margin-bottom: 0.5rem;
    border-radius: 0.25rem;
    background-color: rgba(0,0,0,.1);
    width: 100%;
}

.disk-usage-card .progress-bar {
    background-color: #007bff;
    border-radius: 0.25rem;
    transition: width .6s ease;
}

.disk-usage-card .progress-details {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.5rem;
    width: 100%;
    text-align: left;
}

.disk-usage-card .progress-details div {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0.25rem;
}

/* Progress bar renkleri */
.disk-usage-card .progress-bar[aria-valuenow^="9"],
.disk-usage-card .progress-bar[aria-valuenow="100"] {
    background-color: #dc3545; /* Kırmızı - %90 üzeri */
}

.disk-usage-card .progress-bar[aria-valuenow^="8"] {
    background-color: #ffc107; /* Sarı - %80 üzeri */
}

.disk-usage-card .progress-bar[aria-valuenow^="7"] {
    background-color: #17a2b8; /* Mavi - %70 üzeri */
}

/* Responsive düzenlemeler */
@media (max-width: 768px) {
    .disk-usage .col-md-3 {
        margin-bottom: 1rem;
    }
}

/* Son güncelleme zamanı stili */
.last-update {
    float: right;
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 0.5rem;
}

/* Tablo güncelleme efekti */
@keyframes highlightRow {
    0% {
        background-color: rgba(0, 123, 255, 0.1);
    }
    100% {
        background-color: transparent;
    }
}

.highlight-update {
    animation: highlightRow 1s ease-in-out;
}

/* DataTables özelleştirmeleri */
.table-striped tbody tr:nth-of-type(odd).highlight-update {
    animation: highlightRow 1s ease-in-out;
}

/* Docker buton stilleri - KALDIRILDI */
.docker-actions {
    white-space: nowrap;
}

.highlight-update {
    animation: highlight 1s;
}
@keyframes highlight {
    0% { background-color: #fff3cd; }
    100% { background-color: transparent; }
}
.progress {
    height: 20px;
    margin-bottom: 0;
}
.progress-bar {
    line-height: 20px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 0 5px;
}
</style>
@stop

@section('js')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
// Global fonksiyonlar
function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function formatDate(date) {
    return date.toLocaleString('tr-TR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
}

function updateLastUpdateTime(selector) {
    const now = new Date();
    $(selector).text(`Son Güncelleme: ${formatDate(now)}`);
}

// Docker imaj işlemleri
function showImageDetail(imageId) {
    const ws = window.dockerWs;
    if (ws && ws.readyState === WebSocket.OPEN) {
        // Modalı aç ve yükleniyor göster
        $('#imageDetailModal').modal('show');
        $('#imageDetailModal .modal-body').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Yükleniyor...</span>
                </div>
                <div class="mt-2">İmaj detayları yükleniyor...</div>
            </div>
        `);

        // WebSocket mesajını gönder
        ws.send(JSON.stringify({
            command: 'docker-image-inspect',
            image: imageId
        }));
    }
}

function pullImage() {
    Swal.fire({
        title: 'İmaj Çek',
        input: 'text',
        inputLabel: 'İmaj Adı (örn: nginx:latest)',
        inputPlaceholder: 'İmaj adını girin...',
        showCancelButton: true,
        confirmButtonText: 'Çek',
        cancelButtonText: 'İptal',
        inputValidator: (value) => {
            if (!value) {
                return 'İmaj adı gerekli!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const ws = window.dockerWs;
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    command: 'docker-image-pull',
                    image: result.value
                }));

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'İmaj indiriliyor...'
                });
            }
        }
    });
}

function removeImage(image) {
    Swal.fire({
        title: 'İmaj Sil',
        text: `"${image}" imajını silmek istediğinize emin misiniz?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'İptal',
        showDenyButton: true,
        denyButtonText: 'Zorla Sil',
        denyButtonColor: '#ffc107'
    }).then((result) => {
        if (result.isConfirmed || result.isDenied) {
            const ws = window.dockerWs;
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    command: 'docker-image-remove',
                    image: image,
                    force: result.isDenied
                }));

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'İmaj siliniyor...'
                });
            }
        }
    });
}

function tagImage(image) {
    Swal.fire({
        title: 'İmaj Etiketle',
        input: 'text',
        inputLabel: 'Yeni Etiket',
        inputPlaceholder: 'örn: my-nginx:v1',
        showCancelButton: true,
        confirmButtonText: 'Etiketle',
        cancelButtonText: 'İptal',
        inputValidator: (value) => {
            if (!value) {
                return 'Yeni etiket gerekli!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const ws = window.dockerWs;
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    command: 'docker-image-tag',
                    source: image,
                    target: result.value
                }));

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'İmaj etiketleniyor...'
                });
            }
        }
    });
}

function updateImageDetail(data) {
    // Modal içeriğini güncelle
    const modalBody = `
        <div class="row">
            <div class="col-md-6">
                <h6>Temel Bilgiler</h6>
                <table class="table table-sm">
                    <tr>
                        <th>ID</th>
                        <td>${data.Id.substring(7, 19)}</td>
                    </tr>
                    <tr>
                        <th>Repository Tags</th>
                        <td>${data.RepoTags ? data.RepoTags.join(', ') : '-'}</td>
                    </tr>
                    <tr>
                        <th>Oluşturulma</th>
                        <td>${new Date(data.Created).toLocaleString('tr-TR')}</td>
                    </tr>
                    <tr>
                        <th>Boyut</th>
                        <td>${formatBytes(data.Size)}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Sistem Bilgileri</h6>
                <table class="table table-sm">
                    <tr>
                        <th>İşletim Sistemi</th>
                        <td>${data.Os || '-'}</td>
                    </tr>
                    <tr>
                        <th>Mimari</th>
                        <td>${data.Architecture || '-'}</td>
                    </tr>
                    <tr>
                        <th>Açık Portlar</th>
                        <td>${data.Config.ExposedPorts ? Object.keys(data.Config.ExposedPorts).join(', ') : '-'}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Çevre Değişkenleri</h6>
                <pre class="bg-light p-2" style="max-height: 150px; overflow-y: auto;">${data.Config.Env ? data.Config.Env.join('\n') : '-'}</pre>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <h6>Etiketler</h6>
                <pre class="bg-light p-2" style="max-height: 150px; overflow-y: auto;">${data.Config.Labels ? JSON.stringify(data.Config.Labels, null, 2) : '-'}</pre>
            </div>
        </div>
    `;

    // Modal içeriğini güncelle
    $('#imageDetailModal .modal-body').html(modalBody);
}

function updateImagesTable(images) {
    const table = $('#images-table').DataTable();
    const currentPage = table.page();
    table.clear();

    images.forEach(image => {
        const actions = `
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info docker-image-action" data-command="inspect" data-image="${image.ID}" title="Detaylar">
                    <i class="fas fa-info-circle"></i>
                </button>
                <button type="button" class="btn btn-primary docker-image-action" data-command="tag" data-image="${image.Repository}:${image.Tag}" title="Etiketle">
                    <i class="fas fa-tag"></i>
                </button>
                <button type="button" class="btn btn-danger docker-image-action" data-command="remove" data-image="${image.Repository}:${image.Tag}" title="Sil">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        const row = table.row.add([
            image.ID.substring(0, 12),
            image.Repository,
            image.Tag,
            image.Size,
            `${image.CreatedSince}<br><small>${image.CreatedAt}</small>`,
            `${image.Os}/${image.Architecture}`,
            actions
        ]).draw(false).node();

        $(row).addClass('highlight-update');
        setTimeout(() => {
            $(row).removeClass('highlight-update');
        }, 1000);
    });

    table.page(currentPage).draw('page');
    updateLastUpdateTime('#images-last-update');
}

// WebSocket bağlantısı
function initWebSocket() {
    const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
    const url = `${protocol}://{{ $server->ip_address }}:{{ $server->ws_port }}/?token={{ auth()->user()->api_token }}`;
    const ws = new WebSocket(url);

    // Global erişim için WebSocket nesnesini sakla
    window.dockerWs = ws;

    ws.onopen = function() {
        console.log('WebSocket bağlantısı açıldı');
        // İlk yükleme için tüm komutları gönder
        ws.send(JSON.stringify({ command: 'docker-resources' }));
        ws.send(JSON.stringify({ command: 'docker-images' }));
        ws.send(JSON.stringify({ command: 'docker-volumes' }));

        // Her 5 saniyede bir güncelle
        setInterval(() => {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ command: 'docker-resources' }));
                ws.send(JSON.stringify({ command: 'docker-images' }));
                ws.send(JSON.stringify({ command: 'docker-volumes' }));
            }
        }, 5000);
    };

    ws.onmessage = function(event) {
        try {
            const response = JSON.parse(event.data);
            if (response.success) {
                switch(response.command) {
                    case 'docker-resources':
                        updateDockerInfo(response.data);
                        break;
                    case 'docker-images':
                        updateImagesTable(response.data.images);
                        break;
                    case 'docker-volumes':
                        updateVolumesTable(response.data.volumes);
                        break;
                    case 'docker-image-inspect':
                        updateImageDetail(response.data[0]);
                        break;
                    case 'docker-volume-inspect':
                        updateVolumeDetail(response.data);
                        break;
                    case 'docker-image-pull':
                    case 'docker-image-remove':
                    case 'docker-image-tag':
                    case 'docker-volume-create':
                    case 'docker-volume-remove':
                    case 'docker-volume-prune':
                        // İşlem başarılı bildirimi
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'success',
                            title: response.stdout || 'İşlem başarıyla tamamlandı'
                        });

                        // İlgili tabloyu yeniden yükle
                        if (response.command.startsWith('docker-image')) {
                            ws.send(JSON.stringify({ command: 'docker-images' }));
                        } else if (response.command.startsWith('docker-volume')) {
                            ws.send(JSON.stringify({ command: 'docker-volumes' }));
                        }
                        break;
                }
            } else {
                // Hata durumunda bildirim göster
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: response.error || 'Bir hata oluştu',
                    confirmButtonText: 'Tamam'
                });

                // Container kullanımda hatası için özel mesaj
                if (response.command === 'docker-volume-remove' && response.containers) {
                    const containerList = response.containers.join(', ');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Volume Kullanımda',
                        html: `Bu volume şu container'lar tarafından kullanılıyor:<br><strong>${containerList}</strong><br>Önce bu container'ları durdurmanız veya silmeniz gerekiyor.`,
                        confirmButtonText: 'Tamam'
                    });
                }
            }
        } catch (e) {
            console.error('WebSocket mesaj hatası:', e);
        }
    };

    ws.onerror = function(error) {
        console.error('WebSocket hatası:', error);
    };

    ws.onclose = function() {
        console.log('WebSocket bağlantısı kapandı');
        setTimeout(initWebSocket, 5000);
    };
}

// Docker bilgilerini güncelle
function updateDockerInfo(data) {
    // Sistem bilgilerini güncelle
    $('#docker-version').text(data.version);
    $('#cpu-count').text(data.cpu_count);
    $('#total-memory').text(formatBytes(data.total_memory));
    $('#running-containers').text(data.containers.running);
    $('#total-containers').text(data.containers.total);

    // Docker durumu
    $('.docker-status').text(data.running ? 'Çalışıyor' : 'Durduruldu')
        .removeClass('badge-success badge-danger')
        .addClass(data.running ? 'badge-success' : 'badge-danger');
    $('.docker-version').text(data.version);
    $('.docker-driver').text(data.driver);
    $('.docker-os').text(data.operating_system);
    $('.docker-kernel').text(data.kernel_version);

    // Konteyner istatistikleri
    $('.container-total').text(data.containers.total);
    $('.container-running').text(data.containers.running);
    $('.container-paused').text(data.containers.paused);
    $('.container-stopped').text(data.containers.stopped);

    // Sistem kaynakları
    $('.docker-cpu').text(data.cpu_count);
    $('.docker-memory').text(formatBytes(data.total_memory));
    $('.memory-limit').text(data.memory_limit ? 'Aktif' : 'Pasif')
        .removeClass('badge-success badge-danger')
        .addClass(data.memory_limit ? 'badge-success' : 'badge-danger');
    $('.swap-limit').text(data.swap_limit ? 'Aktif' : 'Pasif')
        .removeClass('badge-success badge-danger')
        .addClass(data.swap_limit ? 'badge-success' : 'badge-danger');

    // Disk kullanımını güncelle
    updateDiskUsage(data.disk_usage);

    // Konteynerleri güncelle
    if (data.all_containers) {
        updateContainersTable(data.all_containers);
    }
}

// Disk kullanımını güncelle
function updateDiskUsage(diskUsage) {
    const diskUsageHtml = diskUsage.map(item => {
        const used = parseFloat(item.Size);
        const reclaimable = parseFloat(item.Reclaimable.split(' ')[0]);
        const total = used + (isNaN(reclaimable) ? 0 : reclaimable);
        const percent = Math.round((used / total) * 100);

        let progressClass = 'bg-success';
        if (percent >= 90) {
            progressClass = 'bg-danger';
        } else if (percent >= 80) {
            progressClass = 'bg-warning';
        } else if (percent >= 70) {
            progressClass = 'bg-info';
        }

        return `
        <div class="col-md-3">
            <div class="disk-usage-card">
                <div class="card-body">
                    <h6 class="card-title" title="${item.Type}">${item.Type}</h6>
                    <div class="progress">
                        <div class="progress-bar ${progressClass}" role="progressbar"
                             style="width: ${percent}%"
                             aria-valuenow="${percent}"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            ${percent}%
                        </div>
                    </div>
                    <div class="progress-details">
                        <div title="Boyut: ${item.Size}">Boyut: ${item.Size}</div>
                        <div title="Aktif: ${item.Active}/${item.TotalCount}">Aktif: ${item.Active}/${item.TotalCount}</div>
                        <div title="Geri Kazanılabilir: ${item.Reclaimable}">Geri Kazanılabilir: ${item.Reclaimable}</div>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');

    $('.disk-usage').html(diskUsageHtml);
}

// Konteyner tablosunu güncelle
function updateContainersTable(containers) {
    const table = $('#containers-table').DataTable();
    const currentPage = table.page();
    table.clear();

    containers.forEach(container => {
        const isRunning = container.State.Running;
        const isPaused = container.State.Paused;

        const actions = `
            <div class="btn-group btn-group-sm">
                ${isRunning && !isPaused ? `
                    <button type="button" class="btn btn-warning docker-action" data-command="docker-stop" data-container="${container.Names}" title="Durdur">
                        <i class="fas fa-stop"></i>
                    </button>
                    <button type="button" class="btn btn-info docker-action" data-command="docker-restart" data-container="${container.Names}" title="Yeniden Başlat">
                        <i class="fas fa-sync"></i>
                    </button>
                    <button type="button" class="btn btn-secondary docker-action" data-command="docker-pause" data-container="${container.Names}" title="Duraklat">
                        <i class="fas fa-pause"></i>
                    </button>
                ` : isPaused ? `
                    <button type="button" class="btn btn-success docker-action" data-command="docker-unpause" data-container="${container.Names}" title="Devam Et">
                        <i class="fas fa-play"></i>
                    </button>
                ` : `
                    <button type="button" class="btn btn-success docker-action" data-command="docker-start" data-container="${container.Names}" title="Başlat">
                        <i class="fas fa-play"></i>
                    </button>
                `}
                <button type="button" class="btn btn-danger docker-action" data-command="docker-remove" data-container="${container.Names}" title="Sil">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        const row = table.row.add([
            container.ID.substring(0, 12),
            container.Names,
            container.Image,
            `<span class="badge badge-${isRunning ? (isPaused ? 'warning' : 'success') : 'danger'}">${container.Status}</span>`,
            container.stats ? container.stats.CPUPerc : '-',
            container.stats ? container.stats.MemUsage : '-',
            container.stats ? container.stats.NetIO : '-',
            container.stats ? container.stats.BlockIO : '-',
            container.Ports || '-',
            actions
        ]).draw(false).node();

        $(row).addClass('highlight-update');
        setTimeout(() => {
            $(row).removeClass('highlight-update');
        }, 1000);
    });

    table.page(currentPage).draw('page');
    updateLastUpdateTime('#containers-last-update');
}

// Docker butonları için event listener
$(document).on('click', '.docker-action', function() {
    const command = $(this).data('command');
    const container = $(this).data('container');
    handleDockerAction(command, container);
});

// Docker aksiyonları için fonksiyon
function handleDockerAction(command, container) {
    const ws = window.dockerWs;
    if (ws && ws.readyState === WebSocket.OPEN) {
        let title = '';
        let text = '';
        let icon = 'warning';
        let confirmButtonText = 'Evet';
        let confirmButtonColor = '#3085d6';

        switch(command) {
            case 'docker-start':
                title = 'Konteyneri Başlat';
                text = `"${container}" konteynerini başlatmak istediğinize emin misiniz?`;
                icon = 'info';
                confirmButtonColor = '#28a745';
                break;
            case 'docker-stop':
                title = 'Konteyneri Durdur';
                text = `"${container}" konteynerini durdurmak istediğinize emin misiniz?`;
                icon = 'warning';
                confirmButtonColor = '#ffc107';
                break;
            case 'docker-restart':
                title = 'Konteyneri Yeniden Başlat';
                text = `"${container}" konteynerini yeniden başlatmak istediğinize emin misiniz?`;
                icon = 'info';
                confirmButtonColor = '#17a2b8';
                break;
            case 'docker-pause':
                title = 'Konteyneri Duraklat';
                text = `"${container}" konteynerini duraklatmak istediğinize emin misiniz?`;
                icon = 'warning';
                confirmButtonColor = '#6c757d';
                break;
            case 'docker-unpause':
                title = 'Konteyneri Devam Ettir';
                text = `"${container}" konteynerini devam ettirmek istediğinize emin misiniz?`;
                icon = 'info';
                confirmButtonColor = '#28a745';
                break;
            case 'docker-remove':
                title = 'Konteyneri Sil';
                text = `"${container}" konteynerini silmek istediğinize emin misiniz?\nBu işlem geri alınamaz!`;
                icon = 'error';
                confirmButtonColor = '#dc3545';
                confirmButtonText = 'Evet, Sil';
                break;
        }

        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'İptal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                ws.send(JSON.stringify({
                    command: command,
                    container: container
                }));

                // İşlem başladı bildirimi
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'İşlem gerçekleştiriliyor...'
                });
            }
        });
    }
}

// Volumes tablosunu güncelle
function updateVolumesTable(volumes) {
    const table = $('#volumes-table').DataTable();
    const currentPage = table.page();
    table.clear();

    volumes.forEach(volume => {
        const labels = volume.Labels ? Object.entries(volume.Labels)
            .map(([key, value]) => `${key}: ${value}`)
            .join('<br>') : '-';

        const actions = `
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-info" onclick="showVolumeDetail('${volume.Name}')" title="Detaylar">
                    <i class="fas fa-info-circle"></i>
                </button>
                <button type="button" class="btn btn-danger" onclick="removeVolume('${volume.Name}')" title="Sil">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

        const row = table.row.add([
            volume.Name,
            volume.Driver,
            volume.Mountpoint,
            volume.Scope,
            labels,
            actions
        ]).draw(false).node();

        $(row).addClass('highlight-update');
        setTimeout(() => {
            $(row).removeClass('highlight-update');
        }, 1000);
    });

    table.page(currentPage).draw('page');
    updateLastUpdateTime('#volumes-last-update');
}

// Volume işlemleri için global fonksiyonlar
function createVolume() {
    Swal.fire({
        title: 'Volume Oluştur',
        html: `
            <div class="form-group text-left">
                <label for="volume-name">Volume Adı</label>
                <input type="text" class="form-control" id="volume-name" placeholder="my_volume">
            </div>
            <div class="form-group text-left">
                <label for="volume-driver">Driver</label>
                <select class="form-control" id="volume-driver">
                    <option value="local">local</option>
                    <option value="nfs">nfs</option>
                </select>
            </div>
            <div id="nfs-options" style="display: none;">
                <div class="form-group text-left">
                    <label for="nfs-device">NFS Path</label>
                    <input type="text" class="form-control" id="nfs-device" placeholder=":/path/to/dir">
                </div>
                <div class="form-group text-left">
                    <label for="nfs-address">NFS Address</label>
                    <input type="text" class="form-control" id="nfs-address" placeholder="192.168.1.1">
                </div>
            </div>
            <div class="form-group text-left">
                <label for="volume-labels">Labels (JSON)</label>
                <textarea class="form-control" id="volume-labels" rows="3" placeholder='{"environment": "production"}'></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Oluştur',
        cancelButtonText: 'İptal',
        didOpen: () => {
            $('#volume-driver').on('change', function() {
                if ($(this).val() === 'nfs') {
                    $('#nfs-options').show();
                } else {
                    $('#nfs-options').hide();
                }
            });
        },
        preConfirm: () => {
            const name = $('#volume-name').val();
            const driver = $('#volume-driver').val();
            let opts = {};
            let labels = {};

            if (!name) {
                Swal.showValidationMessage('Volume adı gerekli!');
                return false;
            }

            if (driver === 'nfs') {
                const device = $('#nfs-device').val();
                const address = $('#nfs-address').val();
                if (!device || !address) {
                    Swal.showValidationMessage('NFS için path ve adres gerekli!');
                    return false;
                }
                opts = {
                    type: 'nfs',
                    device: device,
                    o: `addr=${address}`
                };
            }

            try {
                const labelsText = $('#volume-labels').val();
                if (labelsText) {
                    labels = JSON.parse(labelsText);
                }
            } catch (e) {
                Swal.showValidationMessage('Labels geçerli bir JSON olmalı!');
                return false;
            }

            return {
                name: name,
                driver: driver,
                opts: opts,
                labels: labels
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const ws = window.dockerWs;
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    command: 'docker-volume-create',
                    ...result.value
                }));

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Volume oluşturuluyor...'
                });
            }
        }
    });
}

function removeVolume(name) {
    Swal.fire({
        title: 'Volume Sil',
        text: `"${name}" volume'ünü silmek istediğinize emin misiniz?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'İptal',
        showDenyButton: true,
        denyButtonText: 'Zorla Sil',
        denyButtonColor: '#ffc107'
    }).then((result) => {
        if (result.isConfirmed || result.isDenied) {
            const ws = window.dockerWs;
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    command: 'docker-volume-remove',
                    name: name,
                    force: result.isDenied
                }));

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Volume siliniyor...'
                });
            }
        }
    });
}

function showVolumeDetail(name) {
    const ws = window.dockerWs;
    if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            command: 'docker-volume-inspect',
            name: name
        }));

        Swal.fire({
            title: 'Volume Detayları',
            html: '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>',
            showConfirmButton: false,
            width: '800px'
        });
    }
}

function updateVolumeDetail(data) {
    const volume = data[0];
    const createdAt = new Date(volume.CreatedAt).toLocaleString('tr-TR');

    Swal.fire({
        title: 'Volume Detayları',
        html: `
            <div class="table-responsive">
                <table class="table table-sm">
                    <tr>
                        <th>İsim</th>
                        <td>${volume.Name}</td>
                    </tr>
                    <tr>
                        <th>Oluşturulma</th>
                        <td>${createdAt}</td>
                    </tr>
                    <tr>
                        <th>Driver</th>
                        <td>${volume.Driver}</td>
                    </tr>
                    <tr>
                        <th>Mountpoint</th>
                        <td>${volume.Mountpoint}</td>
                    </tr>
                    <tr>
                        <th>Scope</th>
                        <td>${volume.Scope}</td>
                    </tr>
                    <tr>
                        <th>Options</th>
                        <td><pre>${JSON.stringify(volume.Options || {}, null, 2)}</pre></td>
                    </tr>
                    <tr>
                        <th>Labels</th>
                        <td><pre>${JSON.stringify(volume.Labels || {}, null, 2)}</pre></td>
                    </tr>
                </table>
            </div>
        `,
        width: '800px'
    });
}

function pruneVolumes() {
    Swal.fire({
        title: 'Kullanılmayan Volume\'leri Temizle',
        text: 'Kullanılmayan tüm volume\'ler silinecek. Bu işlem geri alınamaz!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Evet, Temizle',
        cancelButtonText: 'İptal'
    }).then((result) => {
        if (result.isConfirmed) {
            const ws = window.dockerWs;
            if (ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    command: 'docker-volume-prune',
                    force: true
                }));

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Kullanılmayan volume\'ler temizleniyor...'
                });
            }
        }
    });
}

$(function() {
    // DataTables başlatma
    const containersTable = $('#containers-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
        },
        pageLength: 10,
        order: [[1, 'asc']]
    });

    const imagesTable = $('#images-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
        },
        pageLength: 10,
        order: [[1, 'asc']]
    });

    const volumesTable = $('#volumes-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/tr.json'
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });

    // Docker imaj butonları için event listener
    $(document).on('click', '.docker-image-action', function() {
        const command = $(this).data('command');
        const image = $(this).data('image');

        switch(command) {
            case 'inspect':
                showImageDetail(image);
                break;
            case 'tag':
                tagImage(image);
                break;
            case 'remove':
                removeImage(image);
                break;
        }
    });

    // WebSocket bağlantısını başlat
    initWebSocket();
});
</script>
@stop
