<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tên Phòng</th>
            <th>Diện Tích</th>
            <th>Sức Chứa</th>
            <th>Giá</th>
            <th>Số Lượng</th>
            <th>Trạng Thái</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rooms as $index => $room)
            <tr class="align-middle">
                <td>{{ $index + 1 }}</td>
                <td>{{ $room->name }}</td>
                <td>{{ $room->area }} m²</td>
                <td>
                    <span class="badge rounded-pill bg-light text-dark">
                        Người Lớn: {{ $room->adult }}
                    </span><br>
                    <span class="badge rounded-pill bg-light text-dark">
                        Trẻ Em: {{ $room->children }}
                    </span>
                </td>
                <td>{{ number_format($room->price, 0, '.', ',') }} ₫</td>
                <td>{{ $room->quantity }}</td>
                <td>
                    @if ($room->status == 1)
                        <button onclick="toggle_status({{ $room->id }}, 0)"
                            class="btn btn-dark btn-sm shadow-none">Hoạt Động</button>
                    @else
                        <button onclick="toggle_status({{ $room->id }}, 1)"
                            class="btn btn-warning btn-sm shadow-none">Bảo Trì</button>
                    @endif
                </td>
                <td>
                    <button type="button" onclick="edit_details({{ $room->id }})"
                        class="btn btn-primary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#edit-room">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button type="button" onclick="room_images({{ $room->id }}, '{{ $room->name }}')"
                        class="btn btn-info shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#room-images">
                        <i class="bi bi-images"></i>
                    </button>
                    <button type="button" onclick="remove_room({{ $room->id }})"
                        class="btn btn-danger shadow-none btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
