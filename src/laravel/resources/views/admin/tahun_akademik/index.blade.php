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
<div class="row justify-content-center">
    <div class="col-7">
        <x-table
            title="Daftar Tahun Akademik"
            :headers="['No', 'Periode', 'Tanggal Mulai', 'Tanggal Selesai', 'Status']"
            addRoute="kelas.create"
        >
            @forelse ($academicYears as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->nama_tahun}}</td>
                    <td>{{ $row->tanggal_mulai }}</td>
                    <td>{{ $row->tanggal_selesai }}</td>
                    <td>
                        @if($row->current)
                            <span class="badge badge-pill badge-success">current</span>
                        @elseif($row->status)
                            <span class="badge badge-pill badge-info">opened</span>
                        @else
                            <span class="badge badge-pill badge-danger">closed</span>
                        @endif
                        
                    </td>
                    <td class="d-flex justify-content-around">
                        <a href="#" class="btn btn-warning btn-xs">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a 
                        href="#" 
                        role="button"
                        class="btn btn-info btn-xs"
                        onclick="event.preventDefault();Livewire.dispatch('tahun_akademik-edit', {id : {{ $row->id }}})"
                        data-toggle="modal"
                        data-target="#edit-modal">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a 
                        href="#"
                        role="button"
                        class="btn btn-xs btn-danger"
                        onclick="Livewire.dispatch('swal:confirm', {
                            title : 'Konfirmasi hapus data',
                            text : 'Apakah anda yakin ingin menghapus tahun akademik ini?',
                            icon : 'warning',
                            method : 'tahun_akademik:delete',
                            params : {id : {{ $row->id }}}
                        })">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
            @endforelse
        </x-table>
    </div>
    <div class="col-3">
        <div class="card shadow-sm border-top border-top border-4 border-info">
            <div class="card-header">
                <h2 class="h6 font-weight-bold text-info">Form Tahun Akademik</h2>
            </div>
            <div class="card-body px-1">
                <form action="" method="POST" class="col-12"  wire:submit="save()">
                @csrf
                <div class="form-group">
                    <label for="tahun_mulai_field">Periode Tahun</label>
                    <div class="input-group">
                        
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" id="tahun_mulai_field" placeholder="Awal" wire:model="form.tahun_mulai">
                        <input type="text" class="form-control" id="tahun_akhir_field" placeholder="Akhir" wire:model="form.tahun_akhir">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="checkbox" id="status_field" wire:model="form.status">
                                <label class="p-0 m-0 pl-2 d-inline font-weight-normal">Open</label>
                            </div>
                        </div>
                        
                    </div>
                    <x-form-error-text :field="'periode'" />
                    <x-form-error-text :field="'tahun_mulai'" />
                    <x-form-error-text :field="'tahun_akhir'" />
                    <x-form-error-text :field="'status'" />
                </div>
                
                
                <div class="form-group">
                    <label for="tanggal_mulai_field">Tanggal</label>
                    <div class="d-flex" style="gap: 1rem;">
                        <div class="form-group flex-fill px-0">
                            <label for="tanggal_mulai_field" class="font-weight-normal">Mulai</label>
                            <input type="date" class="form-control" id="tanggal_mulai_field" wire:model="form.tanggal_mulai">
                            <x-form-error-text :field="'tanggal_mulai'" />
                        </div>
                        
                        <div class="form-group flex-fill px-0">
                            <label for="tanggal_selesai_field" class="font-weight-normal">Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai_field" wire:model="form.tanggal_selesai">
                            <x-form-error-text :field="'tanggal_selesai'" />
                        </div>
                    </div>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox"  id="current_field" class="form-check-input" wire:model="form.current" value="true">
                    <label for="" class="font-weight-normal">Set as current</label>
                    <x-form-error-text :field="'current'" />

                </div>
                <button type="submit" class="btn btn-lg btn-info w-100">Save</button>
            </form>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
@endsection