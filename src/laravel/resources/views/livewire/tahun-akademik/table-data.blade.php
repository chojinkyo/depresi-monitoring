<tbody>
    @foreach ($thak as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->nama_tahun}}</td>
            <td>{{ $row->tanggal_mulai }}</td>
            <td>{{ $row->tanggal_selesai }}</td>
            <td>{{ 0 }}</td>
            <td class="d-flex justify-content-around">
                <a href="{{ route('hari-libur.edit', $row->id) }}" class="btn btn-warning btn-xs">
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
    @endforeach
</tbody>
