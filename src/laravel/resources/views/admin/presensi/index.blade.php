

@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Riwayat Kehadiran</h1>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    const labels = [];
    const moodData = []; // tanggal berurutan
    const emotionLabels = {
        1: "H",
        2: "I",
        3: "S",
        4: "A"
       
        
    };
    const emotionLabels2 = Object.fromEntries(Object.entries(emotionLabels).map(([key, value]) => [value, key]))
    function loadPaginatedData(history, page=1)
    {
        // const end=10*page;
        // const start=10*(page-1);
        // const slicedHistory=history.slice(start, end)
        const data=[];
        const xLabels=[];

        history.forEach(e=>{
            data.push(emotionLabels2[e.status])
            xLabels.push(" ");
        })

        loadDiagram(data, xLabels)
    }
    function loadDiagram(data, xLabels)
    {
        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: xLabels,
                datasets: [{
                    label: 'Mood',
                    data: data,
                    borderWidth: 2,
                    borderColor: "rgba(60,141,188,0.8)",
                    backgroundColor: "rgba(60,141,188,0.2)",
                    fill: false,
                    // garis patah-patah
                    tension: 0 // biar benar-benar patah
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes : [{
                        display:true,
                        
                        position: 'bottom',
                        ticks: {
                            min: 0,
                            max: 4,
                            step: 1,
                            callback: function (value) {
                                return emotionLabels[value];
                            }
                        },
                        
                    }]
                }
            }
        });
    }

    loadDiagram(moodData, labels);
</script>


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
                                <th class="col-4 py-3 fw-medium text-dark">Siswa</th>
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
                                <td class="text-center py-3">{{ $key+1 }}</td>
                                <td class="py-3">
                                    <small>
                                        <div class="fw-medium">{{$studentAttendance->nama_lengkap }}</div>
                                        <div class="text-secondary">{{$studentAttendance->nisn }}</div>
                                    </small>
                                </td>
                                <td class="py-3">{{ $studentAttendance->classes->first()?->nama ?? "-" }}</td>
                                <td class="alignment-end py-3">
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
                                        @if($persenHadir > 0)
                                            <div class="fw-medium" style="width: {{ $persenHadir }}%;min-width: fit-content">
                                                <small>{{ $persenHadir }}%</small>
                                            </div>
                                        @endif
                                        @if($persenAlpha > 0)
                                            <div class="fw-medium" style="width: {{ $persenAlpha }}%;min-width: fit-content">
                                                <small>{{ $persenAlpha }}%</small>
                                            </div>
                                        @endif
                                        @if($persenIjinSakit > 0)
                                            <div class="fw-medium" style="width: {{ $persenIjinSakit }}%;min-width: fit-content">
                                                <small>{{ $persenIjinSakit }}%</small>
                                            </div>
                                        @endif

                                        
                                    </div>
                                </td>
                                <td class="py-3">
                                    <button 
                                    type="button"
                                    class="btn btn-sm btn-primary"
                                    onclick="loadAttendancesHistory(`{{ route('admin.siswa.kehadiran.show', ['student'=>$studentAttendance->id]) }}`, {{ $studentAttendance }})"
                                    >
                                        <i class="fas fa-eye mr-2"></i>
                                    </button>

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
                        <h3 class="h6 font-weight-bold">Keterangan :</h3>
                        <ul class="list-unstyled font-italic fw-normal d-flex gap-3">
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
                        {{-- <h3 class="h6 font-weight-bold">Total Pertemuan : <span class="font-italic">14</span></h3> --}}
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
            <div class="card-body d-flex align-items-center gap-2" x-data="{student : {}}" x-ref="student_container">
                <div class="img-container col-2">
                    <div class="w-100 ratio ratio-1x1">
                        <img 
                        :src="student.user?.avatar_url ?
                        `http://localhost:8000/files/images/users/id/${student.id_user}/${student.user.avatar_url}` :
                        'http://localhost:8000/files/images/users/default'" 
                        alt="" 
                        class="w-100 d-block border border-2 border-black-50 rounded-circle"
                        style="object-fit: cover;">
                    </div>
                </div>
                <div class="text-container col-8">
                    <h3 class="font-weight-bold h5 text-white">
                        <div x-text="student.nama_lengkap || 'Profile Name'">Profile Name</div>
                    </h3>
                    <h4 class="h6 text-secondary text-light">
                        <div x-text="student.nisn || 'NISN'">NISN</div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-2">
            <div class="card-body d-flex align-items-center p-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title h5">Diagram Kehadiran</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart d-flex justify-content-center">
                            <canvas id="myChart" width="450" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm ">
            <div class="card-header no-after py-4 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                <h2 class="h5 font-weight-bold m-0 text-black-50">Riwayat Kehadiran</h2>


                
            </div>
            <div class="card-body">
                <div class="table-responsive" >
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col-2 text-dark fw-medium bg-light-subtle py-3 text-center">
                                    No
                                </th>
                                <th class="col-5 text-dark fw-medium bg-light-subtle py-3">
                                    Date
                                </th>
                                <th class="col-2 text-dark fw-medium bg-light-subtle py-3 text-center">
                                    Status
                                </th>
                                <th class="col-2 text-dark fw-medium bg-light-subtle py-3">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody x-data="{
                            attendances_hist : [], 
                            index: 0, 
                            startIndex : 0,
                            student : {},
                            
                            loadAttendanceDetails(attendance) 
                            {
                                const attendanceDetailContainer = Alpine.$data(document.querySelector(`[x-ref='attendance_details_container']`));
                                attendanceDetailContainer.details=attendance;
                                attendanceDetailContainer.student=this.student;
                            }
                        }" 
                        x-ref="attendances_container">
                            <template x-for="(item, index) in attendances_hist" :key="index">
                                <tr>
                                    <td class="text-center">
                                        <div x-text="index+startIndex+1"></div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold" x-text="item.date_string"></div>
                                        <div class="font-weight-bold" x-text="item.time_string"></div>
                                    </td>
                                    <td class="text-center">
                                        <div x-text="item.status"></div>
                                    </td>
                                    <td>
                                        <a 
                                        href="#" 
                                        role="button" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"
                                        x-on:click="loadAttendanceDetails(item)"
                                        >
                                            Detail
                                        </a>
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

                    
                    <div 
                        class="d-flex w-auto"
                        x-data="{
                            route : ``, 
                            pages : [],
                            start : [],
                            year : {{ request()->input('year')=='' ? '0' : request()->input('year') }},
                            total : 0,
                            limit : 10,
                            current : 0, 
                            lastIndex : 0,
                            visibleLastIndex : 0,
                            isLast : false,
                            isFirst : true,
                            batchCount : 5,
                            setPages()
                            {
                                const totalPages=Math.ceil(this.total / this.limit)
                                this.pages=Array.from({length : totalPages}, (_, index)=>index)
                            },
                            setIndexes()
                            {
                                const current=this.current;
                                
                                const batchCount=this.batchCount;

                                const mod=Math.floor(current/batchCount);
                                const min=current < 2 ? 0 : current > this.visibleLastIndex ? this.visibleLastIndex : current - 1;
                                const max=min + batchCount;
                                
                                this.start=this.pages.slice(min, max)
                                this.lastIndex=this.pages.length - 1
                                this.visibleLastIndex=this.lastIndex - batchCount + 1;
                                this.isFirst=current===0;
                                this.isLast=current===this.lastIndex;
                            },
                            async loadPage(index, increment=0)
                            {
                                const nextIndex=index+increment;
                                index=nextIndex < 0 || nextIndex > this.pages.length - 1 ? index : nextIndex;
                                const attendanceContainer = Alpine.$data(document.querySelector(`[x-ref='attendances_container']`));
                                let url=`${this.route}?page=${index}&limit=${this.limit}`;
                                if(this.year!==0) url+=`&year=${this.year}`
                                
                                

                                const resp=await fetch(url);
                                const json=await resp.json();
                                const attendances=json?.response?.data

                                attendanceContainer.attendances_hist=attendances
                                attendanceContainer.startIndex=index*this.limit
                                loadPaginatedData(attendances);

                                this.current=index
                            }
                        }" 
                        x-init="$watch('route, limit', _=>{setPages();setIndexes();loadPage(0,0)});$watch('current', _=>setIndexes())"
                        x-ref="nav_container"
                    >
                        <nav>
                            <ul class="pagination" >
                                <li class="page-item">
                                    <button x-on:click="loadPage(0)" class="page-link" :class="{'disabled':isFirst}">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </button>
                                </li>
                                <li class="page-item">
                                    <button x-on:click="loadPage(current, -1)" class="page-link" :class="{'disabled':isFirst}">
                                        <span aria-hidden="true">&lsaquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </button>
                                </li>
                                <template x-for="index in start">
                                    <li class="page-item" :class="{'active':current===index}">
                                        <button x-on:click="loadPage(index)" class="page-link" x-text="index+1"></button>
                                    </li>
                                </template>
                                
                                <li class="page-item">
                                    <button x-on:click="loadPage(current, 1)" class="page-link" :class="{'disabled':isLast}">
                                        <span aria-hidden="true">&rsaquo;</span>
                                        <span class="sr-only">Next</span>
                                    </button>
                                </li>
                                <li class="page-item">
                                    <button x-on:click="loadPage(lastIndex)" class="page-link rounded-0" :class="{'disabled':isLast}">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </button>
                                </li>
                            </ul>
                        </nav>

                        <div>
                            <select class="form-select rounded-start-0 border-start-0" x-model="limit">
                                <option value="3">3</option>
                                <option value="5">5</option>
                                <option value="8">8</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
   

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" 
    x-data="{
        details : {},
        student : {},
        switchStatus(status)
        {
            switch(status) 
            {
                case 'I' :
                    return 'Izin';
                case 'H' :
                    return 'Hadir';
                case 'S' :
                    return 'Sakit';
                case 'A' : 
                    return 'Alpha';
                default :
                    return '-';
            }
        }
    }" 
    x-ref="attendance_details_container">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header bg-gradient bg-primary bg-opacity-75 py-4">
            <h1 class="modal-title fs-5 text-black-50" id="exampleModalLabel">Detail Presensi</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-4">
                        <div class="w-75 ratio ratio-1x1 py-2">
                            <img 
                            :src="student.user?.avatar_url ?
                            `http://localhost:8000/files/images/users/id/${student.id_user}/${student.user.avatar_url}` :
                            'http://localhost:8000/files/images/users/default'" 
                            alt="" 
                            class="w-100 img-fluid"
                            style="object-fit: contain;">
                        </div>
                    </div>

                    <div class="col-8">
                        <table class="table table-borderless" style="font-size: 14px;">
                            <tbody>
                                <tr>
                                    <th>Nama</th>
                                    <td x-text="student.nama_lengkap ?? ' '"></td>
                                </tr>
                                <tr>
                                    <th>NISN</th>
                                    <td x-text="student.nisn ?? ' '"></td>
                                </tr>
                                <tr>
                                    <th>Waktu Presensi</th>
                                    <td x-text="details.waktu_string ?? '-'"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td x-text="switchStatus(details.status)"></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td x-text="details.ket ?? '-'"></td>
                                </tr>
                                <tr>
                                    <th>Dokumen Pendukung</th>
                                    <td x-text="details.doc_path ?? '-'"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer py-4 border-0">
            
        </div>
        </div>
    </div>
    </div>

</div>
<script>


    async function loadAttendancesHistory(route, student, year=null)
    {
        const attendanceContainer = Alpine.$data(document.querySelector('[x-ref="attendances_container"]'));
        const studentContainer = Alpine.$data(document.querySelector('[x-ref="student_container"]'));
        const navContainer = Alpine.$data(document.querySelector('[x-ref="nav_container"]'));
        
        navContainer.total=student.presensi?.result?.total_presensi || 0;
        navContainer.route=route;
        studentContainer.student=student;
        attendanceContainer.student=student;
    }
    function loadAttendanceDetails(attendance)
    {
        
    }
</script>
@endsection



