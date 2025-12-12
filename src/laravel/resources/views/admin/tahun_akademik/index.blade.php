@extends('layouts.admin')

@section('title', 'Index Tahun Akademik')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-10">
            <h1 class="m-0">Tahun Akademik</h1>
        </div>
    </div>
@endsection


@section('content')

<div class="row justify-content-center"  x-data="{selected_id : `{{ old('id') }}`}">
    <div class="col-7">
        <x-table
            title="Daftar Tahun Akademik"
            :headers="['No', 'Periode', 'Tanggal Mulai', 'Tanggal Selesai', 'Status']"
            addRoute="kelas.create"
        >
            @forelse ($academicYears as $i => $row)
                <tr :class="{'table-active' : (selected_id=={{ $row->id }}) }">
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
                        onclick="event.preventDefault();editForm('{{ $row->id }}', `{{ route('admin.tahun-akademik.update', ['tahun_akademik'=>$row->id]) }}`)"
                        x-on:click="selected_id={{ $row->id }};">
                            <i class="fas fa-edit"></i>
                        </a>

                        <a 
                        href="#"
                        role="button"
                        class="btn btn-xs btn-danger"
                        onclick="
                        event.preventDefault();
                        setDeleteForm(`{{ route('admin.tahun-akademik.destroy', ['tahun_akademik'=>$row->id]) }}`);
                        Livewire.dispatch('swal:confirm', {
                            title : 'Konfirmasi hapus data',
                            text : 'Apakah anda yakin ingin menghapus tahun akademik ini?',
                            icon : 'warning',
                            method : submitDeleteForm,
                            
                        })">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
            @endforelse
            <x-slot name="paginator">
                {{ $academicYears->links() }}
            </x-slot>
        </x-table>
    </div>
    <div class="col-3">
        <div class="card shadow-sm border-top border-top border-4 border-info">
            <div class="card-header no-after py-3 d-flex justify-content-between align-items-center">
                <h2 class="h5 font-weight-bold text-info">Form Tahun Akademik</h2>
                <a 
                href="#"
                role="button"
                onclick="event.preventDefault();resetForm(`{{ route('admin.tahun-akademik.store') }}`)">
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
                action="{{ route('admin.tahun-akademik.store') }}" 
                @else  
                action="{{ route('admin.tahun-akademik.update', ['tahun_akademik'=>old('id')]) }}"
                @endif
                method="POST"
                class="w-100" id="form-tahun-akademik">
                @csrf
                <input type="hidden" name="id" id="id_field" x-model="selected_id">
                <input type="hidden" name="_method" id="_method_field" value="{{ old('id')==null ? 'POST' : 'PUT' }}">
                <div class="form-group">
                    <label for="tahun_mulai_field">Periode Tahun</label>
                    <div class="input-group">
                        
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" id="tahun_mulai_field" placeholder="Awal" name="tahun_mulai" value="{{ old('tahun_mulai') }}">
                        <input type="text" class="form-control" id="tahun_akhir_field" placeholder="Akhir" name="tahun_akhir" value="{{ old('tahun_akhir') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="status_field" name="status" value="1" @checked(old('status'))>
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
                            <input type="date" class="form-control" id="tanggal_mulai_field" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                            <x-form-error-text :field="'tanggal_mulai'" />
                        </div>
                        
                        <div class="form-group flex-fill px-0">
                            <label for="tanggal_selesai_field" class="font-weight-normal">Selesai</label>
                            <input type="date" class="form-control" id="tanggal_selesai_field" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                            <x-form-error-text :field="'tanggal_selesai'" />
                        </div>
                    </div>
                </div>

                <div class="form-group form-check">
                    <input type="hidden" name="current" value="0">
                    <input type="checkbox"  id="current_field" class="form-check-input" name="current" value="1" @checked(old('current'))>
                    <label for="" class="font-weight-normal">Set as current</label>
                    <x-form-error-text :field="'current'" />

                </div>
                <button type="submit" class="btn btn-lg btn-info w-100">Simpan</button>
            </form>
            </div>
            <div class="card-footer">

            </div>
        </div>
    </div>
</div>
<script>
    const deleteForm=document.getElementById('form-delete');
    const formTahunAkademik=document.getElementById('form-tahun-akademik');
    const methodField=document.getElementById('_method_field');
    const fields=['tahun_mulai', 'tahun_akhir', 'status', 'current', 'tanggal_mulai', 'tanggal_selesai'];
    const fieldEls=Object.fromEntries(fields.map(key=>[key, document.getElementById(`${key}_field`)]))

    const academicYears=@json($academicYears->items())

    

    function editForm(id, route) {
        const academicYear=academicYears.filter(c => c.id==id)
        const form=academicYear[0]

        formTahunAkademik.action=route;
        methodField.value='PUT';
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=form[key]
        })
        fieldEls['current'].value="1";
        fieldEls['status'].value="1";
        fieldEls['current'].checked=form['current']
        fieldEls['status'].checked=form['status']
    }
    const submitDeleteForm=()=>deleteForm.submit()
    const setDeleteForm=route=>deleteForm.action=route;

    function resetForm(route)
    {
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=null
        })
        fieldEls['current'].checked=false
        fieldEls['status'].checked=false
        formTahunAkademik.action=route;
        methodField.value='POST'
    }
    
</script>
@endsection