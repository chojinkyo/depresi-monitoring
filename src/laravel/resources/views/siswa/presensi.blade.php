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

            {{-- Webcam Section --}}
            <div class="upload-section">
                <label class="form-label">Ambil Foto Selfie</label>
                <div class="webcam-container text-center">
                    <div id="cameraArea" class="mb-3">
                        <video id="webcam" autoplay playsinline style="width: 100%; max-width: 400px; border-radius: 10px; display: none;"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div id="placeholder" class="p-5 bg-light rounded border" style="cursor: pointer;">
                            <i class="bi bi-camera-fill fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">Klik untuk aktifkan kamera</p>
                        </div>
                    </div>
                    
                    <div id="previewArea" style="display: none;" class="mb-3 position-relative">
                        <img id="photoPreview" src="" class="img-fluid rounded" style="max-width: 400px;">
                        <button type="button" id="retakeBtn" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Foto Ulang
                        </button>
                    </div>

                    <button type="button" id="captureBtn" class="btn btn-primary w-100" style="display: none;">
                        <i class="bi bi-camera"></i> Ambil Foto
                    </button>
                </div>
                <input type="file" id="swafotoInput" name="swafoto" class="d-none" accept="image/*">
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

            if (this.value === 'I') {
                formIzin.classList.add('show');
            } else if (this.value === 'S') {
                formSakit.classList.add('show');
            }
        });
    });

    // Webcam Elements
    const video = document.getElementById('webcam');
    const canvas = document.getElementById('canvas');
    const placeholder = document.getElementById('placeholder');
    const captureBtn = document.getElementById('captureBtn');
    const previewArea = document.getElementById('previewArea');
    const photoPreview = document.getElementById('photoPreview');
    const retakeBtn = document.getElementById('retakeBtn');
    const swafotoInput = document.getElementById('swafotoInput');
    const cameraArea = document.getElementById('cameraArea');

    let stream = null;

    // Initialize Camera
    placeholder.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            captureBtn.style.display = 'block';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    });

    // Capture Photo
    captureBtn.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        
        // Convert to file
        canvas.toBlob(blob => {
            const file = new File([blob], "swafoto.jpg", { type: "image/jpeg" });
            
            // Create FileList hack for input file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            swafotoInput.files = dataTransfer.files;

            // Show preview
            photoPreview.src = URL.createObjectURL(blob);
            previewArea.style.display = 'block';
            video.style.display = 'none';
            captureBtn.style.display = 'none';
            
            // Stop stream
            stream.getTracks().forEach(track => track.stop());
        }, 'image/jpeg');
    });

    // Retake Photo
    retakeBtn.addEventListener('click', async () => {
        previewArea.style.display = 'none';
        swafotoInput.value = ''; // Clear input
        
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            video.style.display = 'block';
            captureBtn.style.display = 'block';
        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
            placeholder.style.display = 'block';
        }
    });

    // Form Submit
    document.getElementById('absensiForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const status = document.querySelector('input[name="status"]:checked');
        
        if (!status) {
            alert('Mohon pilih status kehadiran!');
            return;
        }

        if (status.value === 'I') {
            const alasanIzin = document.getElementById('alasanIzin').value;
            if (!alasanIzin) {
                alert('Mohon isi alasan izin!');
                return;
            }
        }

        if (status.value === 'S') {
            const jenisSakit = document.getElementById('jenisSakit').value;
            if (!jenisSakit) {
                alert('Mohon isi jenis sakit!');
                return;
            }
        }

        // Create FormData
        const formData = new FormData(this);
        
        // Add token if not present (usually handled by @csrf directive in form)
        // formData.append('_token', '{{ csrf_token() }}');

        // Show loading state
        const submitBtn = document.querySelector('.btn-submit');
        const originalBtnText = submitBtn.innerText;
        submitBtn.innerText = 'Mengirim...';
        submitBtn.disabled = true;

        fetch('/siswa/presensi', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert('Berhasil! ' + data.message);
                window.location.href = '/siswa/dashboard';
            } else {
                throw new Error(data.message || 'Terjadi kesalahan saat mengirim absensi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal: ' + error.message);
        })
        .finally(() => {
            submitBtn.innerText = originalBtnText;
            submitBtn.disabled = false;
        });
    });

    // Update date
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date();
    document.getElementById('currentDate').textContent = today.toLocaleDateString('id-ID', options);
</script>
@endsection
