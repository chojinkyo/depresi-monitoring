

@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <h1>Siswa</h1>
@endsection

@section('content')
<x-table
    title="Daftar Siswa"
    :headers="['No', 'NISN', 'Nama Lengkap', 'Gender', 'Kelas']"
    addRoute="hari-libur.create"
>
    @foreach ($siswa as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nisn}}</td>
            <td>{{ $row->nama_lengkap }}</td>
            <td class="d-flex justify-content-around">
                <a href="{{ route('hari-libur.edit', $row->id) }}" class="btn btn-warning btn-xs">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('hari-libur.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus data?');">
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
