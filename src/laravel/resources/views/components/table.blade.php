<!-- resources/views/components/table-modern.blade.php -->
@props([
    'title'   => 'Data Table',
    'headers' => [],
    'addRoute' => null,  // route untuk tombol “Tambah”
])

<div class="row justify-content-center w-100">
    <div class="col-12">
        <div class="card shadow-sm mb-4 border-top border-3 border-primary">
            <div class="card-header no-after py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>
                <div class="d-flex align-items-center">
                    <input type="text" name="" id="" class="form-control mr-2" placeholder="search items">
                    @if($addRoute)
                        <a 
                        href="#" 
                        class="btn btn-primary d-block d-flex align-items-center"
                        x-on:click="event.preventDefault();"
                        onclick="Livewire.dispatch('{{ $addRoute }}')"
                        data-toggle="modal"
                        data-target="#create-modal">
                            <i class="fas fa-plus mr-2"></i> Tambah
                        </a>

                        @if (isset($create_form))
                            {{ $create_form }}
                        @endif

                        @if (isset($edit_form))
                            {{ $edit_form }}
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover shadow-sm">
                        <thead class="bg-light">
                            <tr>
                                @foreach($headers as $header)
                                    <th scope="col">{{ $header }}</th>
                                @endforeach
                                <th scope="col" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        {{ $slot }}
                    </table>

                    
                    
                </div>
                
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-left">
                    

                    @if(isset($paginator))
                        {{ $paginator }}
                    @endif
                </div>
            </div>
        </div>
    </div>
<script>
    window.addEventListener('modal:close', event=>{
        $(`#${event.detail.modal_id}`).modal('hide')
    })
    function destroy()
    {
        
    }
    
</script>
</div>



