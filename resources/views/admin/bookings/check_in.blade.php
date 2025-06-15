@extends('layouts.admin.app')

@section('content')
    <script>
        const routes = {
            get_bookings: `{{ route('admin.bookings.get_bookings') }}`,
            kh_booking: `{{ route('admin.bookings.kh_booking') }}`,
            huy_booking: `{{ route('admin.bookings.huy_booking') }}`,
            get_bookings: `{{ route('admin.bookings.get_bookings') }}`,
            get_bookings: `{{ route('admin.bookings.get_bookings') }}`,
        };
    </script>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Phòng Mới Đặt</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <input type="text" oninput="get_bookings(this.value)"
                                class="form-control shadow-none w-25 ms-auto" placeholder="Nhập để tìm kiếm...">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border" style="min-width: 1200px;">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Thông Tin Khách Hàng</th>
                                        <th scope="col">Phòng</th>
                                        <th scope="col">Thông Tin Phòng Đặt</th>
                                        <th scope="col">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('scripts/khdatphong.js') }}"></script>
@endsection
