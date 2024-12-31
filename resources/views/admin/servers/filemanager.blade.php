@extends('adminlte::page')

@section('title', 'Dosya Yöneticisi - ' . $server->name)

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ $server->name }} - Dosya Yöneticisi</h1>
        <a href="{{ route('admin.servers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Geri
        </a>
    </div>
@stop

@section('content')
<div class="file-manager">

    <!-- Araç Çubuğu -->
    <div class="file-manager-toolbar">
        <div class="row align-items-center">
            <!-- Sol Araçlar -->
            <div class="col-md-6">
                <div class="btn-group">
                    <button type="button" class="btn btn-light" id="btn-parent-dir" title="Üst Dizin">
                            <i class="fas fa-level-up-alt"></i>
                        </button>
                    <button type="button" class="btn btn-light" id="btn-refresh" title="Yenile">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    <button type="button" class="btn btn-light" id="btn-home" title="Ana Dizin">
                        <i class="fas fa-home"></i>
                        </button>
                    </div>

                <div class="btn-group ml-2">
                    <button type="button" class="btn btn-success" id="btn-new-file">
                        <i class="fas fa-file-alt"></i> Yeni Dosya
                        </button>
                    <button type="button" class="btn btn-info" id="btn-new-folder">
                        <i class="fas fa-folder-plus"></i> Yeni Klasör
                        </button>
                </div>
            </div>

            <!-- Sağ Araçlar -->
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary mr-2" id="btn-upload" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-cloud-upload-alt"></i> Yükle
                        </button>

                        <div class="btn-group">
                        <button type="button" class="btn btn-light" data-view="grid" title="Grid Görünüm">
                            <i class="fas fa-th-large"></i>
                            </button>
                        <button type="button" class="btn btn-light" data-view="list" title="Liste Görünüm">
                            <i class="fas fa-list"></i>
                        </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Yol Çubuğu -->
    <div class="file-manager-breadcrumb px-3 py-2 bg-light border-bottom">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 py-0 bg-transparent" id="path-breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#" data-path="/" class="text-primary">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                    </ol>
                </nav>
    </div>

    <!-- İçerik Alanı -->
    <div class="file-manager-content p-3">
        <!-- Grid Görünümü -->
                <div id="files-grid" class="row">
                    <!-- JavaScript ile doldurulacak -->
                </div>

        <!-- Liste Görünümü -->
                <div id="files-list" class="d-none">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 40px"></th>
                                    <th>İsim</th>
                                    <th>Boyut</th>
                                    <th>İzinler</th>
                                    <th>Sahip</th>
                                    <th>Grup</th>
                                    <th>Değiştirilme</th>
                            <th style="width: 150px">İşlemler</th>
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

<!-- Dosya Yükleme Modalı -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dosya Yükle</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" class="dropzone" id="fileUploadDropzone">
                    <div class="dz-message">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                        <h4>Dosyaları buraya sürükleyin</h4>
                        <span class="text-muted">veya tıklayarak seçin</span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- Dosya Düzenleme Modalı -->
<div class="modal fade" id="fileEditModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dosya Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <textarea id="file-content"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="btn-save-file">Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- İzin Düzenleme Modalı -->
<div class="modal fade" id="permissionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">İzinleri Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6>Sahip</h6>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="owner-read">
                            <label class="custom-control-label" for="owner-read">Okuma</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="owner-write">
                            <label class="custom-control-label" for="owner-write">Yazma</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="owner-execute">
                            <label class="custom-control-label" for="owner-execute">Çalıştırma</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Grup</h6>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="group-read">
                            <label class="custom-control-label" for="group-read">Okuma</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="group-write">
                            <label class="custom-control-label" for="group-write">Yazma</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="group-execute">
                            <label class="custom-control-label" for="group-execute">Çalıştırma</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6>Diğer</h6>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="other-read">
                            <label class="custom-control-label" for="other-read">Okuma</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="other-write">
                            <label class="custom-control-label" for="other-write">Yazma</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="other-execute">
                            <label class="custom-control-label" for="other-execute">Çalıştırma</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="btn-save-permissions">Kaydet</button>
            </div>
        </div>
    </div>
</div>

<!-- Sahiplik Düzenleme Modalı -->
<div class="modal fade" id="ownershipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sahipliği Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="owner">Sahip</label>
                    <input type="text" class="form-control" id="owner">
                </div>
                <div class="form-group">
                    <label for="group">Grup</label>
                    <input type="text" class="form-control" id="group">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                <button type="button" class="btn btn-primary" id="btn-save-ownership">Kaydet</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/lib/codemirror.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/theme/monokai.css">

<style>
/* Modern dosya yöneticisi stilleri */
.file-manager {
    background: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
    z-index: 1;
}

.file-manager-header {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.file-manager-toolbar {
    padding: 0.5rem 1rem;
    background: #fff;
    border-bottom: 1px solid #dee2e6;
}

.file-grid-item {
    width: 150px;
    height: 170px;
    margin: 10px;
    padding: 15px;
    text-align: center;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    padding-top: 35px;
    cursor: pointer;
}

.file-grid-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.file-grid-item.selected {
    background-color: #e3f2fd;
    border: 2px solid #2196f3;
}

.file-icon {
    font-size: 3rem;
    margin-bottom: 10px;
}

.file-name {
    font-size: 0.9rem;
    word-break: break-word;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
    margin: 0 auto;
}

.file-info {
    font-size: 0.8rem;
    color: #6c757d;
}

.dropzone {
    border: 2px dashed #2196f3;
    border-radius: 8px;
    background: #f8f9fa;
    min-height: 200px;
    padding: 20px;
}

.cm-editor {
    height: 500px;
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

/* Animasyonlar */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.fade-in {
    animation: fadeIn 0.3s ease-in;
}

/* Responsive tasarım */
@media (max-width: 768px) {
    .file-grid-item {
        width: calc(50% - 20px);
    }
}

@media (max-width: 576px) {
    .file-grid-item {
        width: calc(100% - 20px);
    }
}

.file-grid-item .dropdown {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.file-grid-item:hover .dropdown {
    opacity: 1;
}

.file-grid-item .dropdown .btn-light {
    padding: 2px 8px;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.file-grid-item .dropdown .btn-light:hover {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.file-grid-item .dropdown-menu {
    position: absolute;
    min-width: 200px;
    margin-top: 5px;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.1);
    padding: 0.5rem 0;
    z-index: 9999;
    background: #fff;
}

.file-grid-item .dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    color: #333;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.file-grid-item .dropdown-item:hover {
    background-color: #f8f9fa;
}

.file-grid-item .dropdown-item.text-danger:hover {
    background-color: #fee;
}

/* Liste görünümü için dropdown stilleri */
#files-list .dropdown {
    opacity: 0;
    transition: opacity 0.2s ease;
}

#files-list tr:hover .dropdown {
    opacity: 1;
}

#files-list .dropdown .btn-light {
    padding: 2px 6px;
    font-size: 12px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

#files-list .dropdown-menu {
    position: absolute;
    min-width: 160px;
    margin-top: 2px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.1);
    padding: 0.25rem 0;
    z-index: 9999;
    background: #fff;
    right: 0;
    left: auto;
    font-size: 0.875rem;
}

#files-list .dropdown-item {
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
    color: #333;
    transition: all 0.2s ease;
}

#files-list .dropdown-item i {
    width: 16px;
    margin-right: 0.5rem;
    font-size: 0.875rem;
}

#files-list .dropdown-divider {
    margin: 0.25rem 0;
}

/* Ortak dropdown stilleri */
.dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    color: #333;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-item.text-danger:hover {
    background-color: #fee;
}

/* Dosya yöneticisi için genel z-index ayarları */
.file-manager {
    position: relative;
}

.file-manager-content {
    position: relative;
}

/* Bootstrap dropdown override */
.dropdown-menu {
    float: none;
    position: absolute !important;
}

/* Dropdown menü konteyneri için stil */
.dropdown {
    position: static;
}

.dropdown-menu.show {
    display: block !important;
    z-index: 9999 !important;
}

/* Dropdown için yeni stiller */
.file-grid-item .dropdown {
    position: absolute;
    top: 5px;
    right: 5px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.file-grid-item:hover .dropdown {
    opacity: 1;
}

/* Dropdown menü için özel stiller */
.file-grid-item .dropdown-menu {
    position: absolute;
    min-width: 200px;
    margin-top: 5px;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0, 0, 0, 0.1);
    padding: 0.5rem 0;
    z-index: 9999;
    background: #fff;
}

/* Grid container için stil */
#files-grid {
    position: relative;
}

/* Grid öğesi için hover durumu */
.file-grid-item:hover {
    z-index: 1000;
}

/* Dropdown buton stili */
.file-grid-item .dropdown .btn-light {
    padding: 2px 8px;
    font-size: 14px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Dropdown öğeleri için stiller */
.file-grid-item .dropdown-item {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    color: #333;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.file-grid-item .dropdown-item:hover {
    background-color: #f8f9fa;
}

.file-grid-item .dropdown-item.text-danger:hover {
    background-color: #fee;
}

/* Bootstrap dropdown override */
.dropdown-menu.show {
    display: block !important;
    z-index: 9999 !important;
}

/* Dosya yöneticisi için genel z-index ayarları */
.file-manager {
    position: relative;
}

.file-manager-content {
    position: relative;
}

/* Liste görünümünde isim sütunu için özel genişlik */
#files-list td:nth-child(2) {
    max-width: 300px;
    min-width: 200px;
}

/* Tooltip ekleme */
.file-name, #files-list td {
    position: relative;
}

.file-name[title], #files-list td[title] {
    cursor: help;
}
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/lib/codemirror.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/mode/javascript/javascript.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/mode/xml/xml.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/mode/css/css.js"></script>
<script src="https://cdn.jsdelivr.net/npm/codemirror@5.65.2/mode/php/php.js"></script>
<script>
// Global değişkenler
let currentView = 'grid';
let currentPath = '/';
let currentFile = null;
let editor = null;
let dropzone = null;  // Dropzone için global değişken

// Toast bildirimi için yardımcı fonksiyon
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

// FileManager nesnesini tanımla
const FileManager = {
    ws: null,
    infoCallback: null,

    // WebSocket mesajı gönder
    sendCommand: function(command, data, callback) {
        if (callback) {
            this.infoCallback = callback;
        }
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify({
                command: command,
                ...data
            }));
        }
    },

    // Dizin listele
    listDirectory: function(path) {
        this.sendCommand('file-list', { path });
    },

    // Dosya bilgisi al
    getFileInfo: function(path) {
        this.sendCommand('file-info', { path });
    },

    // Dosya oku
    readFile: function(path) {
        this.sendCommand('file-read', { path });
    },

    // Dosya yaz
    writeFile: function(path, content, append = false) {
        this.sendCommand('file-write', { path, content, append });
    },

    // Dosya sil
    deleteFile: function(path, recursive = false) {
        this.sendCommand('file-delete', { path, recursive });
    },

    // Dosya taşı
    moveFile: function(source, target) {
        this.sendCommand('file-move', { source, target });
    },

    // Dosya kopyala
    copyFile: function(source, target) {
        this.sendCommand('file-copy', { source, target });
    },

    // İzinleri değiştir
    chmod: function(path, mode) {
        this.sendCommand('file-chmod', { path, mode });
    },

    // Sahipliği değiştir
    chown: function(path, user, group) {
        this.sendCommand('file-chown', { path, user, group });
    },

    // Klasör oluştur
    mkdir: function(path, mode = '755', exist_ok = false) {
        this.sendCommand('file-mkdir', { path, mode, exist_ok });
    },

    // Dosya ara
    search: function(path, pattern, recursive = true) {
        this.sendCommand('file-search', { path, pattern, recursive });
    },

    // Dosya indir
    download: function(path) {
        this.sendCommand('file-download', { path });
    },

    // Dosya yükle
    upload: function(path, content) {
        this.sendCommand('file-upload', { path, content });
    },

    // WebSocket yanıtlarını işle
    handleResponse: function(response) {
        if (!response.success) {
            Swal.fire({
                icon: 'error',
                title: 'Hata',
                text: response.error,
                confirmButtonText: 'Tamam'
            });
            return;
        }

                switch(response.command) {
                    case 'file-list':
                updateFileList({
                    path: response.data.path || '/',
                    items: response.data.items || []
                });
                        break;

                    case 'file-read':
                        showFileContent(response.data);
                        break;

                    case 'file-write':
                Toast.fire({
                    icon: 'success',
                    title: 'Dosya kaydedildi'
                });
                $('#fileEditModal').modal('hide');
                this.listDirectory(currentPath);
                break;

                    case 'file-delete':
                Toast.fire({
                    icon: 'success',
                    title: 'Dosya silindi'
                });
                this.listDirectory(currentPath);
                break;

                    case 'file-move':
                    case 'file-copy':
                Toast.fire({
                    icon: 'success',
                    title: 'İşlem tamamlandı'
                });
                this.listDirectory(currentPath);
                break;

            case 'file-chmod':
                        Toast.fire({
                            icon: 'success',
                    title: 'İzinler güncellendi'
                });
                this.listDirectory(currentPath);
                break;

            case 'file-chown':
                Toast.fire({
                    icon: 'success',
                    title: 'Sahiplik güncellendi'
                });
                this.listDirectory(currentPath);
                        break;

            case 'file-info':
                if (this.infoCallback) {
                    this.infoCallback(response.data);
                    this.infoCallback = null;
            } else {
                    // Normal bilgi gösterimi için
                Swal.fire({
                        title: 'Dosya Bilgileri',
                        html: `
                            <div class="text-left">
                                <p><strong>İsim:</strong> ${response.data.name}</p>
                                <p><strong>Tip:</strong> ${response.data.type}</p>
                                <p><strong>Boyut:</strong> ${formatBytes(response.data.size)}</p>
                                <p><strong>İzinler:</strong> ${formatPermissions(response.data.permissions)}</p>
                                <p><strong>Sahip:</strong> ${response.data.owner}</p>
                                <p><strong>Grup:</strong> ${response.data.group}</p>
                                <p><strong>Değiştirilme:</strong> ${formatDate(new Date(response.data.modified))}</p>
                                ${response.data.is_symlink ? '<p><strong>Sembolik Link</strong></p>' : ''}
                                ${response.data.mimetype ? `<p><strong>MIME Tipi:</strong> ${response.data.mimetype}</p>` : ''}
                            </div>
                        `,
                    confirmButtonText: 'Tamam'
                });
            }
                break;

            case 'file-search':
                showSearchResults(response.data);
                break;

            case 'file-mkdir':
                Toast.fire({
                    icon: 'success',
                    title: 'Klasör oluşturuldu'
                });
                this.listDirectory(currentPath);
                break;

            case 'file-upload':
                Toast.fire({
                    icon: 'success',
                    title: 'Dosya yüklendi'
                });
                this.listDirectory(currentPath);
                // Dropzone'u temizle ve modalı kapat
                if (dropzone) {
                    dropzone.removeAllFiles(true);  // true parametresi ekledik
                    $('#uploadModal').modal('hide');
                }
                break;

            case 'file-download':
                // Base64 içeriği indirme bağlantısına dönüştür
                const link = document.createElement('a');
                link.href = 'data:application/octet-stream;base64,' + response.data.content;
                link.download = response.data.name;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                break;

            case 'file-exists':
                if (this.existsCallback) {
                    this.existsCallback(response.data);
                    this.existsCallback = null;
                }
                break;
        }
    },

    // WebSocket bağlantısını başlat
    init: function() {
        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
        const url = `${protocol}://{{ $server->ip_address }}:{{ $server->ws_port }}/?token={{ auth()->user()->api_token }}`;

        this.ws = new WebSocket(url);

        this.ws.onopen = () => {
            console.log('WebSocket bağlantısı açıldı');
            this.listDirectory(currentPath);
        };

        this.ws.onmessage = (event) => {
            try {
                const response = JSON.parse(event.data);
                this.handleResponse(response);
        } catch (e) {
            console.error('WebSocket mesaj hatası:', e);
        }
    };

        this.ws.onerror = (error) => {
        console.error('WebSocket hatası:', error);
            Swal.fire({
                icon: 'error',
                title: 'Bağlantı Hatası',
                text: 'Sunucu ile bağlantı kurulamadı',
                confirmButtonText: 'Tamam'
            });
        };

        this.ws.onclose = () => {
        console.log('WebSocket bağlantısı kapandı');
            setTimeout(() => this.init(), 5000);
        };
    }
};

// Sayfa yüklendiğinde WebSocket bağlantısını başlat
$(document).ready(function() {
    FileManager.init();
    // ... diğer başlangıç kodları

    // Yeni dosya oluştur
    $('#btn-new-file').click(function() {
        Swal.fire({
            title: 'Yeni Dosya',
            input: 'text',
            inputLabel: 'Dosya Adı',
            showCancelButton: true,
            confirmButtonText: 'Oluştur',
            cancelButtonText: 'İptal',
            inputValidator: (value) => {
                if (!value) return 'Dosya adı gerekli!';
                if (/[<>:"/\\|?*]/.test(value)) {
                    return 'Dosya adında geçersiz karakterler var!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                FileManager.sendCommand('file-write', {
                    path: currentPath + '/' + result.value
                });
            }
        });
    });

    // Yeni klasör oluştur
    $('#btn-new-folder').click(function() {
        Swal.fire({
            title: 'Yeni Klasör',
            input: 'text',
            inputLabel: 'Klasör Adı',
            showCancelButton: true,
            confirmButtonText: 'Oluştur',
            cancelButtonText: 'İptal',
            inputValidator: (value) => {
                if (!value) return 'Klasör adı gerekli!';
                if (/[<>:"/\\|?*]/.test(value)) {
                    return 'Klasör adında geçersiz karakterler var!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                FileManager.sendCommand('file-mkdir', {
                    path: currentPath + '/' + result.value
                });
            }
        });
    });

    // Dosya yükleme modalını aç
    $('#btn-upload').click(function() {
        $('#uploadModal').modal('show');
    });

    // Dropzone yapılandırması
    dropzone = new Dropzone('#fileUploadDropzone', {
        url: '#',  // WebSocket kullanacağımız için dummy URL
        autoProcessQueue: false,
        addRemoveLinks: true,
        parallelUploads: 1,
        maxFilesize: 100, // MB
        dictDefaultMessage: 'Dosyaları buraya sürükleyin veya tıklayarak seçin',
        dictRemoveFile: 'Sil',
        init: function() {
            this.on('addedfile', function(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64 = e.target.result.split(',')[1];
                    FileManager.sendCommand('file-upload', {
                        path: currentPath + '/' + file.name,
                        content: base64
                    });
                };
                reader.readAsDataURL(file);
            });
        }
    });

    // Üst dizine git
    $('#btn-parent-dir').click(function() {
        if (currentPath === '/') return;

        const parts = currentPath.split('/').filter(Boolean);
        parts.pop();
        currentPath = '/' + parts.join('/');
        FileManager.listDirectory(currentPath);
    });

    // Dizini yenile
    $('#btn-refresh').click(function() {
        FileManager.listDirectory(currentPath);
    });

    // Ana dizine git
    $('#btn-home').click(function() {
        currentPath = '/';
        FileManager.listDirectory(currentPath);
    });

    // FileManager nesnesine listDirectory metodunu ekle
    FileManager.listDirectory = function(path) {
        this.sendCommand('file-list', { path });
    };

    // Dosya kaydetme butonu için olay dinleyici
    $('#btn-save-file').click(function() {
        if (!currentFile) return;

        const content = editor.getValue();
        FileManager.sendCommand('file-write', {
            path: currentFile,
            content: btoa(content),  // İçeriği Base64'e çevir
            append: false  // Üzerine yaz
        });
    });

    // Modal kapandığında temizlik yap
    $('#fileEditModal').on('hidden.bs.modal', function() {
        currentFile = null;
        if (editor) {
            editor.setValue('');
        }
    });
});

// Mevcut dizini yükle
function loadCurrentDirectory() {
    if (FileManager.ws && FileManager.ws.readyState === WebSocket.OPEN) {
        FileManager.sendCommand('file-list', { path: currentPath });
    }
}

// Dosya listesini güncelle
function updateFileList(data) {
    const gridContainer = $('#files-grid').empty();
    const listContainer = $('#files-list tbody').empty();

    data.items.forEach(item => {
        // Dosya tipine göre simge ve renk belirle
        let icon, color;
        if (item.type === 'directory') {
            icon = 'fa-folder';
            color = 'text-warning';
        } else {
            icon = getFileIcon(item.name);
            color = getFileColor(item.name);
        }

        // Grid öğesi oluştur
        const gridItem = $(`
            <div class="col-auto fade-in">
                <div class="file-grid-item" data-path="${item.path}" data-type="${item.type}">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                                </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            ${item.type === 'file' ? `
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editFile('${item.path}')">
                                    <i class="fas fa-edit mr-2"></i> Düzenle
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="downloadFile('${item.path}')">
                                    <i class="fas fa-download mr-2"></i> İndir
                                </a>
                            ` : ''}
                            <a class="dropdown-item" href="javascript:void(0)" onclick="showInfo('${item.path}')">
                                <i class="fas fa-info-circle mr-2"></i> Bilgi
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="copyFile('${item.path}')">
                                <i class="fas fa-copy mr-2"></i> Kopyala
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="moveFile('${item.path}')">
                                <i class="fas fa-cut mr-2"></i> Taşı
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="renameFile('${item.path}')">
                                <i class="fas fa-pencil-alt mr-2"></i> Yeniden Adlandır
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="changePermissions('${item.path}')">
                                <i class="fas fa-key mr-2"></i> İzinler
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="changeOwnership('${item.path}')">
                                <i class="fas fa-user mr-2"></i> Sahiplik
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteFile('${item.path}')">
                                <i class="fas fa-trash mr-2"></i> Sil
                            </a>
                        </div>
                    </div>
                    <div class="file-content" onclick="handleItemClick('${item.path}', '${item.type}')">
                        <div class="file-icon">
                            <i class="fas ${icon} ${color}"></i>
                        </div>
                        <div class="file-name">${item.name}</div>
                        <div class="file-info">
                            ${item.type === 'file' ? formatBytes(item.size) : ''}
                        </div>
                    </div>
                </div>
            </div>
        `);

        // Liste öğesi oluştur
        const listItem = $(`
            <tr>
                <td><i class="fas ${icon} ${color}"></i></td>
                <td onclick="handleItemClick('${item.path}', '${item.type}')" style="cursor: pointer">${item.name}</td>
                <td>${formatBytes(item.size)}</td>
                <td>${formatPermissions(item.permissions)}</td>
                <td>${item.owner}</td>
                <td>${item.group}</td>
                <td>${formatDate(new Date(item.modified))}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                            </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            ${item.type === 'file' ? `
                                <a class="dropdown-item" href="javascript:void(0)" onclick="editFile('${item.path}')">
                                    <i class="fas fa-edit mr-2"></i> Düzenle
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="downloadFile('${item.path}')">
                                    <i class="fas fa-download mr-2"></i> İndir
                                </a>
                        ` : ''}
                            <a class="dropdown-item" href="javascript:void(0)" onclick="showInfo('${item.path}')">
                                <i class="fas fa-info-circle mr-2"></i> Bilgi
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="copyFile('${item.path}')">
                                <i class="fas fa-copy mr-2"></i> Kopyala
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="moveFile('${item.path}')">
                                <i class="fas fa-cut mr-2"></i> Taşı
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="renameFile('${item.path}')">
                                <i class="fas fa-pencil-alt mr-2"></i> Yeniden Adlandır
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="changePermissions('${item.path}')">
                                <i class="fas fa-key mr-2"></i> İzinler
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="changeOwnership('${item.path}')">
                                <i class="fas fa-user mr-2"></i> Sahiplik
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteFile('${item.path}')">
                                <i class="fas fa-trash mr-2"></i> Sil
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
        `);

        gridContainer.append(gridItem);
        listContainer.append(listItem);
    });

    // Breadcrumb'ı güncelle
    updateBreadcrumb(data.path);
}

// Breadcrumb güncelleme fonksiyonu
function updateBreadcrumb(path) {
    // Path undefined veya null ise kök dizin olarak kabul et
    if (!path) path = '/';

    const breadcrumb = $('#path-breadcrumb').empty();

    // Ana dizin
    breadcrumb.append(`
        <li class="breadcrumb-item">
            <a href="javascript:void(0)" onclick="handleItemClick('/', 'directory')">
                <i class="fas fa-home"></i>
            </a>
        </li>
    `);

    // Kök dizin ise başka bir şey eklemeye gerek yok
    if (path === '/') return;

    // Alt dizinler
    const parts = path.split('/').filter(Boolean);
    let currentPath = '';
    parts.forEach((part, index) => {
        currentPath += '/' + part;
        breadcrumb.append(`
            <li class="breadcrumb-item">
                <a href="javascript:void(0)" onclick="handleItemClick('${currentPath}', 'directory')">${part}</a>
            </li>
        `);
    });
}

// Dosya/klasör tıklama işleyicisi
function handleItemClick(path, type) {
    if ($(event.target).closest('.dropdown-menu').length) {
        return; // Dropdown menü içindeki tıklamaları yoksay
    }

    if (type === 'directory') {
        currentPath = path;
        FileManager.listDirectory(currentPath);
    } else {
        editFile(path);
    }
}

// Görünüm modu değiştirme
function updateViewMode() {
    if (currentView === 'grid') {
        $('#files-grid').removeClass('d-none');
        $('#files-list').addClass('d-none');
    } else {
        $('#files-grid').addClass('d-none');
        $('#files-list').removeClass('d-none');
    }
}

// İzinleri formatla
function formatPermissions(perms) {
    const permissions = parseInt(perms, 8);
    let result = '';

    // Sahip izinleri
    result += (permissions & 0o400) ? 'r' : '-';
    result += (permissions & 0o200) ? 'w' : '-';
    result += (permissions & 0o100) ? 'x' : '-';

    // Grup izinleri
    result += (permissions & 0o040) ? 'r' : '-';
    result += (permissions & 0o020) ? 'w' : '-';
    result += (permissions & 0o010) ? 'x' : '-';

    // Diğer izinleri
    result += (permissions & 0o004) ? 'r' : '-';
    result += (permissions & 0o002) ? 'w' : '-';
    result += (permissions & 0o001) ? 'x' : '-';

    return result;
}

// İzinleri göster
function showPermissions(permissions) {
    const perms = parseInt(permissions, 8);

    $('#owner-read').prop('checked', !!(perms & 0o400));
    $('#owner-write').prop('checked', !!(perms & 0o200));
    $('#owner-execute').prop('checked', !!(perms & 0o100));

    $('#group-read').prop('checked', !!(perms & 0o040));
    $('#group-write').prop('checked', !!(perms & 0o020));
    $('#group-execute').prop('checked', !!(perms & 0o010));

    $('#other-read').prop('checked', !!(perms & 0o004));
    $('#other-write').prop('checked', !!(perms & 0o002));
    $('#other-execute').prop('checked', !!(perms & 0o001));
}

// Dosya indirme işlemi
function downloadFile(path) {
    FileManager.download(path);
}

// Dosya yeniden adlandırma
function renameFile(path) {
    const name = path.split('/').pop();

    Swal.fire({
        title: 'Yeniden Adlandır',
        input: 'text',
        inputLabel: 'Yeni Ad',
        inputValue: name,
        showCancelButton: true,
        confirmButtonText: 'Kaydet',
        cancelButtonText: 'İptal',
        inputValidator: (value) => {
            if (!value) return 'Yeni ad gerekli!';
            if (value === name) return 'Farklı bir ad girin!';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const newPath = path.replace(name, result.value);
            FileManager.moveFile(path, newPath);
        }
    });
}

// Arama sonuçlarını göster
function showSearchResults(data) {
    updateFileList({
        path: currentPath,
        items: data.matches
    });

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    Toast.fire({
        icon: 'info',
        title: `${data.matches.length} sonuç bulundu`
    });
}

// Dosya bilgilerini göster
function showFileInfo(data) {
    Swal.fire({
        title: data.name,
        html: `
            <div class="text-left">
                <p><strong>Tip:</strong> ${data.type}</p>
                <p><strong>Boyut:</strong> ${formatBytes(data.size)}</p>
                <p><strong>İzinler:</strong> ${formatPermissions(data.permissions)}</p>
                <p><strong>Sahip:</strong> ${data.owner}</p>
                <p><strong>Grup:</strong> ${data.group}</p>
                <p><strong>Oluşturulma:</strong> ${formatDate(new Date(data.created))}</p>
                <p><strong>Değiştirilme:</strong> ${formatDate(new Date(data.modified))}</p>
                <p><strong>Erişim:</strong> ${formatDate(new Date(data.accessed))}</p>
                ${data.is_symlink ? '<p><strong>Sembolik Link</strong></p>' : ''}
                ${data.mimetype ? `<p><strong>MIME Tipi:</strong> ${data.mimetype}</p>` : ''}
            </div>
        `,
        confirmButtonText: 'Tamam'
    });
}

// İzinleri göster
function showPermissions(permissions) {
    const perms = parseInt(permissions, 8);

    $('#owner-read').prop('checked', !!(perms & 0o400));
    $('#owner-write').prop('checked', !!(perms & 0o200));
    $('#owner-execute').prop('checked', !!(perms & 0o100));

    $('#group-read').prop('checked', !!(perms & 0o040));
    $('#group-write').prop('checked', !!(perms & 0o020));
    $('#group-execute').prop('checked', !!(perms & 0o010));

    $('#other-read').prop('checked', !!(perms & 0o004));
    $('#other-write').prop('checked', !!(perms & 0o002));
    $('#other-execute').prop('checked', !!(perms & 0o001));
}

// Dosya/klasör tıklama işleyicisi
function handleItemClick(path, type) {
    if ($(event.target).closest('.dropdown-menu').length) {
        return; // Dropdown menü içindeki tıklamaları yoksay
    }

    if (type === 'directory') {
        currentPath = path;
        FileManager.listDirectory(currentPath);
    } else {
        editFile(path);
    }
}

// Görünüm değiştirme butonları için olay dinleyicileri
$(document).ready(function() {
    // Görünüm değiştirme
    $('.btn-group button[data-view]').click(function() {
        const view = $(this).data('view');
        $('.btn-group button[data-view]').removeClass('active');
        $(this).addClass('active');
        currentView = view;
        updateViewMode();
    });
});

// Dosya işlemleri için global fonksiyonlar
function moveFile(path) {
    Swal.fire({
        title: 'Taşı',
        input: 'text',
        inputLabel: 'Hedef Yol',
        inputValue: path,
        showCancelButton: true,
        confirmButtonText: 'Taşı',
        cancelButtonText: 'İptal',
        inputValidator: (value) => {
            if (!value) return 'Hedef yol gerekli!';
            if (value === path) return 'Farklı bir yol girin!';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            FileManager.sendCommand('file-move', {
                    source: path,
                    target: result.value
            });
        }
    });
}

function copyFile(path) {
    Swal.fire({
        title: 'Kopyala',
        input: 'text',
        inputLabel: 'Hedef Yol',
        inputValue: path,
        showCancelButton: true,
        confirmButtonText: 'Kopyala',
        cancelButtonText: 'İptal',
        inputValidator: (value) => {
            if (!value) return 'Hedef yol gerekli!';
            if (value === path) return 'Farklı bir yol girin!';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            FileManager.sendCommand('file-copy', {
                    source: path,
                    target: result.value
            });
        }
    });
}

function deleteFile(path) {
    Swal.fire({
        title: 'Emin misiniz?',
        text: `"${path}" silinecek!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Evet, Sil',
        cancelButtonText: 'İptal',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            FileManager.sendCommand('file-delete', {
                    path: path,
                    recursive: true
            });
        }
    });
}

// İzinleri değiştir
function changePermissions(path) {
    // Önce dosya bilgilerini al ve izinleri göster
    FileManager.sendCommand('file-info', { path }, function(data) {
        // İzinleri checkbox'lara uygula
        showPermissions(data.permissions);

        // Modalı aç
    $('#permissionsModal').modal('show');

        // İzinleri kaydet butonu için olay dinleyici
        $('#btn-save-permissions').one('click', function() {
            const perms = calculatePermissions();
            FileManager.sendCommand('file-chmod', {
                path: path,
                mode: perms.toString(8)
            });
        $('#permissionsModal').modal('hide');
});
    });
}

function changeOwnership(path) {
    FileManager.sendCommand('file-info', { path });
    $('#ownershipModal').modal('show');

    // Sahipliği kaydet butonu için olay dinleyici
    $('#btn-save-ownership').one('click', function() {
        FileManager.sendCommand('file-chown', {
            path: path,
            user: $('#owner').val(),
            group: $('#group').val()
        });
        $('#ownershipModal').modal('hide');
    });
}

function editFile(path) {
    currentFile = path;
    FileManager.sendCommand('file-read', { path });
    $('#fileEditModal').modal('show');
}

// İzinleri hesapla
function calculatePermissions() {
    let perms = 0;

    if ($('#owner-read').prop('checked')) perms |= 0o400;
    if ($('#owner-write').prop('checked')) perms |= 0o200;
    if ($('#owner-execute').prop('checked')) perms |= 0o100;

    if ($('#group-read').prop('checked')) perms |= 0o040;
    if ($('#group-write').prop('checked')) perms |= 0o020;
    if ($('#group-execute').prop('checked')) perms |= 0o010;

    if ($('#other-read').prop('checked')) perms |= 0o004;
    if ($('#other-write').prop('checked')) perms |= 0o002;
    if ($('#other-execute').prop('checked')) perms |= 0o001;

    return perms;
}

// Yardımcı fonksiyonlar
function formatBytes(bytes, decimals = 2) {
    if (!bytes || bytes === 0) return '0 Bytes';

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
        minute: '2-digit'
    });
}

// Dosya türüne göre simge belirle
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    switch(ext) {
        case 'pdf': return 'fa-file-pdf';
        case 'doc':
        case 'docx': return 'fa-file-word';
        case 'xls':
        case 'xlsx': return 'fa-file-excel';
        case 'ppt':
        case 'pptx': return 'fa-file-powerpoint';
        case 'zip':
        case 'rar':
        case 'tar':
        case 'gz': return 'fa-file-archive';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif': return 'fa-file-image';
        case 'mp3':
        case 'wav': return 'fa-file-audio';
        case 'mp4':
        case 'avi': return 'fa-file-video';
        case 'php': return 'fa-php';
        case 'js': return 'fa-js';
        case 'css': return 'fa-css3';
        case 'html': return 'fa-html5';
        case 'json': return 'fa-file-code';
        case 'xml': return 'fa-file-code';
        case 'txt': return 'fa-file-alt';
        case 'md': return 'fa-file-alt';
        case 'sh': return 'fa-terminal';
        case 'py': return 'fa-python';
        case 'java': return 'fa-java';
        default: return 'fa-file';
    }
}

// Dosya türüne göre renk belirle
function getFileColor(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    switch(ext) {
        case 'pdf': return 'text-danger';
        case 'doc':
        case 'docx': return 'text-primary';
        case 'xls':
        case 'xlsx': return 'text-success';
        case 'ppt':
        case 'pptx': return 'text-warning';
        case 'zip':
        case 'rar':
        case 'tar':
        case 'gz': return 'text-secondary';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif': return 'text-info';
        case 'mp3':
        case 'wav': return 'text-success';
        case 'mp4':
        case 'avi': return 'text-danger';
        case 'php': return 'text-purple';
        case 'js': return 'text-warning';
        case 'css': return 'text-info';
        case 'html': return 'text-danger';
        case 'json': return 'text-dark';
        case 'xml': return 'text-primary';
        case 'txt': return 'text-secondary';
        case 'md': return 'text-info';
        case 'sh': return 'text-success';
        case 'py': return 'text-primary';
        case 'java': return 'text-danger';
        default: return 'text-secondary';
    }
}

// Dosya yükleme için Dropzone yapılandırması
Dropzone.autoDiscover = false;

// Dosya içeriğini göster
function showFileContent(data) {
    if (!editor) {
        editor = CodeMirror.fromTextArea(document.getElementById('file-content'), {
            lineNumbers: true,
            mode: 'application/x-httpd-php',
            theme: 'monokai',
            indentUnit: 4,
            smartIndent: true,
            lineWrapping: true,
            foldGutter: true,
            gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
            matchBrackets: true,
            autoCloseBrackets: true,
            extraKeys: {
                'Ctrl-Space': 'autocomplete'
            }
        });
    }

    // Base64 içeriği çöz ve editöre yükle
    const content = atob(data.content);
    editor.setValue(content);
    editor.refresh();
}

// Dosya/Klasör bilgilerini göster
function showInfo(path) {
    FileManager.sendCommand('file-info', { path });
}
</script>
@stop
