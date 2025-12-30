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
        today : {{ now()->day }},
        calendarMonth : {{ (int) request('month', now()->month)-1 }},
        currentMonth : {{ (int) now()->month-1 }},
        calendars : {{ Js::from($calendars) }},
        vacations : {{ Js::from($vacations) }}, 
        vacation : {}, 
        current : 0, 
        index : 0,
        loading : true,
        isEditing : false,
        selectedDates : [],
        isToday(date)
        {
            return this.today===date && this.calendarMonth===this.currentMonth;
        },
        async showEvents(date)
        {
            const container=Alpine.$data(document.querySelector(`[x-ref='events_container']`))

            const lists=await this.vacations.filter(e=>{
                const bulanMulai=e.bulan_mulai;
                const bulanAkhir=e.bulan_selesai;
                const now=date * bulanMulai;
                return e.tanggal_mulai <= date && e.tanggal_selesai >= date;
            })
            

            const day=String(date).padStart(2, '0')
            const month=String(this.calendarMonth+1).padStart(2, '0')
            container.events=lists
            container.date=`${day}-${month}`
            console.log(container.date)
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
        isChosen(date)
        {
            const start = this.selectedDates[0];
            const end = this.selectedDates[1] || start;
            const chosen=start <= date && end >= date;
            return chosen;
        }
    }"

    
    x-ref="dates_container">
        <div class="col-3">
            <form action="{{ route('admin.jadwal-harian.update') }}" method="post">
            @csrf
                <div 
                id="accordionExample" 
                class="accordion accordion-flush border" 
                x-data="{ 
                    data : {{ Js::from($schedules) }}, 
                    grade : 1,
                    schedule : [],
                    days : {
                        0 : 'Senin', 
                        1 : 'Selasa',
                        2 : 'Rabu', 
                        3 : 'Kamis', 
                        4 : 'Jumat',
                        5 : 'Sabtu',
                        6 : 'Minggu'
                    },
                    setSchedule() 
                    {
                        this.schedule=this.data[this.grade]
                    },
                    async initData()
                    {
                        schedule=await this.data;
                        schedule=this.data[this.grade];
                        this.schedule=schedule;
                    }
                }"
                x-init="initData;$watch('grade', _=>setSchedule())"
                >
                    <div class="accordion-item">
                        <h2 class="accordion-header bg-success bg-gradient">
                            <button 
                            type="button" 
                            class="accordion-button fs-5 fw-bold text-primary py-4" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapseOne"
                            aria-expanded="true" 
                            aria-controls="collapseOne"
                            >
                                Jadwal Harian
                            </button>
                        </h2>
                        <div 
                        id="collapseOne" 
                        class="accordion-collapse collapse show" 
                        data-bs-parent="#accordionExample"
                        >
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
                                <select name="jenjang" class="form-select form-select-sm w-auto bg-light" x-model="grade">
                                    <option value="">--Pilih Jenjang--</option>
                                    <template x-for="i in 3" :key="i">
                                        <option :value="i" :selected="i===parseInt(grade)" x-text="'Jenjang '+i"></option>
                                    </template>
                                </select>
                                <x-form-error-text :field="'jenjang'" />

                            </div>
                        </div>
                    </div>

                    {{-- <input type="hidden" name="hari_libur[0]"> --}}
                    <template x-if="schedule?.jadwal?.length > 0">
                        <template x-for="(day, key) in days" :key="day">
                            <div class="accordion-item" :id="'heading'+key">
                                <h2 class="accordion-header">
                                    <button 
                                    class="accordion-button collapsed d-flex align-items-center" 
                                    type="button" 
                                    data-bs-toggle="collapse" 
                                    aria-expanded="true" 
                                    :data-bs-target="'#collapse'+key" 
                                    :aria-controls="'collapse'+key"
                                    >
                                        <input 
                                        type="checkbox" 
                                        class="form-check" 
                                        :name="`hari_libur[${key}]`" 
                                        :value="key" 
                                        :checked="!schedule?.hari_libur.includes(parseInt(key))"
                                        >
                                        <div type="button" class="fs-6 fw-medium ms-2 text-black-50"  x-text="day">
                                        
                                        </div>
                                    </button>
                                </h2>
                                <div 
                                :id="'collapse'+key"
                                class="accordion-collapse collapse" 
                                data-bs-parent="#accordionExample"
                                >
                                    <div class="accordion-body bg-light-subtle">
                                        <div class="row align-items-end justify-content-e px-2">
                                            <div class="col p-0">
                                                <label class="fw-medium mb-1 fs-6">
                                                    <small>Mulai</small>
                                                </label>
                                                <input type="time" :name="`jadwal[${key}][jam_mulai]`" id="" class="form-control rounded-end-0 border-end-0" :value="schedule?.jadwal[key]?.jam_mulai">
                                            </div>
                                            <div class="col p-0">
                                                <label class="fw-medium mb-1 fs-6">
                                                    <small>Akhir</small>
                                                </label>
                                                <input type="time" :name="`jadwal[${key}][jam_akhir]`" id="" class="form-control rounded-0 border-end-0" :value="schedule?.jadwal[key]?.jam_akhir">
                                            </div>
                                            <div class="w-auto p-0">
                                                <button type="submit" class="btn btn-pill btn-primary rounded-start-0">Save</button>
                                            </div>
                                        </div>
                                        <x-form-error-text :field="'jadwal'" />
                                        <x-form-error-text :field="'jadwal'" />

                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>
                    
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
                            <h1 class="fs-5 m-0 d-block text-black-50">
                                <strong class="text-black-50">
                                    {{ \Carbon\Carbon::create()
                                    ->month((int) request('month', now()->month))
                                    ->locale('id')->translatedFormat('F')}}
                                </strong> 
                                - 
                                <span class="font-weight-normal text-black-50">{{ now()->year }}</span>
                            </h1>
                        </div>

                        <div class="input-group m-0 w-auto">
                            <div>
                                <select 
                                name="" 
                                class="form-select bg-secondary-subtle fw-medium opacity-75 d-block rounded-end-0"
                                x-data="
                                {

                                    months : [
                                        'Januari', 'Februari', 'Maret', 
                                        'April', 'Mei', 'Juni', 
                                        'Juli', 'Agustus', 'September', 
                                        'Oktober', 'November', 'Desember'
                                    ],
                                    changeMonth(event) {
                                        let month=event.target.value || 1;
                                        month=parseInt(month)

                                        const baseURL=(window.location.href).split('?')
                                        const url=new URL(baseURL[0])
                                        url.searchParams.set('month', month);
                                        window.location.href=url

                                    },
                                }"
                                x-on:change="changeMonth($event)"
                                
                                >
                                    <template x-for="(month, index) in months">
                                        <option 
                                        :value="index+1" 
                                        :selected="calendarMonth===index"
                                        x-text="month" 
                                        ></option>
                                    </template>
                                </select>
                            </div>

                            <button
                            type="button"
                            x-on:click="isEditing=!isEditing;"
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
                                            <th 
                                            class="col text-center {{ in_array($key, [5, 6]) ? 'text-secondary' : '' }}">{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="week in calendars" :key="index">
                                        <tr>
                                            <template x-for="i in week[0].day">
                                                <td class="col p-0 bg-light"></td>
                                            </template>
                                        
                                            <template 
                                            x-for="day in week" 
                                            :key="index+250"
                                            >
                                                <td 
                                                class="col p-0" 
                                                style="box-sizing: border-box;"
                                                >
                                                    <template x-if="([5,6]).includes(day.day)">
                                                        <button 
                                                        type="button"
                                                        class="w-100 h-100  px-0 rounded-0 border-0  position-relative btn btn-outline-light text-muted" 
                                                        x-data="{
                                                            vacant : false,
                                                            setLibur() {
                                                                current=day.date;   
                                                                this.vacant=vacations.some(vacation=>{
                                                                    return (vacation.tanggal_mulai <= current && current <= vacation.tanggal_selesai);
                                                                })
                                                            }
                                                        }"
                                                        disabled
                                                        x-init="setLibur()"
                                                        >
                                                            <div 
                                                            :class="{
                                                                'bg-warning-subtle' : vacant, 
                                                                'bg-primary-subtle': isToday(day.date)
                                                            }" 
                                                            x-text="day.date">
                                                                
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
                                                                current=day.date    
                                                                this.vacant=vacations.some(vacation=>{
                                                                    return (vacation.tanggal_mulai <= current && current <= vacation.tanggal_selesai);
                                                                })
                                                            },
                                                            handleClick() {
                                                                if(isEditing) chooseDate(day.date);
                                                                else showEvents(day.date)
                                                            }
                                                        }"
                                                        :class="isChosen(day.date) ? 'btn bg-light' : 'btn btn-outline-light'"
                                                        x-on:click='handleClick'

                                                        x-init="setLibur()">
                                                            
                                                            <div :class="{'bg-warning-subtle' : vacant, 'bg-primary-subtle': isToday(day.date)}" x-text="day.date">
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
                                </tbody>
                            </table>
                            <x-form-error-text :field="'id'" />
                            <x-form-error-text :field="'date'" />
                        </div>
                        </ul>
                        
                    </div>
                </form>
            </div>

            <ul class="list-unstyled mt-3" x-data="{events : [], index : 0, date : ''}" x-ref="events_container">
                <template x-for="item in events" :key="index++">
                    <li class="list-item mb-2">
                        <div class="card bg-warning-subtle text-sm">
                            <div class="card-body no-after d-flex justify-content-between align-items-center">
                                <p class="m-0 font-weight-semibold text-black-50" style="font-size: 14px;" x-text="item.ket"></p>
                                <form action="{{ route('admin.presensi-libur.destroy') }}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="id" x-model="item.id">
                                    <input type="hidden" name="date" x-model="date">
                                    <button type="submit" class="text-black-50 btn"><i class="fas fa-times"></i></button>
                                </form>
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
                    const month = calendarMonth;
                    document.getElementById('tanggal_mulai').value= selectedDates[0]
                    document.getElementById('tanggal_selesai').value= selectedDates[1] || selectedDates[0]
                    document.getElementById('bulan_mulai').value= month
                    document.getElementById('bulan_selesai').value= month


                    form.submit();
                }
            }"
            @submit.prevent="submitForm">
                @csrf
                <div class="card">
                    <div class="card-header py-4 bg-warning bg-gradient bg-opacity-50">
                        <h2 class="fs-5 fw-medium m-0 text-black-50">Add Event</h2>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="tanggal_mulai" id="tanggal_mulai">
                        <input type="hidden" name="tanggal_selesai" id="tanggal_selesai">
                        <input type="hidden" name="bulan_mulai" id="bulan_mulai">
                        <input type="hidden" name="bulan_selesai" id="bulan_selesai">
                        <textarea  id="" cols="30" rows="5" class="form-control" name="ket"></textarea>

                        <ul class="list-unstyled d-flex gap-3 mt-2">
                            <li class="d-flex gap-1">
                                <input type="checkbox" name="jenjang[]" value="1" class="form-check">
                                <label for="">1</label>
                            </li>
                            <li class="d-flex gap-1">
                                <input type="checkbox" name="jenjang[]" value="2" class="form-check">
                                <label for="">2</label>
                            </li>
                            <li class="d-flex gap-1">
                                <input type="checkbox" name="jenjang[]" value="3" class="form-check">
                                <label for="">3</label>
                            </li>
                        </ul>
                        <x-form-error-text :field="'jenjang'" />
                        <x-form-error-text :field="'tanggal_mulai'" />
                        <x-form-error-text :field="'tanggal_selesai'" />
                        <x-form-error-text :field="'bulan_mulai'" />
                        <x-form-error-text :field="'bulan_selesai'" />
                        <x-form-error-text :field="'ket'" />

                        <button type="submit" class="btn btn-primary mt-3 w-100">Tambah</button>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.config.diary.update') }}" method="post">
                @csrf
                <div class="card mt-2">
                    <div class="card-body">
                        <label for="" class="form-label fw-medium">
                            <small>Rekap Range</small>
                        </label>
                        <div class="input-group">
                            <input type="number" name="rentang" value="{{ (int) $diaryConfig['rentang'] }}" class="form-control">
                            <div class="input-group-text fs-6">
                                <small>days</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </form>
            
            
        </div>
    </div>
@endsection
