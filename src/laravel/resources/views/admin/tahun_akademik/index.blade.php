@extends('adminlte::page')

@section('title', 'Index Tahun Akademik')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Tahun Akademik</h1>
        </div>
    </div>
@endsection

@section('content')
<x-table
    title="Daftar Tahun Akademik"
    :headers="['No', 'Periode', 'Tanggal Mulai', 'Tanggal Selesai', 'Status']"
    addRoute="kelas.create"
>
    <livewire:tahun-akademik.table-data />
    
    <x-slot name='create_form'>
        <livewire:tahun-akademik.create-form />
    </x-slot>
    <x-slot name='edit_form'>
        <livewire:tahun-akademik.edit-form />
    </x-slot>
</x-table>
@endsection