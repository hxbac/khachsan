<div
    style="width: 70%; margin: auto; font-family: Arial, sans-serif; border: 1px solid #ddd; border-radius: 10px; padding: 20px; background: #f9f9f9;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #2F4F4F; margin-bottom: 5px;">MƯỜNG THANH HOTEL</h2>
        <p style="color: #4682B4; font-size: 18px; margin-top: 0;"><strong>HOÁ ĐƠN</strong></p>
        <hr style="width: 80%; margin: 10px auto; border: 0; border-top: 2px solid #ddd;">
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 16px;">
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>ID Đơn:</strong> {{ $data->order_id }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Ngày Đặt:</strong> {{ $date }}</td>
        </tr>
        <tr>
            <td colspan="2"
                style="padding: 10px; text-align: center; background-color: #f1f1f1; border-bottom: 1px solid #ddd;">
                <strong>Trạng Thái:</strong> <span style="color: #007BFF;">{{ $data->booking_status }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Tên Khách:</strong> {{ $data->user_name }}
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Email:</strong> {{ $data->email }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Số Điện Thoại:</strong>
                {{ $data->phonenum }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Địa Chỉ:</strong> {{ $data->address }}
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Loại Phòng:</strong>
                {{ $data->room_name }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Số Phòng:</strong> {{ $room_no }}
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Ngày Vào:</strong> {{ $checkin }}
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Ngày Ra:</strong> {{ $checkout }}
            </td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Giá Phòng:</strong> {{ $price }} đ
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Thời gian lưu trú:</strong>
                {{ $count_days }} Ngày, {{ $count_nights }} Đêm</td>
        </tr>
        <tr>
            <td colspan="2"
                style="padding: 15px; text-align: center; font-size: 20px; background-color: #FFEFD5; border-radius: 5px; color: #D2691E;">
                <strong>Tổng Số Tiền Thanh Toán:</strong> {{ $price_total }} đ
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 20px;">
        <p style="font-size: 14px; color: #888;">Cảm ơn bạn đã chọn Mường Thanh Hotel!<br>Chúng tôi hy vọng được phục vụ
            bạn trong tương lai.</p>
    </div>
</div>
