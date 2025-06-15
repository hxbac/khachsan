@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>THỐNG KÊ</h3>
                    @if ($is_shutdown)
                        <h6 class="badge bg-danger py-2 px-3 rounded">Chế độ tắt máy đang hoạt động!</h6>
                    @endif
                </div>

                <div class="row mb-4">
                    <x-dashboard.card title="Tổng Số Loại Phòng" value="{{ $total_rooms }}" color="success" />
                    <x-dashboard.card title="Tổng Số Phòng" value="{{ $tong_sophong }}" color="info" />
                    <x-dashboard.card title="Khách Hàng Mới Đặt" value="{{ $kh_datphong }}" color="info" />
                    <x-dashboard.card title="Phòng Đang Đặt" value="{{ $total_phongdat }}" color="info" />
                </div>

                <div class="row mb-3">
                    <x-dashboard.card title="Phòng Đang Trống" value="{{ $tong_sophong - $total_phongdat }}"
                        color="info" />
                    <x-dashboard.card title="Xếp hạng và đánh giá" value="{{ $total_rating }}" color="primary" />
                    <x-dashboard.card title="Khách Hàng Đăng Ký" value="{{ $total_khachhang }}" color="info" />
                    <x-dashboard.card title="Phản Hồi Và Góp Ý" value="{{ $total_phanhoi }}" color="warning" />
                </div>

                <div class="row mb-4">
                    <x-dashboard.card title="Phòng Bị Huỷ" value="{{ $total_phonghuy }}" color="danger" />
                    <x-dashboard.card title="Tổng Doanh Thu"
                        value="{{ number_format($tong_doanhthu, 0, ',', '.') . ' VNĐ' }}" color="success" />
                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('scripts/dashboard.js') }}"></script>
@endsection
