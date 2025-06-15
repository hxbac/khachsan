@extends('layouts.admin.app')

@section('content')
    <script>
        const routes = {
            list: `{{ route('admin.users.list') }}`,
            toggleStatus: `{{ route('admin.users.toggleStatus') }}`,
            remove: `{{ route('admin.users.remove') }}`,
            search: `{{ route('admin.users.search') }}`,
        };
    </script>
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Tài Khoản Khách Hàng</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <!-- <input type="text" oninput="search_user(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Nhập để tìm kiếm..."> -->
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border text-center" style="min-width: 1300px;">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Họ và Tên</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Số Điện Thoại</th>
                                        <th scope="col">Địa Chỉ</th>
                                        <th scope="col">Ngày sinh</th>
                                        <!-- <th scope="col">Xác Minh</th> -->
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Ngày Đăng Ký</th>
                                        <!-- <th scope="col">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody id="users-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('scripts/users.js') }}"></script>
@endsection
