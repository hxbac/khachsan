@extends('layouts.app')

@section('content')
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">PHÒNG</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">Tìm Kiếm</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                            <!-- Kiểm tra tính khả dụng -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>Kiểm Tra Ngày</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()"
                                        class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label class="form-label">Ngày Nhận Phòng</label>
                                <input type="date" class="form-control shadow-none mb-3" value="{{ $checkin_default }}"
                                    id="checkin" onchange="chk_avail_filter()">
                                <label class="form-label">Ngày Trả Phòng</label>
                                <input type="date" class="form-control shadow-none" value="{{ $checkout_default }}"
                                    id="checkout" onchange="chk_avail_filter()">
                            </div>

                            <!-- Cơ sở -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>TIỆN NGHI</span>
                                    <button id="facilities_btn" onclick="facilities_clear()"
                                        class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                @foreach ($facilities as $facility)
                                    <div class="mb-2">
                                        <input type="checkbox" onclick="fetch_rooms()" name="facilities"
                                            value="{{ $facility->id }}" class="form-check-input shadow-none me-1"
                                            id="facility_{{ $facility->id }}">
                                        <label class="form-check-label" for="facility_{{ $facility->id }}">
                                            {{ $facility->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Khách -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span>KHÁCH</span>
                                    <button id="guests_btn" onclick="guests_clear()"
                                        class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">Người Lớn</label>
                                        <input type="number" min="1" id="adults"
                                            value="{{ old('adult', $adult_default ?? '') }}" oninput="guests_filter()"
                                            class="form-control shadow-none">
                                    </div>
                                    <div>
                                        <label class="form-label">Trẻ Em</label>
                                        <input type="number" min="1" id="children"
                                            value="{{ old('children', $children_default ?? '') }}" oninput="guests_filter()"
                                            class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4" id="rooms-data">
            </div>

        </div>
    </div>


    <script>
        let rooms_data = document.getElementById('rooms-data');

        let checkin = document.getElementById('checkin');
        let checkout = document.getElementById('checkout');
        let chk_avail_btn = document.getElementById('chk_avail_btn');

        let adults = document.getElementById('adults');
        let children = document.getElementById('children');
        let guests_btn = document.getElementById('guests_btn');

        let facilities_btn = document.getElementById('facilities_btn');

        function fetch_rooms() {
            //Tạo chuỗi JSON
            let chk_avail = JSON.stringify({
                checkin: checkin.value,
                checkout: checkout.value
            });

            let guests = JSON.stringify({
                adults: adults.value,
                children: children.value
            });

            let facility_list = {
                "facilities": []
            };

            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            if (get_facilities.length > 0) {
                get_facilities.forEach((facility) => {
                    facility_list.facilities.push(facility.value);
                });
                facilities_btn.classList.remove('d-none');
            } else {
                facilities_btn.classList.add('d-none');
            }

            facility_list = JSON.stringify(facility_list);

            let xhr = new XMLHttpRequest();
            xhr.open("GET", "{{ route('ajax.rooms') }}?fetch_rooms&chk_avail=" + chk_avail + "&guests=" + guests + "&facility_list=" +
                facility_list, true);

            xhr.onprogress = function() {
                rooms_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>`;
            }

            xhr.onload = function() {
                rooms_data.innerHTML = this.responseText;
            }

            xhr.send();
        }

        function chk_avail_filter() {
            if (checkin.value != '' && checkout.value != '') {
                fetch_rooms();
                chk_avail_btn.classList.remove('d-none');
            }
        }

        function chk_avail_clear() {
            checkin.value = '';
            checkout.value = '';
            chk_avail_btn.classList.add('d-none');
            fetch_rooms();
        }

        function guests_filter() {
            if (adults.value > 0 || children.value > 0) {
                fetch_rooms();
                guests_btn.classList.remove('d-none');
            }
        }

        function guests_clear() {
            adults.value = '';
            children.value = '';
            guests_btn.classList.add('d-none');
            fetch_rooms();
        }

        function facilities_clear() {
            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            get_facilities.forEach((facility) => {
                facility.checked = false;
            });
            facilities_btn.classList.add('d-none');
            fetch_rooms();
        }


        window.onload = function() {
            fetch_rooms();
        }
    </script>
@endsection
