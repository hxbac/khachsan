@extends('layouts.app')

@section('content')
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CÁC TIỆN NGHI</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Đến với chúng tôi, quý khách sẽ được trải nghiệm tất
            cả các tiện nghi của chúng tôi,<br> Bao gồm các tiện nghi như tivi, wifi, tủ lạnh, máy gặt,
            điều hoà...
        </p>
    </div>

    <div class="container">
        <div class="row">
            @foreach ($facilities as $row)
                <div class="col-lg-4 col-md-6 mb-5 px-4">
                    <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ asset('images/facilities/' . $row->icon) }}" width="40px">
                            <h5 class="m-0 ms-3">{{ $row->name }}</h5>
                        </div>
                        <p>{{ $row->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
