@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <h1>Kelas</h1>
@endsection

@section('content')
<x-table
    title="Daftar Kelas"
    :headers="['No', 'Nama Kelas', 'Jenjang', 'Jurusan', 'Jumlah Siswa']"
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