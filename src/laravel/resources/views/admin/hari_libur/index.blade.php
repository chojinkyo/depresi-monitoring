

@extends('layouts.admin')

@section('title', 'Daftar Hari Libur')

@php
    $pageTitle = 'Hari Libur';
    $pageSubtitle = 'Manajemen Hari Libur & Kalender';
@endphp


@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-5">
            <div class="card">
                <form action="" method="post">
                    @csrf
                    <div class="card-header py-0 no-after d-flex justify-content-between align-items-center">
                        <div class="py-4">
                            <h1 class="h3 m-0 p-0 text-dark d-block">
                                <strong>Februari</strong> - <span class="font-weight-normal">2024</span>
                            </h1>
                        </div>
                        <button class="btn ">
                            &lt;
                        </button>
                        <button class="btn">
                            >
                        </button>
                        <div class="form-group m-0">
                            <select 
                            id="" 
                            name="" 
                            class="form-control bg-secondary-subtle">
                                <option value="">Januari</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled d-flex bg-light">
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Su</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Mo</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Tu</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">We</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Th</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Fr</li>
                            <li class="col d-flex align-items-center justify-content-center font-weight-bold" style="aspect-ratio: 16/9;">Sa</li>
                        </ul>
                        <ul class="list-unstyled">
                            @foreach ($days as $cal)
                                <li>
                                    <ul class="list-unstyled d-flex">
                                        @for ($i=0;$i<$cal[0]['day'];$i++)
                                            <li class="col p-0"></li>
                                        @endfor

                                        @foreach ($cal as $c)
                                            <li class="text-center col p-0" style="aspect-ratio: 1/1;">
                                                <button class="p-0 m-0 btn bg-light rounded-pill font-weight-medium text-dark" style="width:  50px;aspect-ratio: 1/1;">
                                                    {{ $c['date'] }}
                                                </button>
                                            </li>
                                        @endforeach
                                        @for ($i=$cal[count($cal)-1]['day'];$i<6;$i++)
                                            <li class="col p-0"></li>
                                        @endfor
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                        
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </form>


                
            </div>

            
        </div>

        <div class="col-3">
            <form action="" method="post">
                
                <div class="accordion" id="accordionExample">
                    <div class="card m-0" id="heading0">
                    <div class="card-header no-after d-flex align-items-center">
                        <h2 class="m-0">
                            <button type="button" class="btn btn-block text-left pl-0 font-weight-bold"  data-toggle="collapse" data-target="#collapse0" aria-expanded="true" aria-controls="collapse0">
                                Jadwal Harian
                            </button>
                        </h2>
                    </div>
                    <div class="collapse" id="collapse0" aria-labelledby="heading0" data-parent="#accordionExample">
                        <div class="card-body">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero laudantium eos, ipsum nesciunt quis hic?
                        </div>
                    </div>
                </div>
                    <div class="card m-0" id="heading1">
                        <div class="card-header no-after d-flex align-items-center">
                            <input type="checkbox" name="" id="" class="form-check">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link btn-block text-left mb-0"  data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    Senin
                                </button>
                            </h2>
                        </div>
                        <div class="collapse show" id="collapse1" aria-labelledby="heading1" data-parent="#accordionExample">
                            <div class="card-body">
                                <label for="">Jadwal</label>
                                <div class="form-row align-items-end">
                                    
                                    <div class="col">
                                        <label for="" class="font-weight-normal">Start</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="" class="font-weight-normal">End</label>
                                        <input type="text" name="" id="" class="form-control">
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-pill btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card m-0" id="heading2">
                        <div class="card-header no-after d-flex align-items-center">
                            <input type="checkbox" name="" id="" class="form-check">
                            <h2 class="mb-0">
                                <button type="button" class="btn btn-link btn-block text-left"  data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                                    Selasa
                                </button>
                            </h2>
                        </div>
                        <div class="collapse" id="collapse2" aria-labelledby="heading2" data-parent="#accordionExample">
                            <div class="card-body">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero laudantium eos, ipsum nesciunt quis hic?
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-3 px-0">
            <ul class="list-unstyled">
                <li>
                    <div class="alert alert-info alert-dismissible fade show" >
                        <h4 class="alert-heading">Coba Coba</h4>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Saepe, dolores. Odio cum impedit molestias totam?</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>



@endsection
