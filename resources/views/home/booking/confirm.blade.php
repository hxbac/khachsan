@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">XÁC NHẬN ĐẶT PHÒNG</h2>
                <div style="font-size: 14px;">
                    <a href="{{ route('home.index') }}" class="text-secondary text-decoration-none">TRANG CHỦ</a>
                    <span class="text-secondary"> > </span>
                    <a href="{{ route('rooms.index') }}" class="text-secondary text-decoration-none">PHÒNG</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">XÁC NHẬN</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <div class="card p-3 shadow-sm rounded">
                    <img src="{{ $roomThumb }}" class="img-fluid rounded mb-3">
                    <h4>{{ $room->name }}</h4>
                    <h5>{{ $price }} ₫</h5>
                </div>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="{{ route('booking.payNow') }}" method="POST" id="booking_form">
                            @csrf

                            <h6 class="mb-3">CHI TIẾT PHÒNG ĐẶT</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ Và Tên</label>
                                    <input name="name" type="text" value="{{ old('name', $user->name) }}"
                                        class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số Điện Thoại</label>
                                    <input name="phonenum" type="number" value="{{ old('phonenum', $user->phonenum) }}"
                                        class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Địa Chỉ</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1" required>{{ old('address', $user->address) }}</textarea>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Ngày Nhận Phòng</label>
                                    <input name="checkin" onchange="check_availability()" type="date"
                                        class="form-control shadow-none" required>
                                    <div style="padding-left:20px;">
                                        <span class='badge rounded-pill bg-light text-dark mt-2 text-wrap lh-base'>
                                            Nhận phòng từ 14:00h
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Ngày Trả Phòng</label>
                                    <input name="checkout" onchange="check_availability()" type="date"
                                        class="form-control shadow-none" required>
                                    <div style="padding-left:20px;">
                                        <span class='badge rounded-pill bg-light text-dark mt-2 text-wrap'>
                                            Trả phòng trước 12:00h
                                        </span>
                                    </div>
                                </div>
                                <div class="mb-4 col-md-4">
                                    <label class="form-label">Số Lượng</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="decrease()">-</button>
                                        <input type="number" name="quantity" id="quantity" oninput="check_availability()"
                                            class="form-control text-center" readonly min="1" max="5">
                                        <button name="plus_quantity" disabled class="btn btn-outline-secondary"
                                            type="button" onclick="increase()">+</button>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Đang Tải ...</span>
                                    </div>

                                    <h6 class="mb-3 text-danger" id="pay_info">Cung cấp ngày nhận phòng và trả phòng!</h6>

                                    <button id="book-now" name="pay_now"
                                        class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Đặt Ngay</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        let booking_form = document.getElementById('booking_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');
        let roomId = @json($room->id);

        function check_availability() {
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;
            let check_quantity = booking_form.elements['quantity'].value;
            console.log(roomId)

            booking_form.elements['pay_now'].setAttribute('disabled', true);

            if (checkin_val != '' && checkout_val != '') {
                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark', 'text-danger');
                info_loader.classList.remove('d-none');

                let data = new FormData();

                data.append('check_in', checkin_val);
                data.append('check_out', checkout_val);
                data.append('room_id', roomId);
                data.append('check_quantity', check_quantity);
                data.append('_token', token);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "{{ route('booking.checkAvailability') }}", true);

                xhr.onload = function() {
                    let data = JSON.parse(this.responseText);

                    if (data.status == 'check_in_out_equal') {
                        pay_info.innerText = "Bạn không thể trả phòng trong cùng một ngày!";
                        booking_form.elements['quantity'].value = 1;
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Ngày trả phòng sớm hơn ngày nhận phòng!";
                        booking_form.elements['quantity'].value = 1;
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Ngày nhận phòng sớm hơn ngày hôm nay!";
                        booking_form.elements['quantity'].value = 1;
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Đã hết phòng cho ngày đặt phòng này!";
                        booking_form.elements['quantity'].value = 1;
                    } else {
                        booking_form.elements['plus_quantity'].removeAttribute('disabled')
                        pay_info.innerHTML = "Số Phòng Còn Lại: <strong>" + data.out_of_room + "</strong>" +
                            "<br>Số Phòng Trống: <strong>" + data.c_rooms + "</strong>" + "<br>Số Ngày Đặt: " + data
                            .days + " Ngày " + "<strong>" + data.nights + "</strong>" + " Đêm" +
                            "<br>Số Tiền Phòng: <strong>" + data.payment + ' ₫</strong>' +
                            "<br>Tổng Số Tiền Phải Trả: <strong>" + data.total_payment + '₫</strong>';
                        pay_info.classList.replace('text-danger', 'text-dark');
                        data.out_of_room == 0 || data.c_rooms == 1 ? booking_form.elements['plus_quantity']
                            .setAttribute('disabled', 'disabled') : booking_form.elements['plus_quantity']
                            .removeAttribute('disabled');
                        booking_form.elements['pay_now'].removeAttribute('disabled');
                    }

                    pay_info.classList.remove('d-none');
                    info_loader.classList.add('d-none');
                }

                xhr.send(data);
            }

        }

        function increase() {
            let quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < quantityInput.max) {
                quantityInput.value = currentValue + 1;
                check_availability();
            }
        }

        function decrease() {
            let quantityInput = document.getElementById('quantity');
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > quantityInput.min) {
                quantityInput.value = currentValue - 1;
                // booking_form.elements['plus_quantity'].removeAttribute('disabled');
                check_availability();
            }
        }
        window.onload = function() {
            booking_form.elements['quantity'].value = 1;
        }
    </script>
@endsection
