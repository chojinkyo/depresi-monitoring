

@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Siswa</h1>
        </div>
    </div>
@endsection

@section('content')
<x-table
    title="Daftar Siswa"
    :headers="['No', 'NISN', 'Nama Lengkap', 'Gender', 'Kelas', 'Status']"
    addRoute="siswa:create"
>
    <livewire:siswa.table-data />
    <x-slot name='create_form'>
        <livewire:siswa.create-form x-data="{open_modal : @entangle('open_modal')}"/>
    </x-slot>
    <x-slot name='edit_form'>
        <livewire:siswa.edit-form />
    </x-slot>
</x-table>
@endsection
