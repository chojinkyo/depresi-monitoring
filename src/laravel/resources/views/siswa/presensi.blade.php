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

        <form id="absensiForm" enctype="multipart/form-data">
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
                    <div class="camera-view-container w-100 h-100 position-relative">
                        <video id="camera-view" autoplay playsinline class="w-100 h-100">
                        </video>

                        <button 
                        type="button"
                        id="open-cam-btn" 
                        class="position-absolute btn btn-primary" 
                        style="left: 50%;top: 50%;transform: translate(-50%, -50%)">Open Camera</button>
                    </div>
                    <i class="bi bi-camera-fill upload-icon"></i>
                    {{-- <p class="upload-text">Klik untuk Upload Foto</p> --}}
                    <br>
                    {{-- <p class="upload-subtext">JPG, PNG maksimal 5MB</p>
                    <button type="button" class="file-input"></button> --}}
                    <button type="button" id="snap-btn" class="btn btn-success">Take Photo</button>
                    <canvas id="cam-capturer" class="d-none"></canvas>
                    {{-- <input type="file" id="fileInput" class="file-input" accept="image/jpeg,image/png,image/jpg"> --}}
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
    const snapBtn=document.getElementById('snap-btn');
    const cameraView=document.getElementById('camera-view');
    const openCamBtn=document.getElementById('open-cam-btn');
    const formPresensi=document.getElementById('absensiForm');
    let swafoto=null;

    snapBtn.addEventListener('click', TakePhoto);
    openCamBtn.addEventListener('click', ToggleCamera);
    formPresensi.addEventListener('submit', HandleSubmit);

    async function HandleSubmit(event)
    {
        try {
            event.preventDefault();
            const formData=new FormData(formPresensi);
            formData.append('swafoto', swafoto, 'selfie.jpg');

            console.log(formData);
            const response=await fetch('/api/siswa/presensi', {
                method : "POST",
                body : formData,
                headers : {
                    Accept : 'application/json',
                    Authorization : `Bearer 1|CEK90kRHkXH3T90kYmtGUKFavr8CoEmrzBKfYnIh85afff2a`
                }
            })
            const json=await response.json()
            console.log(json)
        } catch (error) {
            console.log(error)
        }
    }
    function base64ToBlob(base64, mime = "image/jpeg") {
        const byteString = atob(base64.split(',')[1]); // decode base64
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);

        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ab], { type: mime });
    }

    function TakePhoto()
    {
        if(!cameraView.srcObject) return;
        const canvas=document.getElementById('cam-capturer');
        canvas.width=cameraView.videoWidth;
        canvas.height=cameraView.videoHeight;

        const ctx=canvas.getContext('2d');
        ctx.drawImage(cameraView, 0, 0, canvas.width, canvas.height);
        
        const dataUrl=canvas.toDataURL('image/jpeg');
        console.log(dataUrl);

        swafoto=base64ToBlob(dataUrl)
    }
    function ToggleCamera()
    {
        if(cameraView.srcObject) CloseCamera();
        else OpenCamera();
    }
    function OpenCamera() {
        navigator.mediaDevices.getUserMedia({video : true, audio : false})
        .then(stream=>cameraView.srcObject=stream)
        .catch(e=>console.log(e));
    }
    function CloseCamera()
    {
        const stream=cameraView.srcObject;
        const tracks=stream.getTracks();
        tracks.forEach(tr=>tr.stop());
        cameraView.srcObject=null;
    }
</script>
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

        uploadArea.addEventListener('click', () => fileInput?.click());

        fileInput?.addEventListener('change', function(e) {
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
