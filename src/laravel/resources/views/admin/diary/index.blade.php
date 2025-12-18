

@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Dashboard Mental Siswa</h1>
        </div>
    </div>
@endsection


@section('scripts')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    const labels = [["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"], ["1 Jan", "2025"]];
    const moodData = [1, 1, 1, 1, 1, 1]; // tanggal berurutan
    const emotionLabels = {
        1: "anger",
        2: "anxiety",
        3: "sadness",
        4: "happy",
        5: "surprise"
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
            data.push(emotionLabels2[e.swafoto_pred])
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
                            max: 5,
                            step: 1,
                            callback: function (value) {
                                return emotionLabels[value];
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Moods'
                        }
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
        <div class="card shadow">
            <div class="card-header no-after py-4 d-flex align-items-center justify-content-between bg-primary bg-gradient bg-opacity-50">
                <div class="d-flex align-items-center">
                    <div class="input-group">
                        <input type="text" name="" id="" class="form-control" placeholder="search items">
                        <div class="input-group-append">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center" style="gap: .5rem;">
                    <div class="form-group m-0">
                        <select name="" id="" class="form-select bg-light">
                            <option value="">All Kelas</option>
                        </select>
                    </div>
                    <div class="form-group m-0">
                        <select name="" id="" class="form-select bg-light">
                            <option value="">Tahun Ini</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table border">
                        <thead class="table-light">
                            <tr>
                                <th class="col-1 py-3 fw-medium text-center">No</th>
                                <th class="col-3 py-3 fw-medium">Siswa</th>
                                <th class="col-2 py-3 fw-medium">Kelas</th>
                                <th class="col-3 py-3 fw-medium">Tingkat Depresi</th>
                                <th class="col-2 py-3 fw-medium">Label</th>
                                <th scope="col" class="col-1 py-3 fw-medium">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-divide">
                            @forelse ($students as $key=>$student)
                                @php
                                    $depressionRate=$student->mental_health->get('result')->get('depression_rate') ?? 0;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>
                                        <small>
                                            <div class="fw-medium">{{ $student->nama_lengkap }}</div>
                                            <div class="text-secondary">{{ $student->nisn }}</div>
                                        </small>
                                    </td>
                                    <td>
                                        {{ $student->activeClass->first()?->nama ?? "-" }}
                                    </td>
                                    <td class="alignment-end">
                                    <div class="progress rounded-pill w-75" style="height: 10px;">
                                        <div class="progress-bar {{ $depressionRate >= 70 ? 'bg-success' : 'bg-danger' }}" role="progressbar" style="width: {{ $depressionRate }}%;" aria-valuenow="{{ $depressionRate }}" aria-valuemin="0" aria-valuemax="100">
                                        
                                        </div>
                                    </div>
                                    <div class="w-75 d-flex flex-wrap">
                                        <div style="width: {{ $depressionRate }}%;min-width: fit-content" class="fs-6">
                                            <small>{{ $depressionRate }}%</small>
                                        </div>
                                    </div>
                                    </td>
                                    <td>
                                        @if($depressionRate >= 70)
                                        <div class="badge badge-sm bg-success rounded-pill">Normal</div>
                                        @else
                                        <div class="badge badge-sm bg-danger rounded-pill">Depresi</div>
                                        @endif
                                    </td>
                                    <td>
                                        
                                        <button 
                                        class="btn btn-sm btn-primary"
                                        onclick='setDetailedView({{ $student }}, {{ $student->mental_health->get("detail") }})'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top d-flex justify-content-between no-after">
                <section class="d-flex">
                  
                    
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
                                <a 
                                href="#" 
                                class="page-link">
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

                    <div class="form-group ml-2">
                        <select name="" id="" class="form-control bg-light">
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
        <div class="card shadow-sm bg-success bg-gradient bg-opacity-50 mb-2">
            <div class="card-body d-flex align-items-center gap-2" x-data="{student_data : {}}" x-ref="student_data_container">
                <div class="img-container col-2" style="aspect-ratio: 1/1;">
                    <img 
                    :src="student_data.user?.avatar_url ?
                    `http://localhost:8000/files/images/users/id/${student_data.id_user}/${student_data.user.avatar_url}` :
                    'http://localhost:8000/files/images/users/default'" 
                    alt="" 
                    class="w-100 img-fluid border border-2 border-white rounded-circle"
                    style="object-fit: contain;">
                </div>
                <div class="text-container col-8">
                    <h3 class="font-weight-bold h4 text-white">
                        <div x-text="student_data.nama_lengkap || 'Profile Name'">Profile Name</div>
                    </h3>
                    <h4 class="h6 text-secondary text-light">
                        <div x-text="student_data.nisn || 'NISN'">NISN</div>
                    </h4>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-2">
            <div class="card-body d-flex align-items-center">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="h5 font-weight-bold text-info">Diagram Mental</h2>
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
            <div class="card-header no-after py-3 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                <h2 class="h5 fw-medium text-black-50">Riwayat Mental</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="col-2 py-3 fw-medium">
                                    No
                                </th>
                                <th class="col-6 py-3 fw-medium">
                                    Date
                                </th>
                                <th class="col-3 py-3 fw-medium">
                                    Result
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody x-data="{history_data : [], index : 0, num : 0}" x-ref="history_data_container">
                            <template x-if="history_data.length > 0">
                                <template x-for="item in history_data" :key="index">
                                    <tr>
                                        <td x-text="num++"></td>
                                        <td>
                                            <div 
                                            class="font-weight-bold" 
                                            x-text="item.waktu">
                                            </div>
                                        </td>
                                        <td>
                                            <div x-text="item.swafoto_pred">
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                
                            </template>
                            <template x-if="history_data.length === 0">
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
    function setDetailedView(student, history)
    {
        const studentContainer = Alpine.$data(document.querySelector('[x-ref="student_data_container"]'));
        const historyContainer = Alpine.$data(document.querySelector('[x-ref="history_data_container"]'));
        studentContainer.student_data=student;
        historyContainer.history_data=history;
        loadPaginatedData(history);
        // loadDiagram(history);
    }

    
</script>
@endsection
