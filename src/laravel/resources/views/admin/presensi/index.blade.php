

@extends('layouts.admin')

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
        <div class="card shadow-sm mb-4">
            <div class="card-header no-after p-4 d-flex align-items-center justify-content-between bg-success bg-gradient bg-opacity-50">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <h2 class="fs-5 fw-medium text-black-50 m-0 col-4">Riwayat Kehadiran Siswa</h2>
                    <div class="d-flex col-8 justify-content-end">
                        <div class="col-5">
                            <input type="text" class="form-control rounded-end-0 border-end-0 opacity-75" placeholder="search items">
                        </div>
                        <div class="col-5 d-flex">
                            <select class="form-select bg-light rounded-0 border-end-0 opacity-75">
                                <option value="">All Kelas</option>
                            </select>
                            <select class="form-select bg-light rounded-0 border-end-0 opacity-75">
                                <option value="">Tahun Ini</option>
                            </select>
                        </div>
                        <button class="btn btn-warning rounded-start-0">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table border">
                        <thead class="table-light">
                            <tr>
                                <th class="col-1 py-3 fw-medium text-dark text-center">No</th>
                                <th class="col-3 py-3 fw-medium text-dark">Siswa</th>
                                <th class="col-1 py-3 fw-medium text-dark">Kelas</th>
                                <th class="col-5 py-3 fw-medium text-dark">Kehadiran</th>
                                <th scope="col" class="col-2 py-3 fw-medium text-dark">Aksi</th>
                            </tr>
                        </thead>
                        
                        @forelse ($studentAttendances as $key=>$studentAttendance)
                            @php
                                $persenHadir=0;
                                $persenAlpha=0;
                                $persenIjinSakit=0;
                                $details=$studentAttendance->presensi->get('details');
                                if($studentAttendance->presensi)
                                {
                                    $result=$studentAttendance->presensi->get('result');
                                    $persenHadir=$result?->persen_hadir ?? 0;
                                    $persenAlpha=$result?->persen_alpha ?? 0;
                                    $persenIjinSakit=$result?->persen_ijin_sakit ?? 0;
                                }
                            @endphp
                        
                            <tr>
                                <td class="text-center">{{ $key+1 }}</td>
                                <td>
                                    <small>
                                        <div class="fw-medium">{{$studentAttendance->nama_lengkap }}</div>
                                        <div class="text-secondary">{{$studentAttendance->nisn }}</div>
                                    </small>
                                </td>
                                <td>{{ $studentAttendance->classes->first()?->nama ?? "-" }}</td>
                                <td class="alignment-end">
                                    <div class="progress rounded-pill w-75" style="height: 10px;">
                                        <div 
                                        role="progressbar" 
                                        class="progress-bar bg-success bg-opacity-75 bg-gradient" 
                                        style="width: {{ $persenHadir }}%;" 
                                        aria-valuenow="{{ $persenHadir }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                        >
                                        </div>

                                        <div 
                                        role="progressbar" 
                                        class="progress-bar bg-danger bg-opacity-75 bg-gradient" 
                                        style="width: {{ $persenAlpha }}%;" 
                                        aria-valuenow="{{ $persenAlpha }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                        >
                                        </div>

                                        <div 
                                        role="progressbar" 
                                        class="progress-bar bg-warning bg-opacity-75 bg-gradient" 
                                        style="width: {{ $persenIjinSakit }}%;" 
                                        aria-valuenow="{{ $persenIjinSakit }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100"
                                        >
                                        </div>
                                    </div>
                                    
                                    <div class="w-75 d-flex flex-wrap text-xs mt-1" style="font-size: 12px;">
                                        @if(($persenHadir+$persenAlpha+$persenIjinSakit))
                                            <div class="fw-medium" style="width: {{ $persenHadir }}%;min-width: fit-content">
                                                <small>{{ $persenHadir }}%</small>
                                            </div>
                                            <div class="fw-medium" style="width: {{ $persenAlpha }}%;min-width: fit-content">
                                                <small>{{ $persenAlpha }}%</small>
                                            </div>
                                            <div class="fw-medium" style="width: {{ $persenIjinSakit }}%;min-width: fit-content">
                                                <small>{{ $persenIjinSakit }}%</small>
                                            </div>
                                        @else
                                            <div class="w-100">
                                                <small>0%</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a 
                                    href="#" 
                                    class="btn btn-sm btn-primary"
                                    x-on:click="event.preventDefault();"
                                    onclick="loadAttendancesHistory(`{{ route('admin.siswa.kehadiran.show', ['student'=>$studentAttendance->id, 'year'=>(request()->query('year') ?? 1) ]) }}`)"
                                    >
                                        <i class="fas fa-eye mr-2"></i> Details
                                    </a>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="bg-light-subtle text-center text-black-50">
                                    Belum Ada Siswa
                                </td>
                            </tr>
                        @endforelse
                    </table>

                    
                    
                </div>
            </div>
            <div class="card-footer bg-light-subtle border-top d-flex justify-content-between no-after p-4">
                <section class="d-flex">
                    <div>
                        <h3 class="h6 font-weight-bold">Ket</h3>
                        <ul class="list-unstyled font-italic fw-medium">
                            <li>
                                <span class="text-success mr-1"><i class="fas fa-circle"></i></span>
                                <small>Hadir</small>
                            </li>
                            <li>
                                <span class="text-danger mr-1"><i class="fas fa-circle"></i></span>
                                <small>Alpha</small>
                            </li>
                            <li>
                                <span class="text-warning mr-1"><i class="fas fa-circle"></i></span>
                                <small>Ijin/Sakit</small>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="h6 font-weight-bold">Total Pertemuan : <span class="font-italic">14</span></h3>
                    </div>
                </section>
                <div class="d-flex justify-content-left">
                    {{ $studentAttendances->links() }}
                    <div class="form-group m-0">
                        <select class="form-select bg-light rounded-start-0 border-start-0">
                            <option value="10">10</option>
                            <option value="10">25</option>
                            <option value="10">50</option>
                            <option value="10">100</option>
                            <option value="10">250</option>
                        </select>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card shadow-sm bg-primary bg-gradient bg-opacity-50 mb-2">
            <div class="card-body d-flex align-items-center gap-2">
                <div class="img-container col-2">
                    <img src="http://localhost:8000/files/images/users/default" alt="" class="img-fluid rounded-circle border border-2 border-white">
                </div>
                <div class="text-container col-8">
                    <h3 class="font-weight-bold h4 text-white">
                        Profile Name
                    </h3>
                    <h4 class="h6 text-secondary text-light">
                        NISN
                    </h4>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-2">
            <div class="card-body d-flex align-items-center">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title h5">Diagram Mental</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="myChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm ">
            <div class="card-header no-after p-4 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                <h2 class="h5 font-weight-bold m-0 text-black-50">Riwayat Mental</h2>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="col-2 text-dark">
                                    No
                                </th>
                                <th class="col-4 text-dark">
                                    Date
                                </th>
                                <th class="col-3 text-dark">
                                    Result
                                </th>
                                <th class="col-2 text-dark">

                                </th>
                            </tr>
                        </thead>
                        <tbody x-data="{attendances_hist : [], index: 0}" x-ref="attendances_container">
                            <template x-if="attendances_hist.length > 0">
                                <template x-for="item in attendances_hist" :key="index++">
                                    <tr>
                                        <td class="">1</td>
                                        <td>
                                            <div class="font-weight-bold" x-text="item.waktu"></div>
                                        </td>
                                        <td>
                                            <div x-text="item.ket"></div>
                                        </td>
                                    </tr>
                                </template>
                                <tr class="table-active">
                                    <td col="3">
                                        <i>Choose siswa first</i>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="attendances_hist.length === 0">
                                <tr class="table-active">
                                    <td colspan="4" class="text-center">
                                        <i>Choose siswa first</i>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
<script>


    async function loadAttendancesHistory(route)
    {
        const data=Alpine.$data(document.querySelector('[x-ref="attendances_container"]'))
        


        const response=await fetch(route+'?page=1');
        const json=await response.json();

        console.log(json);
        const attendances=json['response']['data']
        data.attendances_hist=attendances;
    }
</script>
@endsection



