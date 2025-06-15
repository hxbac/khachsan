function get_bookings(search = '') {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/new_bookings.php", true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  xhr.onload = function () {
    document.getElementById('table-data').innerHTML = this.responseText;
  }

  xhr.send('get_bookings&search=' + search);
}

// let assign_room_form = document.getElementById('assign_room_form');

function assign_room(id) {
  assign_room_form.elements['booking_id'].value = id;
}

//Change room
function getValuesAndSubmit(bookingId,roomId,i) {
  var roomNo = document.querySelector(`select[name=room_option${i}]`).value;
  console.log(bookingId,roomId,roomNo);
  change_room(bookingId,roomId, roomNo);
}
function change_room(bookingId,roomId, roomNo) {
  function isNumber(value) {
    return /^\d+$/.test(value);
  }
  if (roomNo!= '') {
    let data = new FormData();
    data.append('change_room', '');
    data.append('booking_id', bookingId);
    data.append('room_id_num_old', roomId);
    data.append('room_no_id_new', roomNo);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);

    xhr.onload = function () {
      console.log(this.responseText)
      if (this.responseText == 1) {
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


function payment_booking(id, price, totalDay,roomNo) {
  if (confirm("Bạn có chắc chắn, bạn muốn thanh toán đặt phòng này?")) {
    let data = new FormData();
    data.append('booking_id', id);
    data.append('trans_amt', price * totalDay);
    data.append('booking_status', 'Đã Thanh Toán');
    data.append('room_num', roomNo);
    data.append('trans_status', 'TXN_SUCCESS');
    data.append('payment_booking', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);

    xhr.onload = function () {
      console.log(this.responseText)
      if (this.responseText == 1) {
        alert('success', 'Thanh toán thành công!');
        get_bookings();
      }
      else {
        alert('error', 'Server Down!');
        get_bookings();
      }
    }

    xhr.send(data);
  }
}

function cancel_booking(id, roomNo) {
  if (confirm("Bạn có chắc chắn, bạn muốn hủy đặt phòng này?")) {
    let data = new FormData();
    data.append('booking_id', id);
    data.append('room_num_id', roomNo);
    data.append('cancel_booking', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);

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