@php
    $path = asset('images/rooms');
@endphp

@foreach($images as $row)
<tr class='align-middle'>
    <td>
        <img src="{{ $path . '/' . $row->image }}" class='img-fluid'>
    </td>
    <td>
        @if($row->thumb == 1)
            <i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>
        @else
            <button onclick='thumb_image({{ $row->sr_no }}, {{ $row->room_id }})' class='btn btn-secondary shadow-none'>
                <i class='bi bi-check-lg'></i>
            </button>
        @endif
    </td>
    <td>
        <button onclick='rem_image({{ $row->sr_no }}, {{ $row->room_id }})' class='btn btn-danger shadow-none'>
            <i class='bi bi-trash'></i>
        </button>
    </td>
</tr>
@endforeach
