@extends('layouts.app')

@section('content')
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">LIÊN HỆ CHÚNG TÔI</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Liên hệ và góp ý với chúng tôi <br>
        </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="{{ $contact->iframe }}" loading="lazy"></iframe>

                    <h5>Địa Chỉ</h5>
                    <a href="{{ $contact->gmap }}" target="_blank"
                        class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i> {{ $contact->address }}
                    </a>

                    <h5 class="mt-4">Liên Hệ Đường Dây Nóng</h5>
                    <a href="tel:+{{ $contact->pn1 }}" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +{{ $contact->pn1 }}
                    </a>
                    <br>
                    @if (!empty($contact->pn2))
                        <a href="tel:+{{ $contact->pn2 }}" class="d-inline-block text-decoration-none text-dark">
                            <i class="bi bi-telephone-fill"></i> +{{ $contact->pn2 }}
                        </a>
                    @endif

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto:{{ $contact->email }}" class="d-inline-block text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i> {{ $contact->email }}
                    </a>

                    <h5 class="mt-4">Theo Dõi Ngay</h5>
                    @if (!empty($contact->tw))
                        <a href="{{ $contact->tw }}" class="d-inline-block text-dark fs-5 me-2">
                            <i class="bi bi-twitter me-1"></i>
                        </a>
                    @endif

                    <a href="{{ $contact->fb }}" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="{{ $contact->insta }}" class="d-inline-block text-dark fs-5">
                        <i class="bi bi-instagram me-1"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        @csrf
                        <h5>Gửi Tin Nhắn</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Họ và Tên</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Tiêu Đề</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Tin Nhắn</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custom-bg mt-3">GỬI</button>
                    </form>
                </div>
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
@endsection
