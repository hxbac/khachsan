@extends('layouts.app')

@section('content')
    @php
        $setting = \App\Models\Setting::where('sr_no', 1)->first();
    @endphp
    <!-- Carousel -->

    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                @foreach ($carousels as $row)
                    <div class="swiper-slide">
                        <img src="{{ asset('images/carousel/' . $row->image) }}" class="w-100 d-block" alt="carousel image">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- check availability form -->

    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-4">Tìm Phòng</h5>
                <form action="{{ route('rooms.index') }}">
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Ngày Nhận Phòng</label>
                            <input type="date" class="form-control shadow-none" name="checkin" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Ngày Trả Phòng</label>
                            <input type="date" class="form-control shadow-none" name="checkout" required>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Người Lớn</label>
                            <select class="form-select shadow-none" name="adult">
                                @for ($i = 1; $i <= $guests_res->max_adult; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Trẻ Em</label>
                            <select class="form-select shadow-none" name="children">
                                @for ($i = 1; $i <= $guests_res->max_children; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <input type="hidden" name="check_availability">
                        <div class="col-lg-2 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-white shadow-none custom-bg">Tìm Phòng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Our Rooms -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">PHÒNG</h2>

    <div class="container">
        <div class="row">

            @foreach ($rooms as $room)
                @php
                    $price = number_format($room->price, 0, '.', ',');
                    $thumb = $room->thumb_image
                        ? asset('images/rooms/' . $room->thumb_image)
                        : asset('images/rooms/' . 'thumbnail.jpg');

                    $features = $room->feature_names ? explode(',', $room->feature_names) : [];
                    $room_facilities = $room->facility_names ? explode(',', $room->facility_names) : [];

                    $avgRating = $room->avg_rating;
                    $login = auth()->check() ? 1 : 0;
                    $bookBtn = '';
                    if (!$setting->shutdown) {
                        $bookBtn = "<button onclick='checkLoginToBook($login, $room->id)' class='btn btn-sm text-white custom-bg shadow-none'>Đặt Ngay</button>";
                    }
                @endphp

                <div class="col-lg-4 col-md-6 my-3">
                    <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                        <img src="{{ $thumb }}" class="card-img-top" alt="{{ $room->name }}">
                        <div class="card-body">
                            <h5>{{ $room->name }}</h5>
                            <h6 class="mb-4">{{ $price }} ₫ mỗi đêm</h6>

                            <div class="features mb-4">
                                <h6 class="mb-1">Cơ sở</h6>
                                @foreach ($features as $feature)
                                    <span
                                        class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">{{ $feature }}</span>
                                @endforeach
                            </div>

                            <div class="facilities mb-4">
                                <h6 class="mb-1">Tiện nghi & Trang thiết bị</h6>
                                @foreach ($room_facilities as $facility)
                                    <span
                                        class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">{{ $facility }}</span>
                                @endforeach
                            </div>

                            <div class="guests mb-4">
                                <h6 class="mb-1">Khách Hàng</h6>
                                <span class="badge rounded-pill bg-light text-dark text-wrap">{{ $room->adult }} Người
                                    Lớn</span>
                                <span class="badge rounded-pill bg-light text-dark text-wrap">{{ $room->children }} Trẻ
                                    Em</span>
                            </div>

                            @if ($avgRating)
                                <div class="rating mb-4">
                                    <h6 class="mb-1">Rating</h6>
                                    <span class="badge rounded-pill bg-light">
                                        @for ($i = 0; $i < floor($avgRating); $i++)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @endfor
                                    </span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-evenly mb-2">
                                {!! $bookBtn !!}
                                <a href="{{ route('rooms.detail', ['id' => $room->id]) }}"
                                    class="btn btn-sm btn-outline-dark shadow-none">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-lg-12 text-center mt-5">
                <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem Thêm >>></a>
            </div>
        </div>
    </div>

    <!-- Our Facilities -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">CÁC TIỆN NGHI</h2>

    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            @foreach ($facilities as $facility)
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                    <img src="{{ asset('images/facilities/' . $facility->icon) }}" width="60px">
                    <h5 class="mt-3">{{ $facility->name }}</h5>
                </div>
            @endforeach

            <div class="col-lg-12 text-center mt-5">
                <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem thêm >>></a>
            </div>
        </div>
    </div>

    <!-- Testimonials -->

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ĐÁNH GIÁ TỪ KHÁCH HÀNG</h2>

    <div class="container mt-5">
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5">
                @if ($reviews->isEmpty())
                    <p>Chưa có đánh giá nào!</p>
                @else
                    @foreach ($reviews as $review)
                        @php
                            $stars = str_repeat("<i class='bi bi-star-fill text-warning'></i> ", $review->rating);
                        @endphp
                        <div class="swiper-slide bg-white p-4">
                            <div class="profile d-flex align-items-center mb-3">
                                <img src="{{ asset($review->profile) }}" class="rounded-circle" loading="lazy"
                                    width="30px">
                                <h6 class="m-0 ms-2">{{ $review->uname }}</h6>
                            </div>
                            <p>{{ $review->review }}</p>
                            <div class="rating">{!! $stars !!}</div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="col-lg-12 text-center mt-5">
            <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem thêm >>></a>
        </div>
    </div>

    <!-- Reach us -->

    @php
        $contact = App\Models\ContactDetail::where('sr_no', 1)->first();
    @endphp

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">LIÊN HỆ</h2>

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded" height="320px" src="{{ $contact->iframe }}" loading="lazy"></iframe>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Bạn cần hỗ trợ ? Hãy gọi ngay</h5>
                    <a href="tel:+{{ $contact->pn1 }}" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +{{ $contact->pn1 }}
                    </a>
                    <br>
                    @if (!empty($contact->pn2))
                        <a href="tel:+{{ $contact->pn2 }}" class="d-inline-block text-decoration-none text-dark">
                            <i class="bi bi-telephone-fill"></i> +{{ $contact->pn2 }}
                        </a>
                    @endif
                </div>

                <div class="bg-white p-4 rounded mb-4">
                    <h5>Theo dõi ngay</h5>
                    @if (!empty($contact->tw))
                        <a href="{{ $contact->tw }}" class="d-inline-block mb-3">
                            <span class="badge bg-light text-dark fs-6 p-2">
                                <i class="bi bi-twitter me-1"></i> Twitter
                            </span>
                        </a>
                        <br>
                    @endif

                    <a href="{{ $contact->fb }}" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </span>
                    </a>
                    <br>
                    <a href="{{ $contact->insta }}" class="d-inline-block">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram me-1"></i> Instagram
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
