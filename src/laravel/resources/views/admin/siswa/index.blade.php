

@extends('adminlte::page')

@section('title', 'Index Siswa')
@section('content_header')
    <div class="row justify-content-center">
        <div class="col-11">
            <h1 class="m-0">Siswa</h1>
        </div>
    </div>
@endsection
@
@section('content')
<div class="row justify-content-center" x-data="{selected_id : `{{ old('id') }}`, id_user : `{{ old('id_user') }}`}" x-ref="mainContainer">
    <div class="col-6">
        <x-table
            title="Daftar Siswa"
            :headers="['No', 'NISN', 'Nama Lengkap', 'Gender', 'Kelas', 'Status']"
            addRoute="siswa:create"
        >
            @forelse ($students as $i => $row)
                <tr :class="{'table-active' : selected_id==`{{ $row->id }}`}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->nisn}}</td>
                    <td>{{ $row->nama_lengkap }}</td>
                    <td>{{ $row->gender ? "L" : "P" }}</td>
                    <td>{{ $row->getKelasAktif()?->nama }}</td>
                    <td>

                        @if(!$row->status)
                            <div class="badge badge-sm bg-danger rounded-pill">Non-Aktif</div>
                        @else
                            <div class="badge badge-sm bg-success rounded-pill">Aktif</div>
                        @endif
                    </td>
                    <td >
                        <a 
                        href="#" 
                        role="button"
                        class="btn btn-warning btn-xs"
                        x-on:click="event.preventDefault();selected_id=`{{ $row->id }}`"
                        onclick="editForm(`{{ $row->id }}`, `{{ route('admin.siswa.update', ['siswa'=>$row->id]) }}`, `{{ $row->user->id }}`)"
                        >
                            <i class="fas fa-edit"></i>
                        </a>
                        <a 
                        href="#" 
                        role="button"
                        class="btn btn-danger btn-xs"
                        x-on:click="event.preventDefault();setDeleteForm(`{{ route('admin.siswa.destroy', ['siswa'=>$row->id]) }}`)"
                        onclick="Livewire.dispatch('swal:confirm', {
                            title : 'Konfirmasi hapus data',
                            text : 'Apakah anda yakin ingin menghapus siswa ini?',
                            icon : 'warning',
                            method : submitDeleteForm,
                            params : {id : {{ $row->id }}}
                        })"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
            @endforelse
        </x-table>

        <x-slot name="paginator">
            {{ $students->links() }}
        </x-slot>
    </div>
    <form 
        method="post" 
        id="form-delete" 
        class="d-none"
    >
        @csrf
        @method('DELETE')
    </form>
    <form 
        @if(old('id')==null)
        action="{{ route('admin.siswa.store') }}"
        @else
        action="{{ route('admin.siswa.update', ['siswa'=>old('id')]) }}"
        @endif
        method="POST" 
        id="form-siswa"
        class="d-flex justify-between col-5 px-0" 
        enctype="multipart/form-data"
    >
    @csrf
    <input type="hidden" name="id" id="id_field" x-model="selected_id">
    <input type="hidden" name="avatar_url" id="avatar_url_field" value="{{ old('avatar_url') }}">
    <input type="hidden" name="id_user" id="id_user_field" value="{{ old('id_user') }}">
    <input type="hidden" name="_method" id="_method_field" value="{{ old('id') ? 'PUT' : 'POST' }}">
    <div class="col-7 pl-0">
        <div class="card shadow-sm border-top border-top border-4 border-info">
            <div class="card-header no-after py-3 d-flex justify-content-between align-items-center">
                <h2 class="h5 font-weight-bold text-info">Form Siswa</h2>
                <a 
                href="#"
                role="button"
                onclick="event.preventDefault();resetForm(`{{ route('admin.siswa.store') }}`)">
                    Clear
                </a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap_field" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                    <x-form-error-text :field="'nama_lengkap'" />
                </div>
                <div class="form-group">
                    <label for="">NISN</label>
                    <input type="text" id="nisn_field" class="form-control" name="nisn" value="{{ old('nisn') }}">
                    <x-form-error-text :field="'nisn'" />
                </div>
                
                <div class="form-row">
                    <div class="form-group col">
                        <label for="id_thak_masuk_field">Tahun Masuk</label>
                        <select id="id_thak_masuk_field" class="form-control" name="id_thak_masuk" value="{{ old('id_thak_masuk') }}">
                            @forelse ($academicYears as $t)
                                <option value="{{ $t->id }}">{{ $t->nama_tahun }}</option>
                            @empty
                            @endforelse
                        </select>
                        <x-form-error-text :field="'id_thak_masuk'" />
                    </div>
                    
                    <div class="form-group col">
                        <label for="id_kelas_field">Kelas</label>
                        <select id="id_kelas_field" class="form-control" name="id_kelas" value="{{ old('id_kelas') }}">
                            @forelse ($classes as $t)
                                <option value="{{ $t->id }}">{{ $t->nama }}</option>
                            @empty
                            @endforelse
                        </select>
                        <x-form-error-text :field="'id_kelas'" />

                    </div>
                </div>
                <div class="form-group my-0">
                    <label for="">Lahir</label>
                    <div class="form-row" style="font-size: 16px;">
                        <div class="form-group col">
                            <label for="tempat_lahir_field" class="font-weight-normal">Tempat</label>
                            <input type="text" id="tempat_lahir_field" class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}">
                        </div>
                        <div class="form-group col">
                            <label for="tanggal_lahir_field" class="font-weight-normal">Tanggal</label>
                            <input type="date" id="tanggal_lahir_field" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
                        </div>
                    </div>
                    <x-form-error-text :field="'tempat_lahir'" />
                    <x-form-error-text :field="'tanggal_lahir'" />
                </div>
                <div class="form-group">
                    <div class="form-row" >
                        <div class="form-group col">
                            <label for="status_field">Status Siswa</label>
                            <select id="riwayat_status_field" class="form-control" name="status" value="{{ old('status') }}">
                                <option value="NW">Baru</option>
                                <option value="MM">Pindahan</option>
                            </select>
                            <x-form-error-text :field="'status'" />
                        </div>
                        <div class="form-group d-flex flex-wrap col">
                            <label>Gender</label>
                            <div class="input-group d-flex align-items-end" style="gap: 1rem;">
                                <div class="form-check">
                                    <input type="radio" name="gender" id="gen_l" class="form-check-input" value="1" name="gender" @checked(old('gender')==1)>
                                    <label for="gen_l" class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="gender" id="gen_p" class="form-check-input" value="0" name="gender" @checked(old('gender')==0)>
                                    <label for="gen_p" class="form-check-label">Perempuan</label>
                                </div>
                                <x-form-error-text :field="'gender'" />
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                
                <div class="form-group">
                    <label for="alamat_field">Alamat</label>
                    <textarea id="alamat_field" cols="30" rows="4" class="form-control" name="alamat">{{ old('alamat') }}</textarea>
                    <x-form-error-text :field="'alamat'" />
                </div>
                <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                    
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <div class="col-5">
        <div class="card shadow-sm border-top border-top border-4 border-info">
            <div class="card-header">
                <h2 class="h6 font-weight-bold text-info ">Info Akun</h2>

            </div>
            <div class="card-body">
                <div 
                    class="form-group" 
                    x-data="{

                        img_url : `{{ old('avatar_url') }}`, 
                        img_preview : null,
                        img_initial : null,
                        is_img_load : false,
                        reset_preview() 
                        {
                            this.img_preview=null;
                            $refs.img_upload.value=null;
                        },
                        async get_img() 
                        {
                            this.reset_preview();
                            
                            
                            const res1=await fetch(`/files/images/users/default`)
                            const blob1=await res1.blob();
                            this.img_initial=URL.createObjectURL(blob1);
                            
                            if(this.img_url)
                            {
                                const url=`id/${id_user}/${this.img_url==''?'p':this.img_url}` 
                                const res2=await fetch(`/files/images/users/${url}`)
                                const blob2=await res2.blob();
                                this.img_preview=URL.createObjectURL(blob2);

                            }


                        }
                    }"
                    x-init="get_img();$watch('img_url', _=>get_img()) "
                    x-ref="imgContainer"
                >
                    <label for="">Foto Profil</label>
                    <div class="w-50 mb-3 position-relative">
                        <img x-bind:src="img_preview || img_initial" alt="" class="w-100 d-block" style="aspect-ratio: 1/1;object-fit: cover;">
                        <button 
                        type="button" 
                        class="btn btn-danger position-absolute p-0 w-25 rounded-pill" 
                        style="top: 0px; right: 0px;aspect-ratio: 1/1;transform: translate(50%, -50%)"
                        x-show="img_preview!=null"
                        x-on:click="reset_preview">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <input 
                    type="file" 
                    x-ref="img_upload"  
                    id="avatar_field" 
                    class="form-control-file form-control-file-sm d-block" 
                    x-on:change="img_preview=URL.createObjectURL(event.target.files[0])"
                    name="avatar">
                    <x-form-error-text :field="'avatar'" />

                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="text" id="email_field" class="form-control" name="email" value="{{ old('email') }}">
                    <x-form-error-text :field="'email'" />
                </div>
            </div>
        </div>
    </div>
    </form>
    
</div>

<script>
    const deleteForm=document.getElementById('form-delete');
    const formSiswa=document.getElementById('form-siswa');
    const methodField=document.getElementById('_method_field');
    const fields=['nama_lengkap', 'nisn', 'id_thak_masuk', 'id_kelas', 'tempat_lahir', 'tanggal_lahir', 'riwayat_status', 'email', 'alamat', 'avatar_url', 'id_user'];
    const fieldEls=Object.fromEntries(fields.map(key=>[key, document.getElementById(`${key}_field`)]))
    const genderFields=document.querySelectorAll('input[name="gender"]')

    const students=@json($students->items())

    

    function editForm(id, route, userId) {
        const data=Alpine.$data(document.querySelector('[x-ref="mainContainer"]'))
        const student=students.filter(c => c.id==id)
        const form=student[0]
        data.id_user=userId;
        

        formSiswa.action=route;
        methodField.value='PUT';
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=form[key]
        })
        genderFields.forEach(gf=>{
            gf.checked=gf.value==form['gender']
        })
        updateImgURL(form['avatar_url'])
        
    }
    const submitDeleteForm=()=>deleteForm.submit()
    const setDeleteForm=route=>deleteForm.action=route;

    function resetForm(route)
    {
        const data=Alpine.$data(document.querySelector('[x-ref="mainContainer"]'))
        Object.keys(fieldEls).forEach(key=>{
            fieldEls[key].value=null
        })
        genderFields.forEach(gf=>{
            gf.checked=false
        })
        formSiswa.action=route;
        methodField.value='POST';
        data.selected_id=null;
    }
    function updateImgURL(url)
    {
        const data=Alpine.$data(document.querySelector('[x-ref="imgContainer"]'))
        data.img_url=url;
        
    }
    
</script>
@endsection
