function get_room_list() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.list, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        document.getElementById("room-list-data").innerHTML = this.responseText;
    };
    xhr.send();
}

function getValueAndSubmit(roomId, roomNumberId) {
    var roomNo = document.querySelector(
        `input[name=room_${roomId}${roomNumberId}]`
    ).value;
    console.log(roomNo);
    add_room_number(roomId, roomNumberId, roomNo);
}

function add_room_number(roomId, roomNumberId, roomNo) {
    function isNumber(value) {
        return /^\d+$/.test(value);
    }
    if (roomNo !== "") {
        let data = new FormData();
        data.append("room_id", roomId);
        data.append("room_number_id", roomNumberId);
        data.append("room_no", roomNo);
        data.append("_token", token);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", routes.addNumber, true);
        xhr.onload = function () {
            console.log(this.responseText);
            if (this.responseText == 1) {
                alert("success", "Xác nhận thành công!");
                get_room_list();
            } else {
                alert("error", "Lỗi!");
                get_room_list();
            }
        };
        xhr.send(data);
    } else {
        alert("error", "Vui lòng nhập số phòng hợp lệ");
        get_room_list();
    }
}

function del_room_number(id_room_number_str) {
    if (confirm("Bạn có muốn xoá phòng này không?")) {
        let data = new FormData();
        data.append("_token", token);
        data.append("id_room_number_str", id_room_number_str);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", routes.deleteNumber, true);
        xhr.onload = function () {
            console.log(this.responseText);
            if (this.responseText == 1) {
                alert("success", "Đã xoá thành công!");
                get_room_list();
            } else {
                alert("error", "Lỗi!");
                get_room_list();
            }
        };
        xhr.send(data);
    }
}

window.onload = function () {
    get_room_list();
};
