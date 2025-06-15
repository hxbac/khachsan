function get_bookings(search = '') {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/khdatphong.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    document.getElementById('table-data').innerHTML = this.responseText;
  }

  xhr.send('get_bookings&search=' + search);
}

// function getRoomNumber(roomId) {
//   let data = new FormData();
//   data.append('room_id', roomId);
//   data.append('getRoomNumber', '');
//   let xhr = new XMLHttpRequest();
//   xhr.open("POST", "ajax/khdatphong.php", true);
//   xhr.onload = function () {
//     console.log(this.responseText)
//     if (this.responseText == 1) {
//       alert('success', 'Xác nhận thành công!');
//     }
//     else {
//       alert('error', 'Lỗi!');
//       get_bookings();
//     }
//   }
//   xhr.send(data);
// }


function getValuesAndSubmit(bookingId,i) {
          var roomNo = document.querySelector(`select[name=room_option${i}]`).value;
          console.log(bookingId,roomNo);
          kh_booking(bookingId, roomNo);
      }

function kh_booking(id,roomNo) {
  function isNumber(value) {
    return /^\d+$/.test(value);
  }
  if (roomNo!= '') {
    let data = new FormData();
    
    data.append('booking_id', id);
    data.append('room_no', roomNo);
    // data.append('trans_amt', price * totalDay);
    // data.append('booking_status', 'Đã Xác Nhận Đặt Phòng');
    // data.append('trans_status', 'TXN_SUCCESS');
    // data.append('payment_booking', '');
    data.append('kh_booking', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/khdatphong.php", true);

    xhr.onload = function () {
      console.log(this.responseText)
      if (this.responseText == 2) {
        alert('success', 'Xác nhận thành công!');
        get_bookings();
      }
      else {
        alert('error', 'Lỗi!');
        get_bookings();
      }
    }

    xhr.send(data);
  } else {
    alert('error', 'Vui lòng chọn số phòng để xác nhận đặt phòng');
    get_bookings();
  }
}

function huy_booking(id) {
  if (confirm("Bạn có chắc chắn, bạn muốn hủy đặt phòng này?")) {
    let data = new FormData();
    data.append('booking_id', id);
    data.append('huy_booking', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/khdatphong.php", true);

    xhr.onload = function () {
      if (this.responseText == 1) {
        alert('success', 'Đặt chỗ đã bị hủy!');
        get_bookings();
      }
      else {
        alert('error', 'Server Down!');
      }
    }
    xhr.send(data);
  }
}

window.onload = function () {
  get_bookings();
}