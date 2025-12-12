@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <p>lorem ipsum dolor sit amet conseptectur adipiscing elit</p>
    <div class="row justify-content-center">
        <div class="col-5">
            <div class="card border-top border-info">
                <form action="" method="post">
                    @csrf
                    <div class="card-header py-0 no-after d-flex justify-content-between align-items-center">
                        <div class="py-4">
                            <h1 class="h4 m-0 p-0 text-dark d-block text-info">
                                <strong class="text-info">{{ now()->format('F')}}</strong> - <span class="font-weight-normal text-info">{{ now()->year }}</span>
                            </h1>
                        </div>
                        
                        <div class="form-group m-0">
                            <select 
                            id="" 
                            name="" 
                            class="form-control bg-light">
                                <option value="">Januari</option>
                            </select>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- <ul class="list-unstyled d-flex bg-light">
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
                                            <li class="text-center col p-0">
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
                            @endforeach --}}

                            <div class="table-responsive">
                                <table class="table table-bordered" style="table-layout: fixed;">
                                    <thead class="bg-light">
                                        @php
                                            $headers=['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
                                        @endphp

                                        <tr>
                                            @foreach ($headers as $key=>$header)
                                                <th class="col text-center {{ in_array($key, [5, 6]) ? 'text-secondary' : '' }}">{{ $header }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $index=0;
                                        @endphp
                                        @foreach ($calendars as $week)
                                            <tr>
                                                {{-- {{ dd($vacations) }} --}}
                                                @for ($i=0;$i<$week[0]['day'];$i++)
                                                    <td class="col p-0">
                                                       
                                                    </td>
                                                @endfor
                                                
                                                @foreach ($week as $day)
                                                    <td class="col p-0" style="box-sizing: border-box;">
                                                        <button 
                                                        class="w-100 h-100  px-0 rounded-0 border-0  position-relative {{ in_array($day['day'], [5, 6]) ? 'btn text-secondary' : 'btn btn-outline-light text-dark' }}" 
                                                        >
                                                        @php
                                                            if($index < count($vacations)) {
                                                                $vacation=$vacations[$index];
                                                                $current=$day['date'];
                                                                $vacant=false;
                                                                if($current >= $vacation['tanggal_mulai'] && $current <= $vacation['tanggal_selesai'])
                                                                    $vacant=true;
                                                                if($current >= $vacation['tanggal_selesai'])
                                                                    $index++;
                                                            }
                                                        @endphp
                                                            <div class="{{ $vacant ? 'bg-info' : '' }}">
                                                                {{ $day['date'] }}
                                                            </div>
                                                        </button>
                                                    </td>
                                                @endforeach
                                                @for ($i=$week[count($week)-1]['day'];$i<6;$i++)
                                                    <td class="col p-0"></td>
                                                @endfor
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
    </div>
@endsection