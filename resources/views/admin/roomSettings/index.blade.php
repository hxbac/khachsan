@extends('layouts.admin.app')

@section('content')
    <script>
        const routes = {
            add_room: `{{ route('admin.roomSettings.add_room') }}`,
            get_all_rooms: `{{ route('admin.roomSettings.get_all_rooms') }}`,
            edit_details: `{{ route('admin.roomSettings.edit_details') }}`,
            edit_room: `{{ route('admin.roomSettings.edit_room') }}`,
            toggle_status: `{{ route('admin.roomSettings.toggle_status') }}`,
            add_image: `{{ route('admin.roomSettings.add_image') }}`,
            get_room_images: `{{ route('admin.roomSettings.get_room_images') }}`,
            rem_image: `{{ route('admin.roomSettings.rem_image') }}`,
            thumb_image: `{{ route('admin.roomSettings.thumb_image') }}`,
            remove_room: `{{ route('admin.roomSettings.remove_room') }}`,
        };
    </script>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">PHÒNG</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal"
                                data-bs-target="#add-room">
                                <i class="bi bi-plus-square"></i> Thêm
                            </button>
                        </div>

                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Tên Phòng</th>
                                        <th scope="col">Diện Tích</th>
                                        <th scope="col">Khách Hàng</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số Lượng</th>
                                        <th scope="col">Trạng Thái</th>
                                        <th scope="col">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Add room modal -->

    <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_room_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Phòng</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Diện Tích</label>
                                <input type="number" min="1" name="area" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số Lượng</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Người Lớn</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trẻ Em</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Đặc Tính</label>
                                <div class="row">
                                    @foreach ($features as $feature)
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="features" value="{{ $feature->id }}"
                                                    class="form-check-input shadow-none">
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Tiện Nghi & Trang Thiết Bị</label>
                                <div class="row">
                                    @foreach ($facilities as $facility)
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="facilities" value="{{ $facility->id }}"
                                                    class="form-check-input shadow-none">
                                                {{ $facility->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Mô Tả</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none"
                            data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit room modal -->

    <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_room_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Phòng</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên Phòng</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Diện Tích</label>
                                <input type="number" min="1" name="area" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số Lượng</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none"
                                    required disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Người Lớn</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Trẻ Em</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none"
                                    required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Đặc Tính</label>
                                <div class="row">
                                    @foreach ($features as $feature)
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="features" value="{{ $feature->id }}"
                                                    class="form-check-input shadow-none">
                                                {{ $feature->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Tiện Nghi & Trang Thiết Bị</label>
                                <div class="row">
                                    @foreach ($facilities as $facility)
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="facilities" value="{{ $facility->id }}"
                                                    class="form-check-input shadow-none">
                                                {{ $facility->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Mô Tả</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                            <input type="hidden" name="room_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none"
                            data-bs-dismiss="modal">Huỷ</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Cập Nhật</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage room images modal -->

    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tên Phòng</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image-alert"></div>
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold">Thêm Ảnh</label>
                            <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg"
                                class="form-control shadow-none mb-3" required>
                            <button class="btn custom-bg text-white shadow-none">Thêm</button>
                            <input type="hidden" name="room_id">
                        </form>
                    </div>
                    <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border text-center">
                            <thead>
                                <tr class="bg-dark text-light sticky-top">
                                    <th scope="col" width="60%">Ảnh</th>
                                    <th scope="col">Trạng Thái</th>
                                    <th scope="col">Xoá</th>
                                </tr>
                            </thead>
                            <tbody id="room-image-data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('scripts/rooms.js') }}"></script>
@endsection
