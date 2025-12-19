@extends('layouts.guru')

@section('title', 'Laporan Mood Siswa')

@php
    $pageTitle = 'Laporan Mood';
    $pageSubtitle = 'Monitor kondisi emosional siswa';
@endphp

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-4" style="border-top-left-radius: 10px;">Nama Siswa</th>
                        <th class="py-3">Update Terakhir</th>
                        <th class="py-3 text-center">Mood Terakhir</th>
                        <th class="py-3 pe-4 text-center" style="border-top-right-radius: 10px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaData as $siswa)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $siswa['nama'] }}</td>
                            <td class="text-muted">{{ $siswa['last_update'] }}</td>
                            <td class="text-center h4 mb-0" title="{{ $siswa['mood_label'] }}">
                                {{ $siswa['mood_emoji'] }}
                            </td>
                            <td class="text-center">
                                @if($siswa['mood_label'] != '-')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success">Active</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary">No Data</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-4 d-block mb-3"></i>
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
