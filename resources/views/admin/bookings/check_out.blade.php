@extends('layouts.admin.app')

@section('content')
    <script>
        const routes = {
            get_bookings_checkout: `{{ route('admin.bookings.get_bookings_checkout') }}`,
            change_room: `{{ route('admin.bookings.change_room') }}`,
            payment_booking: `{{ route('admin.bookings.payment_booking') }}`,
            cancel_booking: `{{ route('admin.bookings.cancel_booking') }}`,
        };
    </script>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Thanh Toán Trả Phòng</h3>

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

    <script src="{{ asset('scripts/new_bookings.js') }}"></script>
@endsection
