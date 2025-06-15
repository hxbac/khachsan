@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Khách Hàng Phản Hồi</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check-all"></i> Đánh dấu tất cả đã đọc
                            </a>
                            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                                <i class="bi bi-trash"></i> Xoá tất cả
                            </a>
                        </div>

                        <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border">
                                <thead class="sticky-top">
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" width="20%">Tiêu Đề</th>
                                        <th scope="col" width="30%">Tin Nhắn</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp

                                    @foreach ($queries as $query)
                                        @php
                                            $date = \Carbon\Carbon::parse($query->datentime)->format('d-m-Y');

                                            $seen = '';

                                            if ($query->seen != 1) {
                                                $seen .= "<a href='". route('admin.usersQuery.seen') ."?seen=$query->sr_no' class='btn btn-sm rounded-pill btn-primary'>Đánh dấu là đã đọc</a> <br>";
                                            }

                                            $seen .= "<a href='" . route('admin.usersQuery.delete') . "?del=$query->sr_no' class='btn btn-sm rounded-pill btn-danger mt-2'>Xoá</a>";
                                        @endphp

                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $query->name }}</td>
                                            <td>{{ $query->email }}</td>
                                            <td>{{ $query->subject }}</td>
                                            <td>{{ $query->message }}</td>
                                            <td>{{ $date }}</td>
                                            <td>{!! $seen !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
