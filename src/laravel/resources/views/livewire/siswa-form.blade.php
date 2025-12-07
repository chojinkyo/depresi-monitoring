<div class="modal fade" id="create-modal" tabindex="-1" aria-labelledby="create-modal-label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="h2" id="create-modal-label">Form Tambah Siswa</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-between w-100">
                    <form action="" method="POST" class="col-8"  wire:submit="save()">
                        @csrf
                        <div class="form-group">
                            <label for="">Nama Lengkap</label>
                            <input type="text" name="" id="" class="form-control">
                            
                            
                            <x-form-error-text :field="'form.nama_lengkap'" />
                        </div>
                        <div class="form-group">
                            <label for="">NISN</label>
                            <input type="text" name="" id="" class="form-control">
                            <x-form-error-text :field="'form.nisn'" />

                        </div>
                        <div class="form-group">
                            <label for="">Tahun</label>
                            <select name="" id="" class="form-control">
                                <option value="">2025/2026</option>
                            </select>
                            <x-form-error-text :field="'form.id_thak_masuk'" />

                        </div>
                        
                        <div class="form-group">
                            <label for="">Kelas</label>
                            <select name="" id="" class="form-control">
                                <option value="">10-B</option>
                            </select>
                            <x-form-error-text :field="'form.id_kelas'" />

                        </div>
                        <div class="form-group my-0">
                            <label for="">Lahir</label>
                            <div class="d-flex flex-wrap justify-content-between " style="gap: 1rem;font-size: 16px;">
                                <div class="form-group flex-fill px-0">
                                    <label for="" class="font-weight-normal">Tempat</label>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="form-group flex-fill px-0">
                                    <label for="" class="font-weight-normal">Tanggal</label>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                            </div>
                            <x-form-error-text :field="'form.tanggal_lahir'" />

                        </div>
                        <div class="form-group">
                            <label for="">Gender</label>
                            <div class="input-group d-flex" style="gap: 1rem;">
                            
                                <div class="form-check">
                                    <input type="radio" name="gender" id="gen_l" class="form-check-input">
                                    <label for="gen_l" class="form-check-label">Laki-laki</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="gender" id="gen_p" class="form-check-input">
                                    <label for="gen_p" class="form-check-label">Perempuan</label>
                                </div>
                                <x-form-error-text :field="'form.gender'" />
                                
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="">Alamat</label>
                            <textarea name="" id="" cols="30" rows="4" class="form-control"></textarea>
                            <x-form-error-text :field="'form.alamat'" />
                        </div>
                        <button type="submit" class="btn btn-lg btn-info w-100">Submit</button>
                    </form>
                    <div class="col-4 px-4">
                        <div class="form-group" x-data="{
                                                img_preview:null, 
                                                default_pic : `{{ asset('img/default/no-profile.png') }}`, 
                                                reset_preview() {
                                                    this.img_preview=null;
                                                    $refs.img_upload.value=null;
                                                }
                    }">
                            <label for="">Foto Profil</label>
                            <div class="w-50 mb-3 position-relative">
                                <img x-bind:src="img_preview || default_pic" alt="" class="w-100 d-block" style="aspect-ratio: 1/1;object-fit: cover;">
                                <button 
                                type="button" 
                                class="btn btn-danger position-absolute p-0 w-25 rounded-pill" 
                                style="top: 0px; right: 0px;aspect-ratio: 1/1;transform: translate(50%, -50%)"
                                x-show="img_preview!==null"
                                x-on:click="reset_preview">
                                    X
                                </button>
                            </div>
                            <input type="file" x-ref="img_upload" name="" id="" class="form-control-file form-control-file-sm d-block" x-on:change="img_preview=URL.createObjectURL(event.target.files[0])">
                            <x-form-error-text :field="'form.avatar'" />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>