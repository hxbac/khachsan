@extends('layouts.admin.app')

@section('content')
    <script>
        const routes = {
            get_list: `{{ route('admin.bookings.get_list') }}`,
            generatePdf: `{{ route('admin.bookings.generatePdf') }}`,
        };
    </script>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Hồ Sơ Đặt Phòng</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <input type="text" id="search_input" oninput="get_bookings(this.value)"
                                class="form-control shadow-none w-25 ms-auto" placeholder="Nhập để tìm kiếm...">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border" style="min-width: 1200px;">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Khách Hàng</th>
                                        <th scope="col">Phòng</th>
                                        <th scope="col">Chi Tiết Phòng Đặt</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                </tbody>
                            </table>
                        </div>

                        <nav>
                            <ul class="pagination mt-3" id="table-pagination">
                            </ul>
                        </nav>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('scripts/booking_records.js') }}"></script>
@endsection
