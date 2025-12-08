

@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Riwayat Kehadiran</h1>
        </div>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-7">
        <div class="card shadow-sm border-4 border-top border-primary mb-4">
            <div class="card-header no-after py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <h2 class="h6 font-weight-bold text-primary m-0">Riwayat Kehadiran Siswa</h2>
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input type="text" name="" id="" class="form-control" placeholder="search items">
                            <div class="input-group-append">
                                <select name="" id="" class="form-control bg-light rounded-0">
                                    <option value="">All Kelas</option>
                                </select>
                            </div>
                            <div class="input-group-append">
                                <select name="" id="" class="form-control bg-light rounded-0">
                                    <option value="">Tahun Ini</option>
                                </select>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border">
                        <thead class="bg-light">
                            <tr>
                                <th class="col-1">No</th>
                                <th class="col-3">Siswa</th>
                                <th class="col-2">Kelas</th>
                                <th class="col-4">Kehadiran</th>
                                <th scope="col" class="col-2">Aksi</th>
                            </tr>
                        </thead>
                        
                        @forelse ($studentAttendances as $key=>$studentAttendance)
                            @php
                                $persenHadir=0;
                                $persenAlpha=0;
                                $persenIjinSakit=0;
                                if($studentAttendance->presensi)
                                {
                                    $persenHadir=$studentAttendance->presensi->persen_hadir;
                                    $persenAlpha=$studentAttendance->presensi->persen_alpha;
                                    $persenIjinSakit=$studentAttendance->presensi->persen_ijin_sakit;
                                }
                            @endphp
                        
                            <tr>
                                <td class="">{{ $key+1 }}</td>
                                <td>
                                    <div class="font-weight-bold">{{$studentAttendance->nama_lengkap }}</div>
                                    <div class="text-secondary">{{$studentAttendance->nisn }}</div>
                                </td>
                                <td>
                                    {{ $studentAttendance->getClassByAcademicYear($currentAcademicYear->id)?->nama ?? "-" }}
                                </td>
                                <td class="alignment-end">
                                    <div class="progress rounded-pill w-75" style="height: 10px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persenHadir }}%;" aria-valuenow="{{ $persenHadir }}" aria-valuemin="0" aria-valuemax="100">
                                        
                                        </div>
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persenAlpha }}%;" aria-valuenow="{{ $persenAlpha }}" aria-valuemin="0" aria-valuemax="100">

                                        </div>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $persenIjinSakit }}%;" aria-valuenow="{{ $persenIjinSakit }}" aria-valuemin="0" aria-valuemax="100">

                                        </div>
                                    </div>
                                    <div class="w-75 d-flex flex-wrap text-xs">
                                        <div style="width: {{ $persenHadir }}%;">
                                            {{ $persenHadir }}%
                                        </div>
                                        <div style="width: {{ $persenAlpha }}%;">
                                            {{ $persenAlpha }}%
                                        </div>
                                        <div style="width: {{ $persenIjinSakit }}%;">
                                            {{ $persenIjinSakit }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a 
                                    href="#" 
                                    class="btn btn-sm btn-primary"
                                    x-on:click="event.preventDefault();"
                                    onclick="Livewire.dispatch('siswa_kehadiran:view', {id:{{ $studentAttendance->id }}})"
                                    data-toggle="modal"
                                    data-target="#view-modal">
                                        <i class="fas fa-eye mr-2"></i> Details
                                    </a>

                                </td>

                            </tr>
                        @empty
                            
                        @endforelse
                    </table>

                    
                    
                </div>
            </div>
            <div class="card-footer bg-white border-top d-flex justify-content-between no-after">
                <section class="d-flex">
                    <div>
                        <h3 class="h6 font-weight-bold">Ket</h3>
                        <ul class="list-unstyled font-italic">
                            <li>
                                <span class="text-success mr-1"><i class="fas fa-circle"></i></span>
                                Hadir
                            </li>
                            <li>
                                <span class="text-danger mr-1"><i class="fas fa-circle"></i></span>
                                Alpha
                            </li>
                            <li>
                                <span class="text-info mr-1"><i class="fas fa-circle"></i></span>
                                Ijin/Sakit
                            </li>
                            
                        </ul>
                    </div>
                    <div>
                        <h3 class="h6 font-weight-bold">Total Pertemuan : <span class="font-italic">14</span></h3>
                    </div>
                </section>
                <div class="d-flex justify-content-left">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item">
                                <a href="#" class="page-link">
                                    <span aria-hidden="true">&laquo;</span>
                                    <span class="sr-only">Previous</span>
                                </a>

                                
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">
                                    1
                                </a>
                            </li>
                            <li class="page-item">
                                <a href="#" class="page-link">
                                    <span aria-hidden="true">&raquo;</span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card shadow-sm border-4 border-top border-info">
            <div class="card-body">
                <h3 class="font-weight-sbold">Harun Manunggal</h3>
                <h4 class="h5 text-secondary">1234567890</h4>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @if ($presensi && $presensi->count())
                                @foreach ($presensi as $key=>$pr)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $pr->waktu }}</td>
                                        <td>{{ $pr->status }}</td>
                                        <td>
                                            <a 
                                            href="#" 
                                            class="btn btn-primary d-block d-flex align-items-center"
                                            x-on:click="event.preventDefault();"
                                            data-toggle="modal"
                                            data-target="#create-modal">
                                                <i class="fas fa-plus mr-2"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                    
                                @endforeach
                            @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    
</div>
@endsection
