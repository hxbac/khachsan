let general_data, contacts_data;

let general_s_form = document.getElementById("general_s_form");
let site_title_inp = document.getElementById("site_title_inp");
let site_about_inp = document.getElementById("site_about_inp");

let contacts_s_form = document.getElementById("contacts_s_form");

let team_s_form = document.getElementById("team_s_form");
let member_name_inp = document.getElementById("member_name_inp");
let member_picture_inp = document.getElementById("member_picture_inp");

function get_general() {
    let site_title = document.getElementById("site_title");
    let site_about = document.getElementById("site_about");

    let shutdown_toggle = document.getElementById("shutdown-toggle");

    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.get_general, true);

    xhr.onload = function () {
        general_data = JSON.parse(this.responseText);

        site_title.innerText = general_data.site_title;
        site_about.innerText = general_data.site_about;

        site_title_inp.value = general_data.site_title;
        site_about_inp.value = general_data.site_about;

        if (general_data.shutdown == 0) {
            shutdown_toggle.checked = false;
            shutdown_toggle.value = 0;
        } else {
            shutdown_toggle.checked = true;
            shutdown_toggle.value = 1;
        }
    };

    xhr.send();
}

general_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    upd_general(site_title_inp.value, site_about_inp.value);
});

function upd_general(site_title_val, site_about_val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.upd_general, true);
    const data = new FormData();
    data.append('site_title', site_title_val);
    data.append('site_about', site_about_val);

    xhr.onload = function () {
        var myModal = document.getElementById("general-s");
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert("success", "Đã lưu các thay đổi!");
            get_general();
        } else {
            alert("error", "Không có thay đổi nào được thực hiện!");
        }
    };

    xhr.send(data);
}

function upd_shutdown(val) {
    const data = new FormData();
    data.append('upd_shutdown', val);
    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.upd_shutdown, true);

    xhr.onload = function () {
        if (this.responseText == 1 && general_data.shutdown == 0) {
            alert("success", "Trang web đã bị đóng cửa!");
        } else {
            alert("success", "Tắt");
        }
        get_general();
    };

    xhr.send(data);
}

function get_contacts() {
    let contacts_p_id = [
        "address",
        "gmap",
        "pn1",
        "pn2",
        "email",
        "fb",
        "insta",
        "tw",
    ];
    let iframe = document.getElementById("iframe");

    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.get_contacts, true);

    xhr.onload = function () {
        contacts_data = JSON.parse(this.responseText);
        contacts_data = Object.values(contacts_data);

        for (i = 0; i < contacts_p_id.length; i++) {
            document.getElementById(contacts_p_id[i]).innerText =
                contacts_data[i + 1];
        }
        iframe.src = contacts_data[9];
        contacts_inp(contacts_data);
    };

    xhr.send();
}

function contacts_inp(data) {
    let contacts_inp_id = [
        "address_inp",
        "gmap_inp",
        "pn1_inp",
        "pn2_inp",
        "email_inp",
        "fb_inp",
        "insta_inp",
        "tw_inp",
        "iframe_inp",
    ];

    for (i = 0; i < contacts_inp_id.length; i++) {
        document.getElementById(contacts_inp_id[i]).value = data[i + 1];
    }
}

contacts_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    upd_contacts();
});

function upd_contacts() {
    let index = [
        "address",
        "gmap",
        "pn1",
        "pn2",
        "email",
        "fb",
        "insta",
        "tw",
        "iframe",
    ];
    let contacts_inp_id = [
        "address_inp",
        "gmap_inp",
        "pn1_inp",
        "pn2_inp",
        "email_inp",
        "fb_inp",
        "insta_inp",
        "tw_inp",
        "iframe_inp",
    ];

    let data_str = "";

    for (i = 0; i < index.length; i++) {
        data_str +=
            index[i] +
            "=" +
            document.getElementById(contacts_inp_id[i]).value +
            "&";
    }
    data_str += "upd_contacts";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.upd_contacts, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        var myModal = document.getElementById("contacts-s");
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 1) {
            alert("success", "Đã lưu các thay đổi!");
            get_contacts();
        } else {
            alert("error", "Không có thay đổi nào được thực hiện!");
        }
    };

    xhr.send(data_str);
}

team_s_form.addEventListener("submit", function (e) {
    e.preventDefault();
    add_member();
});

function add_member() {
    let data = new FormData();
    data.append("name", member_name_inp.value);
    data.append("picture", member_picture_inp.files[0]);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.add_member, true);

    xhr.onload = function () {
        var myModal = document.getElementById("team-s");
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == "inv_img") {
            alert("error", "Chỉ cho phép hình ảnh JPG và PNG!");
        } else if (this.responseText == "inv_size") {
            alert("error", "Hình ảnh nên ít hơn 2 MB!");
        } else if (this.responseText == "upd_failed") {
            alert(
                "error",
                "Tải lên hình ảnh không thành công. Máy chủ ngừng hoạt động!"
            );
        } else {
            alert("success", "Thành viên mới được thêm vào!");
            member_name_inp.value = "";
            member_picture_inp.value = "";
            get_members();
        }
    };

    xhr.send(data);
}

function get_members() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.get_members, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        document.getElementById("team-data").innerHTML = this.responseText;
    };

    xhr.send();
}

function rem_member(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", routes.rem_member, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert("success", "Đã xóa thành viên!");
            get_members();
        } else {
            alert("error", "Máy chủ ngừng hoạt động!");
        }
    };

    xhr.send("rem_member=" + val);
}

window.onload = function () {
    get_general();
    get_contacts();
    get_members();
};
