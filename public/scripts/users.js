function get_users() {
    let xhr = new XMLHttpRequest();
    let data = new FormData();
    data.append("_token", token);
    xhr.open("POST", routes.list, true);

    xhr.onload = function () {
        document.getElementById("users-data").innerHTML = this.responseText;
    };

    xhr.send(data);
}

function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    let data = new FormData();
    data.append("_token", token);
    data.append("id", id);
    data.append("value", val);
    xhr.open("POST", routes.toggleStatus, true);

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert("success", "Đã bật trạng thái!");
            get_users();
        } else {
            alert("success", "Máy chủ ngừng hoạt động!");
        }
    };

    xhr.send(data);
}

function remove_user(user_id) {
    if (confirm("Bạn có chắc chắn muốn xóa người dùng này không?")) {
        let data = new FormData();
        data.append("user_id", user_id);
        data.append("_token", token);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", routes.remove, true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert("success", "Đã xóa người dùng!");
                get_users();
            } else {
                alert("error", "Xóa người dùng không thành công!");
            }
        };
        xhr.send(data);
    }
}

function search_user(username) {
    let xhr = new XMLHttpRequest();
    let data = new FormData();
    data.append("_token", token);
    xhr.open("POST", routes.search + '?name=' + username, true);

    xhr.onload = function () {
        document.getElementById("users-data").innerHTML = this.responseText;
    };

    xhr.send(data);
}

window.onload = function () {
    get_users();
};
