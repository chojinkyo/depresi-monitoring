

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
                                $details=$studentAttendance->presensi->get('details');
                                if($studentAttendance->presensi)
                                {
                                    $result=$studentAttendance->presensi->get('result');
                                    $persenHadir=$result->persen_hadir;
                                    $persenAlpha=$result->persen_alpha;
                                    $persenIjinSakit=$result->persen_ijin_sakit;
                                }
                            @endphp
                        
                            <tr>
                                <td class="">{{ $key+1 }}</td>
                                <td>
                                    <div class="font-weight-bold">{{$studentAttendance->nama_lengkap }}</div>
                                    <div class="text-secondary">{{$studentAttendance->nisn }}</div>
                                </td>
                                <td>
                                    {{ $studentAttendance->classes->first()?->nama ?? "-" }}
                                </td>
                                <td class="alignment-end">
                                    <div class="progress rounded-pill w-75" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persenHadir }}%;" aria-valuenow="{{ $persenHadir }}" aria-valuemin="0" aria-valuemax="100">
                                        
                                        </div>
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $persenAlpha }}%;" aria-valuenow="{{ $persenAlpha }}" aria-valuemin="0" aria-valuemax="100">

                                        </div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $persenIjinSakit }}%;" aria-valuenow="{{ $persenIjinSakit }}" aria-valuemin="0" aria-valuemax="100">

                                        </div>
                                    </div>
                                    <div class="w-75 d-flex flex-wrap text-xs">
                                        @if(($persenHadir+$persenAlpha+$persenIjinSakit))
                                            <div style="width: {{ $persenHadir }}%;min-width: fit-content">
                                                {{ $persenHadir }}%
                                            </div>
                                            <div style="width: {{ $persenAlpha }}%;min-width: fit-content">
                                                {{ $persenAlpha }}%
                                            </div>
                                            <div style="width: {{ $persenIjinSakit }}%;min-width: fit-content">
                                                {{ $persenIjinSakit }}%
                                            </div>
                                        @else
                                            <div class="w-100">
                                                0%
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
            <div class="card-body d-flex align-items-center">
                <div class="img-container col-2">
                    <img src="http://localhost:8000/files/images/users/default" alt="" class="img-fluid">
                </div>
                <div class="text-container col-8">
                    <h3 class="font-weight-bold h4 text-info">
                        <i>Profile Name</i>
                    </h3>
                    <h4 class="h6 text-secondary">
                        <i>NISN</i>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-4 border-top border-info">
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
        <div class="card shadow-sm border-top border-top border-4 border-info">
            <div class="card-header no-after py-3 d-flex justify-content-between align-items-center">
                <h2 class="h5 font-weight-bold text-info">Riwayat Mental</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col-2">
                                    No
                                </th>
                                <th class="col-4">
                                    Date
                                </th>
                                <th class="col-3">
                                    Result
                                </th>
                                <th class="col-2">

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



