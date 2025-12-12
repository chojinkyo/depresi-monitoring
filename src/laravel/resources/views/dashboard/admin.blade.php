@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@php
    $pageTitle = 'Dashboard';
    $pageSubtitle = 'Panel Administrasi';
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-4">
                <h4 class="fw-bold text-dark mb-3">Selamat Datang, Admin!</h4>
                <p class="text-muted">Kelola data siswa, kelas, dan tahun akademik melalui menu di sidebar.</p>
            </div>
        </div>
    </div>
</div>
@endsection
