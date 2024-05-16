<div class="text-center">
    @php
        $today = date('Y-m-d');
        $start = date('Y-m-d', strtotime($data->created_at));
        $last = date('Y-m-d', strtotime($data->created_at . ' + 3 months'));
    @endphp
    @if ($today >= $start && $today <= $last)
        @if ($data->status == 0)
            <span class="badge badge-pill badge-secondary">Belum Digunakan</span>
        @else
            <span class="badge badge-pill badge-success">Sudah Digunakan</span>
        @endif
    @else
        <span class="badge badge-pill badge-dark">Kadaluarsa</span>
    @endif

</div>
