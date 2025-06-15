@foreach ($data as $index => $item)
    @php
        $booking = $item['booking'];
    @endphp
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            <span class="badge bg-primary">ID Đặt Phòng: {{ $booking->order_id }}</span><br>
            <b>Tên:</b> {{ $booking->user_name }}<br>
            <b>Điện Thoại:</b> {{ $booking->phonenum }}
        </td>
        <td>
            <b>Loại Phòng:</b> {{ $booking->room_name }}<br>
            <b>Số Phòng:</b> {{ $item['room_num'] }}<br>
            <b>Giá:</b> {{ $item['price'] }} ₫<br>
            <b>Tổng:</b> {{ $item['total_pay'] }} ₫
        </td>
        <td>
            <b>Thời Gian Vào:</b> {{ $item['checkin'] }}<br>
            <b>Thời Gian Trả:</b> {{ $item['checkout'] }}<br>
            <b>Thời Gian:</b> {{ $item['count_days'] }} đêm<br>
            <b>Thời Gian Còn Lại:</b> {!! $item['han_phong'] !!}
        </td>
        <td>
            <button type="button"
                onclick="payment_booking({{ $booking->booking_id }}, {{ $booking->price }}, {{ $item['count_days'] }}, {{ $booking->room_no }})"
                class="mb-2 btn btn-outline-success btn-sm fw-bold shadow-none">
                <i class="bi bi-check2-square"></i> Xác Nhận Thanh Toán
            </button>
            <br>
            <button type="button" class="btn btn-outline-dark btn-sm fw-bold shadow-none" data-bs-toggle="modal"
                data-bs-target="#change-room-{{ $index }}">
                <i class="bi bi-pencil-square"></i> Thay Đổi Phòng
            </button>
            <br>
            <button type="button" onclick="cancel_booking({{ $booking->booking_id }}, {{ $booking->room_no }})"
                class="mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none">
                <i class="bi bi-trash"></i> Huỷ Đặt Phòng
            </button>
        </td>
    </tr>

    <!-- Modal -->
    <div class="modal fade" id="change-room-{{ $index }}" data-bs-backdrop="static" data-bs-keyboard="true"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thay Đổi Phòng</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số Phòng</label>
                            <select name="room_option{{ $index }}" class="form-select"
                                aria-label="Default select example">
                                <option value="" selected>Chọn Phòng</option>
                                @foreach ($item['available_rooms'] as $room)
                                    <option value="{{ $room->id }}">{{ $room->room_num }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
                            <!-- Ghi chú nếu cần -->
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none"
                            data-bs-dismiss="modal">Huỷ</button>
                        <button type="button"
                            onclick="getValuesAndSubmit({{ $booking->booking_id }}, {{ $booking->room_no }}, {{ $index }})"
                            class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Xác Nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
