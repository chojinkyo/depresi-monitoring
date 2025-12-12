

@extends('layouts.admin')

@section('title', 'Index Siswa')

@php
    $pageTitle = 'Data Siswa';
    $pageSubtitle = 'Manajemen Data Siswa';
@endphp

@section('content')
<x-table
    title="Daftar Siswa"
    :headers="['No', 'NISN', 'Nama Lengkap', 'Gender', 'Kelas']"
    addRoute="admin.siswa.create"
>
    @foreach ($siswa as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nisn}}</td>
            <td>{{ $row->nama_lengkap }}</td>
            <td class="d-flex justify-content-around">
                <a href="{{ route('admin.siswa.edit', $row->id) }}" class="btn btn-warning btn-xs">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.siswa.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus data?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-xs">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    <x-slot name='create_form'>
        <livewire:siswa-form x-data="{open_modal : @entangle('open_modal')}"/>
    </x-slot>
</x-table>
@endsection
