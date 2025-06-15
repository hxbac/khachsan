@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">THÔNG TIN KHÁCH HÀNG</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">TRANG CHỦ</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">THÔNG TIN</a>
                </div>
            </div>


            <div class="col-12 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info-form">
                        <h5 class="mb-3 fw-bold">Thông tin cơ bản</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tên</label>
                                <input name="name" type="text" value="{{ old('name', $user->name) }}"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số Điện Thoại</label>
                                <input name="phonenum" type="number" value="{{ old('phonenum', $user->phonenum) }}"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ngày Sinh</label>
                                <input name="dob" type="date" value="{{ old('dob', $user->dob) }}"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mã Tỉnh</label>
                                <input name="pincode" type="number" value="{{ old('pincode', $user->pincode) }}"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-8 mb-4">
                                <label class="form-label">Địa Chỉ</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required>{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Lưu Thay Đổi</button>
                    </form>
                </div>
            </div>

            <div class="col-md-4 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="profile-form">
                        <h5 class="mb-3 fw-bold">Ảnh</h5>
                        <img src="{{ asset($user->profile) }}" class="rounded-circle img-fluid mb-3">

                        <label class="form-label">Chọn Ảnh Mới</label>
                        <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp"
                            class="mb-4 form-control shadow-none" required>

                        <button type="submit" class="btn text-white custom-bg shadow-none">Lưu Thay Đổi</button>
                    </form>
                </div>
            </div>


            <div class="col-md-8 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="pass-form">
                        <h5 class="mb-3 fw-bold">Đổi Mật Khẩu</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật Khẩu Mới</label>
                                <input name="new_pass" type="password" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Xác Nhận Mật Khẩu</label>
                                <input name="confirm_pass" type="password" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <button type="submit" class="btn text-white custom-bg shadow-none">Lưu Thay Đổi</button>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <script>
        let info_form = document.getElementById('info-form');

        info_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let data = new FormData();
            data.append('name', info_form.elements['name'].value);
            data.append('phonenum', info_form.elements['phonenum'].value);
            data.append('address', info_form.elements['address'].value);
            data.append('pincode', info_form.elements['pincode'].value);
            data.append('dob', info_form.elements['dob'].value);
            data.append('_token', token);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('user.update') }}", true);

            xhr.onload = function() {
                if (this.responseText == 'phone_already') {
                    alert('error', "Phone number is already registered!");
                } else if (this.responseText == 0) {
                    alert('error', "No Changes Made!");
                } else {
                    alert('success', 'Changes saved!');
                }
            }

            xhr.send(data);

        });


        let profile_form = document.getElementById('profile-form');

        profile_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let data = new FormData();
            data.append('profile', profile_form.elements['profile'].files[0]);
            data.append('_token', token);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('user.updateAvatar') }}", true);

            xhr.onload = function() {
                if (this.responseText == 'inv_img') {
                    alert('error', "Only JPG, WEBP & PNG images are allowed!");
                } else if (this.responseText == 'upd_failed') {
                    alert('error', "Image upload failed!");
                } else if (this.responseText == 0) {
                    alert('error', "Updation failed!");
                } else {
                    window.location.href = window.location.pathname;
                }
            }

            xhr.send(data);
        });


        let pass_form = document.getElementById('pass-form');

        pass_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let new_pass = pass_form.elements['new_pass'].value;
            let confirm_pass = pass_form.elements['confirm_pass'].value;

            if (new_pass != confirm_pass) {
                alert('error', 'Password do not match!');
                return false;
            }


            let data = new FormData();
            data.append('new_pass', new_pass);
            data.append('confirm_pass', confirm_pass);
            data.append('_token', token);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('user.changePassword') }}", true);

            xhr.onload = function() {
                if (this.responseText == 'mismatch') {
                    alert('error', "Password do not match!");
                } else if (this.responseText == 0) {
                    alert('error', "Updation failed!");
                } else {
                    alert('success', 'Changes saved!');
                    pass_form.reset();
                }
            }

            xhr.send(data);
        });
    </script>
@endsection
