@extends('layouts.admin')

@section('title', 'Index Siswa')

@php
    $pageTitle = 'Data Kelas';
    $pageSubtitle = 'Manajemen Data Kelas';
@endphp

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