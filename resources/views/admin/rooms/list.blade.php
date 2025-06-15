@php
    $icon = [
        'close' => asset('images/icon/close.svg'),
        'user' => asset('images/icon/user.svg'),
        'phone' => asset('images/icon/call1.svg'),
        'checkin' => asset('images/icon/checkin.svg'),
        'checkout' => asset('images/icon/checkout.svg'),
        'note' => asset('images/icon/note.svg'),
        'details' => asset('images/icon/details.svg'),
    ];
@endphp

@foreach ($rooms as $room)
    <div>
        <h4 class="mb-4 mt-4" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#room-{{ $room->id }}">
            <img src="{{ $icon['details'] }}" width="24" height="24">
            <span class="ms-2">{{ $room->name }}</span>
        </h4>
        <div class="row justify-content-center">
            @foreach ($room->room_numbers as $rn)
                @if ($rn->roomNumStatus == 1)
                    @php
                        $info = $rn->info ?? null;
                    @endphp
                    <div class="col-md-2 my-4">
                        <div class="d-flex justify-content-center align-items-center border border-dark rounded" style="width: 110px;height: 110px;cursor: pointer;background-color:#FD8A4F"
                            data-bs-toggle="modal" data-bs-target="#room-{{ $room->id }}-{{ $rn->idRoomNum }}">
                            {{ $rn->roomNum }}
                        </div>
                        <div><p class="mt-2">Đang sử dụng</p></div>
                    </div>

                    <div class="modal fade" id="room-{{ $room->id }}-{{ $rn->idRoomNum }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="add_room_no">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Thông tin khách hàng</h5>
                                        <div style="cursor: pointer;" data-bs-dismiss="modal">
                                            <img src="{{ $icon['close'] }}" width="30" height="30">
                                        </div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <p class="text-center">Họ và Tên: <strong class="ms-1">{{ $info->user_name ?? '...' }}</strong></p>
                                            <p class="text-center">SĐT: <strong class="ms-1">{{ $info->phonenum ?? '...' }}</strong></p>
                                            <p class="text-center">Nhận phòng: <strong class="ms-1">{{ isset($info->check_in) ? date('d/m/Y', strtotime($info->check_in)) : '' }}</strong></p>
                                            <p class="text-center">Trả phòng: <strong class="ms-1">{{ isset($info->check_out) ? date('d/m/Y', strtotime($info->check_out)) : '' }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif ($rn->roomNum == '')
                    {{-- Bảo trì --}}
                    <div class="col-md-2 my-4">
                        <div class="d-flex justify-content-center align-items-center border border-dark rounded" style="width: 110px;height: 110px;background-color:#A9A9A9" data-bs-toggle="modal" data-bs-target="#add-room-{{ $room->id }}-{{ $rn->idRoomNum }}">
                            Bảo Trì
                        </div>
                        <div><p class="mt-2">Phòng Đang Sửa</p></div>
                    </div>

                    <div class="modal fade" id="add-room-{{ $room->id }}-{{ $rn->idRoomNum }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="add_room_no">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Nhập Số Phòng</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Số Phòng</label>
                                            <input type="text" name="room_{{ $room->id }}{{ $rn->idRoomNum }}" class="form-control shadow-none">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="reset" class="btn text-secondary" data-bs-dismiss="modal">Huỷ</button>
                                        <button type="button" onclick="getValueAndSubmit({{ $room->id }},{{ $rn->idRoomNum }})" class="btn custom-bg text-white" data-bs-dismiss="modal">Xác Nhận</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Phòng trống --}}
                    <div class="col-md-2 my-4">
                        <div class="position-relative d-flex justify-content-center align-items-center border border-dark rounded" style="width: 110px;height: 110px;background-color:#33BEBE">
                            <div class="position-absolute top-0 end-0 delete-room-id" onclick="del_room_number({{ $rn->idRoomNum }})">
                                <img src="{{ $icon['close'] }}" width="22" height="22">
                            </div>
                            {{ $rn->roomNum }}
                        </div>
                        <div class="mt-2"><p>Phòng Trống</p></div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Modal đặt phòng --}}
        <div class="modal fade bd-example-modal-lg" id="room-{{ $room->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="add_room_no">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Danh sách đặt phòng</h5>
                            <div style="cursor: pointer;" data-bs-dismiss="modal">
                                <img src="{{ $icon['close'] }}" width="30" height="30">
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="row col-md-12 d-flex justify-content-center">
                                @forelse ($room->bookings as $booking)
                                    @php
                                        $check_in = date('d-m-Y', strtotime($booking->check_in));
                                        $check_out = date('d-m-Y', strtotime($booking->check_out));
                                        $badge = $booking->booking_status == 'Đã Xác Nhận Đặt Phòng' ? '#FD8A4F' : '#33BEBE';
                                        $text = $booking->booking_status == 'Đã Xác Nhận Đặt Phòng' ? 'Đang sử dụng' : 'Phòng đã đặt';
                                    @endphp
                                    <div class="col-md-3 border">
                                        <p class="mt-1 pt-2 d-flex align-items-center" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;width: 165px;">
                                            <img src="{{ $icon['user'] }}" width="20" height="20">
                                            <span class="ms-2">{{ $booking->user_name }}</span>
                                        </p>
                                        <p class="d-flex align-items-center">
                                            <img src="{{ $icon['phone'] }}" width="20" height="20">
                                            <span class="ms-2">{{ $booking->phonenum }}</span>
                                        </p>
                                        <p class="d-flex align-items-center">
                                            <img src="{{ $icon['checkin'] }}" width="20" height="20">
                                            <span class="ms-2">{{ $check_in }}</span>
                                        </p>
                                        <p class="d-flex align-items-center">
                                            <img src="{{ $icon['checkout'] }}" width="20" height="20">
                                            <span class="ms-2">{{ $check_out }}</span>
                                        </p>
                                        <p class="d-flex justify-content-center">
                                            <span class="badge rounded-pill text-dark text-wrap" style="background-color: {{ $badge }}">{{ $text }}</span>
                                        </p>
                                    </div>
                                @empty
                                    <p class="mt-3 text-center">Không có lượt đặt phòng nào ...</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
