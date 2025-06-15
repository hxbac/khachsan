

function get_bookings(search = '')
{
  let xhr = new XMLHttpRequest();
  xhr.open("POST", routes.get_list + '?search=' + search, true);

  xhr.onload = function () {
    document.getElementById('table-data').innerHTML = this.responseText;
  }

  xhr.send();
}

function change_page(page){
  get_bookings(document.getElementById('search_input').value,page);
}

function download(id){
  window.location.href = routes.generatePdf + '?id=' + id;
}


window.onload = function(){
  get_bookings();
}
