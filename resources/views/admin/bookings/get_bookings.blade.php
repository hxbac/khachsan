<tr>
    <td>{{ $i }}</td>
    <td>
        <span class='badge bg-primary'>ID Đặt Phòng: {{ $data->order_id }}</span><br>
        <b>Tên:</b> {{ $data->user_name }}<br>
        <b>Điện Thoại:</b> {{ $data->phonenum }}
    </td>
    <td>
        <b>Phòng:</b> {{ $data->room_name }}<br>
        <b>Giá:</b> {{ $price }} ₫<br>
        <b>Tổng:</b> {{ $price_total }} ₫
    </td>
    <td>
        <b>Thời Gian Vào:</b> {{ $checkin }}<br>
        <b>Thời gian Trả:</b> {{ $checkout }}<br>
        <b>Thời Gian:</b> {{ $count_days }} đêm<br>
        <b>Ngày Đặt:</b> {{ $time }} | {{ $date }}
    </td>
    <td>
        <button type='button' class='mb-2 btn btn-outline-primary btn-sm fw-bold shadow-none' data-bs-toggle='modal'
            data-bs-target='#add-room-{{ $i }}'>
            <i class='bi bi-check2-square'></i> Xác Nhận Đặt Phòng
        </button><br>
        <button type='button' onclick='huy_booking({{ $data->booking_id }})'
            class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
            <i class='bi bi-trash'></i> Huỷ Đặt Phòng
        </button>
    </td>
</tr>

<div class='modal fade' id='add-room-{{ $i }}' data-bs-backdrop='static' data-bs-keyboard='true'
    tabindex='-1'>
    <div class='modal-dialog'>
        <form>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title'>Chọn Số Phòng</h5>
                </div>
                <div class='modal-body'>
                    <div class='mb-3'>
                        <label class='form-label fw-bold'>Số Phòng</label>
                        <select name='room_option{{ $i }}' class='form-select'>
                            <option value='' selected>Chọn Phòng</option>
                            {!! $room_option !!}
                        </select>
                    </div>
                    <span class='badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base'>
                        Lưu ý: Chỉ chọn phòng khi người dùng đã đến!
                    </span>
                </div>
                <div class='modal-footer'>
                    <button type='reset' class='btn text-secondary shadow-none' data-bs-dismiss='modal'>Huỷ</button>
                    <button type='button' onclick='getValuesAndSubmit({{ $data->booking_id }},{{ $i }})'
                        class='btn custom-bg text-white shadow-none' data-bs-dismiss='modal'>Xác Nhận</button>
                </div>
            </div>
        </form>
    </div>
</div>
