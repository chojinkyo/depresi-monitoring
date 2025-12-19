@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@php
    $pageTitle = 'Dashboard';
    $pageSubtitle = 'Panel Administrasi';
@endphp

@section('content')
    
    <div 
    class="row justify-content-center"
    x-data="{
        calendars : {{ Js::from($calendars) }},
        vacations : {{ Js::from($vacations) }}, 
        vacation : {}, 
        current : 0, 
        index : 0,
        loading : true,
        isEditing : false,
        selectedDates : [],
        async initData() 
        {
            

            console.log(this.calendars);
        },
        async showEvents(date)
        {
            const container=Alpine.$data(document.querySelector(`[x-ref='events_container']`))

            const lists=await this.vacations.filter(e=>{
                const bulanMulai=e.bulan_mulai;
                const bulanAkhir=e.bulan_selesai;
                const now=date * bulanMulai;
                console.log(date);
                return e.tanggal_mulai * bulanMulai <= now && e.tanggal_selesai * bulanAkhir >= now;
            })
            console.log(lists)

            container.events=lists
        },
        chooseDate(date)
        {
        
            if(!this.isEditing) return
            if(this.selectedDates.length >= 2)
            {
                this.selectedDates=[]
                this.selectedDates.push(date)
                return;
            }
            this.selectedDates.push(date)
        
            this.selectedDates.sort((a,b)=>a - b);
        },
        isChoosen(date)
        {
            const start = this.selectedDates[0];
            const end = this.selectedDates[1] || start;
            const chosen=start <= date && end >= date;
            return chosen;
        }
    }"

    x-init="initData"
    x-ref="dates_container">
        <div class="col-3">
            <form action="" method="post">
                <div class="accordion accordion-flush border" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-success bg-gradient">
                            <button class="accordion-button fs-5 fw-bold text-primary py-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Jadwal Harian
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quae, qui!
                            </div>
                        </div>
                        <hr class="m-0">
                        <div class="d-flex align-items-center justify-content-between px-3 py-3">
                            <div class="d-flex align-items-center w-auto px-1">
                                <input type="checkbox" class="form-check">
                                <label for="" class="font-weight-normal ms-2 fw-medium">Select All</label>
                            </div>
                            <div class="d-block">
                                <select class="form-select form-select-sm w-auto bg-light">
                                    <option value="">--Pilih Jenjang--</option>
                                    <option value="1">Jenjang 1</option>
                                    <option value="2">Jenjang 2</option>
                                    <option value="3">Jenjang 3</option>
                                </select>
                            </div>
                        </div>
                    </div>

            

                    @php
                        $dayNames=['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']
                    @endphp
                    
                    @foreach ($dayNames as $key=>$dayName)
                        @php
                            $key=$key+1;
                        @endphp
                        <div class="accordion-item" id="heading{{ $key }}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                                    <input type="checkbox" class="form-check">
                                    <div type="button" class="fs-6 fw-medium ms-2 text-black-50"  >
                                        {{ $dayName }}
                                    </div>
                                </button>
                            </h2>
                            <div  id="collapse{{ $key }}" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body bg-light-subtle">
                                    <div class="row align-items-end justify-content-e px-2">
                                        <div class="col p-0">
                                            <label class="fw-medium mb-1 fs-6">
                                                <small>Mulai</small>
                                            </label>
                                            <input type="time" name="" id="" class="form-control rounded-end-0 border-end-0">
                                        </div>
                                        <div class="col p-0">
                                            <label class="fw-medium mb-1 fs-6">
                                                <small>Akhir</small>
                                            </label>
                                            <input type="time" name="" id="" class="form-control rounded-0 border-end-0">
                                        </div>
                                        <div class="w-auto p-0">
                                            <button class="btn btn-pill btn-primary rounded-start-0">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                </div>
            </form>
        </div>
        <div class="col-5">
            <div 
            class="card"
            >
                <form 
                action="" 
                method="post">
                    @csrf
                    <div class="card-header py-0 no-after d-flex justify-content-between align-items-center bg-success bg-gradient bg-opacity-50">
                        <div class="py-4">
                            <h1 class="fs-5 m-0 p-0 text-dark d-block">
                                <strong class="text-black-50">{{ now()->format('F')}}</strong> - <span class="font-weight-normal text-black-50">{{ now()->year }}</span>
                            </h1>
                        </div>
                        
                        <div class="form-group d-flex m-0">
                            <select 
                            id="" 
                            name="" 
                            class="form-select bg-secondary-subtle fw-medium opacity-75">
                                <option value="">Januari</option>
                            </select>

                            <button
                            type="button"
                            x-on:click="isEditing=!isEditing;console.log(isEditing)"
                            class="btn btn-warning btn-sm"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        

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
                                   
                                        
                                            <template x-for="week in calendars" :key="index">
                                                <tr>
                                                    <template x-for="i in week[0].day">
                                                        <td class="col p-0 bg-light">
                                                            
                                                        </td>
                                                    </template>
                                                
                                                    
                                                    <template x-for="day in week" :key="index+250">
                                                        <td class="col p-0" style="box-sizing: border-box;">
                                                            <template x-if="([5,6]).includes(day.day)">
                                                                <button 
                                                                type="button"
                                                                class="w-100 h-100  px-0 rounded-0 border-0  position-relative btn btn-outline-light text-muted" disabled
                                                                x-data="{
                                                                    vacant : false,
                                                                    setLibur() {
                                                                        if(index < vacations.length)
                                                                        {
                                                                            vacation=vacations[index];
                                                                            current=day.date;

                                                                            const bulanMulai=vacation.bulan_mulai;
                                                                            const bulanAkhir=vacation.bulan_selesai;
                                                                            const now=current * bulanMulai;
                                                                            
                                                                            this.vacant=(vacation.tanggal_mulai * bulanMulai <= now && now <= vacation.tanggal_selesai * bulanAkhir);
                                                                            if(current>=vacation.tanggal_selesai) index++;
                                                                        }
                                                                    }
                                                                }"
                                                                x-init="setLibur()">
                                                                
                                                                    <div :class="{'bg-warning-subtle' : vacant}" x-text="day.date">
                                                                        
                                                                    </div>
                                                                </button>
                                                            </template>

                                                            <template x-if="!([5,6]).includes(day.day)">
                                                                <button 
                                                                
                                                                type="button"
                                                                class="w-100 h-100  px-0 rounded-0 border-0  position-relative text-body" 
                                                                
                                                                x-data="{
                                                                    vacant : false,
                                                                    setLibur() {
                                    
                                                                        if(index < vacations.length)
                                                                        {
                                                                            
                                                                            vacation=vacations[index];
                                                                            current=day.date;

                                                                            const bulanMulai=vacation.bulan_mulai;
                                                                            const bulanAkhir=vacation.bulan_selesai;
                                                                            const now=current * bulanMulai;
                                                                            
                                                                            this.vacant=(vacation.tanggal_mulai * bulanMulai <= now && now <= vacation.tanggal_selesai * bulanAkhir);
                                                                            if(current>=vacation.tanggal_selesai) index++;
                                                                        }
                                                                    },
                                                                    handleClick() {
                                                                        if(isEditing) chooseDate(day.date);
                                                                        else showEvents(day.date)
                                                                    }
                                                                }"
                                                                :class="isChoosen(day.date) ? 'btn bg-light' : 'btn btn-outline-light'"
                                                                x-on:click='handleClick'

                                                                x-init="setLibur()">
                                                                    
                                                                    <div :class="{'bg-warning-subtle' : vacant}" x-text="day.date">
                                                                        
                                                                    </div>
                                                                </button>
                                                            </template>
                                                            
                                                        </td>
                                                    </template>

                                                    <template x-for="_ in 6 - (week[week.length-1]['day'] || 0)">
                                                        <td class="col p-0 bg-light"></td>
                                                    </template>
                                                    
                                                
                                                </tr>
                                            </template>
                                        
                                            
                                            @php
                                                $current=0;
                                                $index=0;
                                            @endphp
                                    {{-- @foreach ($calendars as $week)
                                        <tr>
                                            @for ($i=0;$i<$week[0]['day'];$i++)
                                                <td class="col p-0 bg-light">
                                                    
                                                </td>
                                            @endfor
                                            
                                            @foreach ($week as $day)
                                                <td class="col p-0" style="box-sizing: border-box;">
                                                    @if(in_array($day['day'], [5, 6]))
                                                        <button 
                                                        type="button"
                                                        class="w-100 h-100  px-0 rounded-0 border-0  position-relative btn btn-outline-light text-muted" disabled
                                                        >
                                                            @php
                                                                $vacant=false;
                                                                if($index < count($vacations)) {
                                                                    $vacation=$vacations[$index];
                                                                    $current=$day['date'];
                                                                    if($current >= $vacation['tanggal_mulai'] && $current <= $vacation['tanggal_selesai'])
                                                                        $vacant=true;
                                                                    if($current >= $vacation['tanggal_selesai'])
                                                                        $index++;
                                                                }
                                                            @endphp
                                                            <div class="{{ $vacant ? 'bg-warning-subtle' : '' }}">
                                                                {{ $day['date'] }}
                                                            </div>
                                                        </button>
                                                    @else
                                                        <button 
                                                        type="button"
                                                        class="w-100 h-100  px-0 rounded-0 border-0  position-relative btn btn-outline-light text-body" 
                                                        onclick='showEvents(@json($vacations), {{ $current+1 }})'
                                                        >
                                                            @php
                                                                $vacant=false;
                                                                if($index < count($vacations)) {
                                                                    $vacation=$vacations[$index];
                                                                    $current=$day['date'];
                                                                    if($current >= $vacation['tanggal_mulai'] && $current <= $vacation['tanggal_selesai'])
                                                                        $vacant=true;
                                                                    if($current >= $vacation['tanggal_selesai'])
                                                                        $index++;
                                                                }
                                                            @endphp
                                                            <div class="{{ $vacant ? 'bg-warning-subtle' : '' }}">
                                                                {{ $day['date'] }}
                                                            </div>
                                                        </button>
                                                    @endif
                                                </td>
                                            @endforeach
                                            @for ($i=$week[count($week)-1]['day'];$i<6;$i++)
                                                <td class="col p-0 bg-light"></td>
                                            @endfor
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                        </ul>
                        
                    </div>
                </form>
            </div>

            <ul class="list-unstyled mt-3" x-data="{events : [],index : 0}" x-ref="events_container">
                <template x-for="item in events" :key="index++">
                    <li class="list-item mb-2">
                        <div class="card bg-warning-subtle text-sm">
                            <div class="card-body">
                                <p class="m-0 font-weight-semibold text-dark" style="font-size: 14px;" x-text="item.ket">Lorem ipsum dolor sit amet consectetur adipisicing elit. Non, quam!</p>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
            
        </div>

        <div class="col-3">
            <form action="{{ route('admin.presensi-libur.store') }}" method="post" x-data="{
                async submitForm(event) {
                    const form = event.target;
                    const month = new Date().getMonth();
                    document.getElementById('tanggal_mulai').value= selectedDates[0]
                    document.getElementById('tanggal_selesai').value=  selectedDates[1] || selectedDates[0]
                    document.getElementById('bulan_mulai').value= month
                    document.getElementById('bulan_selesai').value= month


                    form.submit();
                }
            }"
            @submit.prevent="submitForm">
                @csrf
                <div class="card">
                <div class="card-header py-4 bg-secondary bg-gradient bg-opacity-25">
                    <h2 class="fs-5 fw-medium m-0">Add Event</h2>
                </div>
                <div class="card-body">
                    <input type="hidden" name="tanggal_mulai" id="tanggal_mulai">
                    <input type="hidden" name="tanggal_selesai" id="tanggal_selesai">
                    <input type="hidden" name="bulan_mulai" id="bulan_mulai">
                    <input type="hidden" name="bulan_selesai" id="bulan_selesai">
                    <textarea  id="" cols="30" rows="5" class="form-control" name="ket"></textarea>

                    <ul class="list-unstyled d-flex">
                        <li>
                            <input type="checkbox" name="jenjang[]" value="1" class="form-check">
                            <label for="">1</label>
                        </li>
                        <li>
                            <input type="checkbox" name="jenjang[]" value="2" class="form-check">
                            <label for="">2</label>
                        </li>
                        <li>
                            <input type="checkbox" name="jenjang[]" value="3" class="form-check">
                            <label for="">3</label>
                        </li>
                    </ul>
                    <x-form-error-text :field="'jenjang[]'" />

                    <button type="submit" class="btn btn-primary mt-3 w-100 rounded-0">Tambah</button>
                </div>
            </div>
            </form>
            
        </div>
    </div>
<script>
    function showEvents(events_list, current)
    {
        const container=Alpine.$data(document.querySelector('[x-ref="events_container"]'))
        const lists=events_list.filter(e=>{
            const bulanMulai=e.bulan_mulai;
            const bulanAkhir=e.bulan_selesai;
            const now=current * bulanMulai;
            return e.tanggal_mulai * bulanMulai <= now && e.tanggal_selesai * bulanAkhir >= now;
        })
        
        container.events=lists
    }
    function calendarRange(dates) {
    return {
        dates,

        editMode: false,
        startDate: null,
        endDate: null,
        selectedDates: [],

        toggleEdit() {
            this.editMode = !this.editMode
            this.reset()
        },

        setStart(date) {
            if (!this.editMode) return
            this.startDate = date
            this.endDate = null
            this.selectedDates = []
        },

        setEnd(date) {
            if (!this.editMode || !this.startDate) return
            this.endDate = date
            this.buildRange()
        },

        buildRange() {
            let start = new Date(this.startDate)
            let end   = new Date(this.endDate)

            if (start > end) [start, end] = [end, start]

            this.selectedDates = []

            while (start <= end) {
                this.selectedDates.push(start.toISOString().slice(0, 10))
                start.setDate(start.getDate() + 1)
            }
        },

        isSelected(date) {
            return this.selectedDates.includes(date)
        },

        reset() {
            this.startDate = null
            this.endDate = null
            this.selectedDates = []
        }
    }
}
</script>
@endsection
