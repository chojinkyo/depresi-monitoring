@extends('layouts.admin')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Kelas</h1>
        </div>
    </div>
@endsection


@section('content')

<div class="row justify-content-center"  x-data="{selected_id : `{{ old('id') }}`}">
    <div class="col-6">
        <x-table
            title="Daftar Kelas"
            :headers="['No', 'Nama Kelas', 'Jenjang', 'Jurusan']"
            addRoute="kelas.create"
        >
            @forelse($classes as $i => $row)
                <tr :class="{'table-active' : (selected_id=={{ $row->id }})}">
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $row->nama}}</td>
                    <td>{{ $row->jenjang }}</td>
                    <td>{{ $row->jurusan }}</td>
                    <td class="">
                        <div class="d-flex gap-1">
                            <a 
                            href="#" 
                            class="btn btn-outline-primary btn-sm"
                            >
                                <i class="fas fa-eye"></i>
                            </a>
                            <a 
                            href="#" 
                            role="button"
                            class="btn btn-outline-warning btn-sm"
                            onclick="event.preventDefault();editForm('{{ $row->id }}', `{{ route('admin.kelas.update', ['kelas'=>$row->id]) }}`)"
                            x-on:click="selected_id={{ $row->id }};"
                            >
                                <i class="fas fa-edit"></i>
                            </a>
                            <a 
                            href="#"
                            role="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="
                            event.preventDefault();
                            setDeleteForm(`{{ route('admin.kelas.destroy', ['kelas'=>$row->id]) }}`);
                            window.dispatchEvent(new CustomEvent('swal:confirm', {detail : {
                                title : 'Konfirmasi hapus data',
                                text : 'Apakah anda yakin ingin menghapus kelas ini?',
                                icon : 'warning',
                                method : submitDeleteForm,
                            }}))"
                            >
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="bg-light-subtle text-center text-black-50">
                        No Class Found
                    </td>
                </tr>
            @endforelse

            <x-slot name="paginator">
                {{ $classes->links() }}
            </x-slot>
        </x-table>
        
    </div>
    <div class="col-3">
        <div class="card shadow-sm">
            <div class="card-header no-after py-4 d-flex justify-content-between align-items-center bg-warning bg-gradient bg-opacity-50">
                <h2 class="h5 font-weight-bold text-black-50">Form Kelas</h2>
                <a 
                href="#"
                role="button"
                class="btn"
                onclick="event.preventDefault();resetForm(`{{ route('admin.kelas.store') }}`)">
                    Clear
                </a>
            </div>
            <div class="card-body">
                <form method="post" class="d-none" id="form-delete">
                    @csrf
                    @method('DELETE')
                </form>
                <form 
                @if(old('id')==null)
                action="{{ route('admin.kelas.store') }}" 
                @else
                action="{{ route('admin.kelas.update', ['kelas'=>old('id')]) }}" 
                @endif
                method="POST" id="form-kelas" class="col-12">
                    @csrf
                    <input type="hidden" name="id" id="id_field" x-model="selected_id">
                    <input type="hidden" name="_method" id="_method_field" value="{{ old('id') ? 'PUT' : 'POST' }}">
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="nama_field">
                            <small>Nama</small>
                        </label>
                        <input type="text" id="nama_field" class="form-control" name="nama" value="{{ old('nama') }}">
                        <x-form-error-text :field="'nama'" />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="jenjang_field">
                            <small>Jenjang</small>
                        </label>
                        <select class="form-select" id="jenjang_field" name="jenjang">
                            @for ($i=1;$i<=3;$i++)
                                <option value="{{ $i }}" @selected(old('jenjang')==$i)>{{ $i }}</option>
                            @endfor
                        </select>
                        <x-form-error-text :field="'jenjang'" />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="">
                            <small>Jurusan</small>
                        </label>
                        <select id="jurusan_field" class="form-select" name="jurusan">
                            <option value="IPA" @selected(old('jurusan')=='IPA')>IPA</option>
                            <option value="IPS" @selected(old('jurusan')=='IPS')>IPS</option>
                        </select>
                        <x-form-error-text :field="'jurusan'" />
                    </div>
                    <button type="submit" class="btn btn-primary bg-gradient w-100">Simpan</button>
                </form>
            </div>
            
        </div>
    </div>
</div>
<script>
    const deleteForm=document.getElementById('form-delete');
    const formKelas=document.getElementById('form-kelas');
    const namaField=document.getElementById('nama_field');
    const methodField=document.getElementById('_method_field');
    const jenjangField=document.getElementById('jenjang_field');
    const jurusanField=document.getElementById('jurusan_field');

    const classes=@json($classes->items())

    const submitDeleteForm=()=>deleteForm.submit()
    const setDeleteForm=route=>deleteForm.action=route;
    function editForm(id, route) {
        const _class=classes.filter(c => c.id==id)
        const form=_class[0]

        formKelas.action=route;
        methodField.value='PUT';
        namaField.value=form.nama;
        jenjangField.value=form.jenjang;
        jurusanField.value=form.jurusan;
    }

    function resetForm(route)
    {
        namaField.value='';
        jenjangField.value='1';
        jurusanField.value='IPA';
        formKelas.action=route;
        methodField.value='POST'
    }
</script>
@endsection