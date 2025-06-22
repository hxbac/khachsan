@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">ĐẶT PHÒNG</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">TRANG CHỦ</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">PHÒNG ĐẶT</a>
                </div>
            </div>

            <div class="row">
                @foreach ($bookings as $booking)
                    <div class="col-md-4 px-4 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm">
                            <h5 class="fw-bold">{{ $booking->room_name }}</h5>
                            @if (!empty($booking->room_number))
                                <div><b>{{ $booking->room_number }}</b></div>
                            @endif
                            <p><b>Giá Phòng:</b> {{ $booking->price }} ₫</p>
                            <p>
                                <b>Ngày Vào:</b> {{ $booking->check_in }}<br>
                                <b>Ngày Trả:</b> {{ $booking->check_out }}
                            </p>
                            <p>
                                <b>Tổng:</b> {{ $booking->total_pay }} ₫<br>
                                <b>ID Đơn:</b> {{ $booking->order_id }}<br>
                                <b>Ngày Đặt:</b> {{ $booking->datentime }}
                            </p>
                            <p><span class="badge {{ $booking->status_bg }}">{{ $booking->booking_status }}</span></p>

                            {{-- Button --}}
                            @if ($booking->action ?? null === 'review')
                                <button type="button"
                                    onclick="review_room({{ $booking->booking_id }}, {{ $booking->room_id }})"
                                    data-bs-toggle="modal" data-bs-target="#reviewModal"
                                    class="btn btn-dark btn-sm shadow-none ms-2">Đánh Giá</button>
                            @elseif ($booking->action ?? null === 'cancel')
                                <button onclick="cancel_booking({{ $booking->booking_id }})" type="button"
                                    class="btn btn-danger btn-sm shadow-none">Huỷ Đặt Phòng</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Đánh Giá
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Đánh Giá</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Rất Tốt</option>
                                <option value="4">Tốt</option>
                                <option value="3">Tạm</option>
                                <option value="2">Kém</option>
                                <option value="1">Rất Tệ</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Nhận Xét</label>
                            <textarea type="password" name="review" rows="3" required class="form-control shadow-none"></textarea>
                        </div>

                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">

                        <div class="text-end">
                            <button type="submit" class="btn custom-bg text-white shadow-none">GỬI</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    @if (session('success') || session('error'))
        @php
            $type = session('success') ? 'success' : 'error';
            $msg = session($type);
            $bs_class = $type == 'success' ? 'alert-success' : 'alert-danger';
        @endphp

        <div class="alert {{ $bs_class }} alert-dismissible fade show custom-alert" role="alert">
            <strong class="me-3">{{ $msg }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <script>
        function cancel_booking(id) {
            if (confirm('Bạn có chắc chắn hủy đặt phòng không?')) {
                let xhr = new XMLHttpRequest();

                xhr.open("POST", "{{ route('booking.cancel') }}" + '?id=' + id, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', token);

                xhr.onload = function() {
                    if (this.responseText == 1) {
                        window.location.href = "{{ route('user.bookings') }}?cancel_status=true";
                    } else {
                        alert('error', 'Hủy không thành công!');
                    }
                }

                xhr.send();
            }
        }

        let review_form = document.getElementById('review-form');

        function review_room(bid, rid) {
            review_form.elements['booking_id'].value = bid;
            review_form.elements['room_id'].value = rid;
        }

        review_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let data = new FormData();

            data.append('review_form', '');
            data.append('rating', review_form.elements['rating'].value);
            data.append('review', review_form.elements['review'].value);
            data.append('booking_id', review_form.elements['booking_id'].value);
            data.append('room_id', review_form.elements['room_id'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('user.review') }}", true);

            xhr.onload = function() {

                if (this.responseText == 1) {
                    window.location.href = '{{ route('user.bookings') }}?review_status=true';
                } else {
                    var myModal = document.getElementById('reviewModal');
                    var modal = bootstrap.Modal.getInstance(myModal);
                    modal.hide();

                    alert('error', "Xếp hạng & Đánh giá Không thành công!");
                }
            }

            xhr.send(data);
        })
    </script>
@endsection
