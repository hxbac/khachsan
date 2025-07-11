@extends('layouts.app')

@section('content')
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">GIỚI THIỆU</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Mường Thanh Hotel cam kết mang đến cho khách hàng những trải nghiệm<br>
            tuyệt vời nhất trong một không gian sang trọng và đẳng cấp.
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Khách Sạn Mường Thanh</h3>
                <p>
                    Chào mừng đến với khách sạn Mường Thanh hãy đặt phòng để trải nghiệm
                    tận hưởng cùng với những dịch vụ của chúng tôi khi đặt phòng nhé.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="{{ asset('images/about/hy.jpg') }}" class="w-90">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3">100+ PHÒNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/customers.svg" width="70px">
                    <h4 class="mt-3">200+ KHÁCH HÀNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/rating.svg" width="70px">
                    <h4 class="mt-3">150+ ĐÁNH GIÁ</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3">200+ NHÂN VIÊN</h4>
                </div>
            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold h-font text-center">ĐỘI NGŨ</h3>

    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                @foreach ($teamDetails as $row)
                    <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                        <img src="{{ asset('images/about/' . $row->picture) }}" class="w-100">
                        <h5 class="mt-2">{{ $row->name }}</h5>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>
@endsection
