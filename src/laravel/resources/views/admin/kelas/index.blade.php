@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Kelas</h1>
        </div>
    </div>
@endsection

@section('content')
<x-table
    title="Daftar Kelas"
    :headers="['No', 'Nama Kelas', 'Jenjang', 'Jurusan']"
    addRoute="kelas.create"
>
    <livewire:kelas.table-data />
    
    <x-slot name='create_form'>
        <livewire:kelas.create-form />
    </x-slot>
    <x-slot name='edit_form'>
        <livewire:kelas.edit-form />
    </x-slot>
</x-table>
@endsection