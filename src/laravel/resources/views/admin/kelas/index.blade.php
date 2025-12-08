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
<div class="row justify-content-center">
    <div class="col-7">
        <x-table
            title="Daftar Kelas"
            :headers="['No', 'Nama Kelas', 'Jenjang', 'Jurusan']"
            addRoute="kelas.create"
        >
            @forelse($classes as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->nama}}</td>
                    <td>{{ $row->jenjang }}</td>
                    <td>{{ $row->jurusan }}</td>
                    <td class="d-flex justify-content-around">
                        <a href="#" class="btn btn-warning btn-xs">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a 
                        href="#" 
                        role="button"
                        class="btn btn-info btn-xs"
                        onclick="event.preventDefault();Livewire.dispatch('kelas-edit', {id : {{ $row->id }}})"
                        data-toggle="modal"
                        data-target="#edit-modal">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a 
                        href="#"
                        role="button"
                        class="btn btn-xs btn-danger"
                        onclick="Livewire.dispatch('swal:confirm', {
                            title  : 'Konfirmasi hapus data',
                            text   : 'Apakah anda yakin ingin menghapus kelas ini?',
                            icon   : 'warning',
                            method : 'kelas:delete',
                            params : {id : {{ $row->id }}}
                        })">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
            @endforelse

            <x-slot name="paginator">
                {{ $classes->links() }}
            </x-slot>
        </x-table>
        
    </div>
    <div class="col-3">
        <div class="card shadow-sm border-top border-top border-4 border-info">
            <div class="card-header py-3">
                <h2 class="h6 font-weight-bold text-info">Tambah Kelas Baru</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.kelas.store') }}" method="POST" class="col-12">
                    @csrf
                    <div class="form-group">
                        <label for="nama_field">Nama</label>
                        <input type="text" id="nama_field" class="form-control" wire:model="form.nama">
                        <x-form-error-text :field="'nama'" />
                    </div>
                    
                    <div class="form-group">
                        <label for="jenjang_field">Jenjang</label>
                        <select class="form-control" id="jenjang_field" wire:model="form.jenjang">
                            <option value="1" selected="true">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                        <x-form-error-text :field="'jenjang'" />
                    </div>
                    
                    <div class="form-group">
                        <label for="">Jurusan</label>
                        <select id="jurusan_field" class="form-control" wire:model="form.jurusan">
                            <option value="IPA">IPA</option>
                            <option value="IPS">IPS</option>
                        </select>
                        <x-form-error-text :field="'jurusan'" />
                    </div>
                    <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                </form>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</div>
@endsection