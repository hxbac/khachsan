@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">{{ $room->name }}</h2>
                <div style="font-size: 14px;">
                    <a href="{{ route('home.index') }}" class="text-secondary text-decoration-none">TRANG CHỦ</a>
                    <span class="text-secondary"> > </span>
                    <a href="{{ route('rooms.index') }}" class="text-secondary text-decoration-none">PHÒNG</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if ($images->count() > 0)
                            @php $active = 'active'; @endphp
                            @foreach ($images as $img)
                                <div class="carousel-item {{ $active }}">
                                    <img src="{{ asset('images/rooms/' . $img->image) }}" class="d-block w-100 rounded">
                                </div>
                                @php $active = ''; @endphp
                            @endforeach
                        @else
                            <div class="carousel-item active">
                                <img src="{{ '/images/rooms/thumbnail.jpg' }}" class="d-block w-100">
                            </div>
                        @endif
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <h4>{{ number_format($room->price, 0, '.', ',') }} ₫ mỗi đêm</h4>

                        <div class="mb-3">
                            @for ($i = 0; $i < floor($avgRating); $i++)
                                <i class="bi bi-star-fill text-warning"></i>
                            @endfor
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-1">Cơ Sở</h6>
                            @foreach ($features as $f)
                                <span
                                    class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">{{ $f->name }}</span>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-1">Tiện Nghi</h6>
                            @foreach ($facilities as $f)
                                <span
                                    class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">{{ $f->name }}</span>
                            @endforeach
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-1">Khách Hàng</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                {{ $room->adult }} Người Lớn
                            </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">
                                {{ $room->children }} Trẻ Em
                            </span>
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-1">Rộng</h6>
                            <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                {{ $room->area }} m2.
                            </span>
                        </div>

                        @if (!config('settings.shutdown'))
                            @php
                                $login = auth()->check() ? 1 : 0;
                            @endphp
                            <button onclick='checkLoginToBook({{ $login }}, {{ $room->id }})'
                                class="btn w-100 text-white custom-bg shadow-none mb-1">
                                Đặt Ngay
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 px-4">
                <div class="mb-5">
                    <h5>Chi Tiết Về Phòng</h5>
                    <p>
                        {{ $room->description }}
                    </p>
                </div>

                <div>
                    <h5 class="mb-3">Đánh giá & Xếp hạng</h5>

                    @if ($reviews->isEmpty())
                        <p>Chưa có đánh giá nào!</p>
                    @else
                        @foreach ($reviews as $row)
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $row->profile }}" class="rounded-circle" loading="lazy"
                                        width="30px">
                                    <h6 class="m-0 ms-2">{{ $row->uname }}</h6>
                                </div>
                                <p class="mb-1">{{ $row->review }}</p>
                                <div>
                                    @for ($i = 0; $i < $row->rating; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
