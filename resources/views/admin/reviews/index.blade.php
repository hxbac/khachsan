@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Đánh Giá</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <a href="{{ route('admin.reviews.seen') }}?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check-all"></i> Đánh dấu tất cả đã đọc
                            </a>
                            <a href="{{ route('admin.reviews.delete') }}?delete=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                                <i class="bi bi-trash"></i> Xoá tất cả
                            </a>
                        </div>

                        <div class="table-responsive-md">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Tên Phòng</th>
                                        <th scope="col">Tên Khách Hàng</th>
                                        <th scope="col">Đánh Giá</th>
                                        <th scope="col" width="30%">Nhận Xét</th>
                                        <th scope="col">Ngày</th>
                                        <th scope="col">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reviews as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $row->rname }}</td>
                                            <td>{{ $row->uname }}</td>
                                            <td>{{ $row->rating }}</td>
                                            <td>{{ $row->review }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->datentime)->format('d-m-Y') }}</td>
                                            <td>
                                                @if (!$row->seen)
                                                    <a href="{{ route('admin.reviews.seen', ['seen' => $row->sr_no]) }}"
                                                        class="btn btn-sm btn-primary rounded-pill mb-2">Đánh dấu là đã
                                                        đọc</a><br>
                                                @endif
                                                <a href="{{ route('admin.reviews.delete', ['delete' => $row->sr_no]) }}"
                                                    class="btn btn-sm btn-danger rounded-pill">Xoá</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">Không có đánh giá nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
