@extends('layouts.siswa')

@section('title', 'Absensi - Sistem Manajemen Siswa')

@section('page-title', 'Absensi')
@section('page-subtitle', 'Catat kehadiran dan kelola riwayat absensi Anda')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/presensi.css') }}">
@endsection

@section('content')
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">Form Absensi Hari Ini</h2>
            <p class="form-date" id="currentDate">Minggu, 16 November 2025</p>
        </div>

        <form id="absensiForm">
            {{-- Status Selection Component --}}
            @include('components.presensi.status-selection')

            {{-- Form Izin Component --}}
            @include('components.presensi.form-izin')

            {{-- Form Sakit Component --}}
            @include('components.presensi.form-sakit')

            {{-- Upload Photo (TIDAK PAKAI COMPONENT) --}}
            <div class="upload-section">
                <label class="form-label">Upload Foto</label>
                <div class="upload-area" id="uploadArea">
                    <i class="bi bi-camera-fill upload-icon"></i>
                    <p class="upload-text">Klik untuk Upload Foto</p>
                    <p class="upload-subtext">JPG, PNG maksimal 5MB</p>
                    <input type="file" id="fileInput" class="file-input" accept="image/jpeg,image/png,image/jpg">
                </div>
                <div class="preview-container" id="previewContainer">
                    <img id="previewImage" class="preview-image" alt="Preview">
                    <button type="button" class="remove-image" id="removeImage">
                        <i class="bi bi-x-circle"></i> Hapus
                    </button>
                </div>
            </div>

            {{-- Mood Form Component --}}
            @include('components.presensi.mood-form')

            <button type="submit" class="btn-submit">Kirim Absensi</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Status Change Handler
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const formIzin = document.getElementById('formIzin');
    const formSakit = document.getElementById('formSakit');

    statusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            formIzin.classList.remove('show');
            formSakit.classList.remove('show');

            if (this.value === 'izin') {
                formIzin.classList.add('show');
            } else if (this.value === 'sakit') {
                formSakit.classList.add('show');
            }
        });
    });

    // File Upload Handlers
    setupFileUpload('uploadArea', 'fileInput', 'previewContainer', 'previewImage', 'removeImage');
    setupFileUpload('uploadAreaIzin', 'fileInputIzin', 'previewContainerIzin', 'previewImageIzin', 'removeImageIzin');
    setupFileUpload('uploadAreaSakit', 'fileInputSakit', 'previewContainerSakit', 'previewImageSakit', 'removeImageSakit');

    function setupFileUpload(uploadAreaId, fileInputId, previewContainerId, previewImageId, removeButtonId) {
        const uploadArea = document.getElementById(uploadAreaId);
        const fileInput = document.getElementById(fileInputId);
        const previewContainer = document.getElementById(previewContainerId);
        const previewImage = document.getElementById(previewImageId);
        const removeButton = document.getElementById(removeButtonId);

        uploadArea.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) handleFile(file, uploadArea, previewContainer, previewImage);
        });

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) handleFile(files[0], uploadArea, previewContainer, previewImage);
        });

        removeButton.addEventListener('click', () => {
            fileInput.value = '';
            previewContainer.style.display = 'none';
            uploadArea.style.display = 'block';
        });
    }

    function handleFile(file, uploadArea, previewContainer, previewImage) {
        if (!file.type.match('image.*')) {
            alert('Hanya file gambar yang diperbolehkan!');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file maksimal 5MB!');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            uploadArea.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // Form Submit
    document.getElementById('absensiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const status = document.querySelector('input[name="status"]:checked');
        
        if (!status) {
            alert('Mohon pilih status kehadiran!');
            return;
        }

        if (status.value === 'izin') {
            const alasanIzin = document.getElementById('alasanIzin').value;
            if (!alasanIzin) {
                alert('Mohon isi alasan izin!');
                return;
            }
        }

        if (status.value === 'sakit') {
            const jenisSakit = document.getElementById('jenisSakit').value;
            if (!jenisSakit) {
                alert('Mohon isi jenis sakit!');
                return;
            }
        }

        alert('Absensi berhasil dikirim!');
    });

    // Update date
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date();
    document.getElementById('currentDate').textContent = today.toLocaleDateString('id-ID', options);
</script>
@endsection
