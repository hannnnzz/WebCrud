
$(document).ready(function() {
  
  $('#myTable').dataTable();

  $('.badge-deletes').on('click', function(e) {

    event.preventDefault();

    const target = this.dataset.target;


    swal.fire({
      position: 'center',
      icon: 'warning',
      title: 'apakah anda sudah yakin',
      text: 'ingin menghapus data tersebut?',
      showCancelButton: true,
      cancelButtonText: 'tidak',
      confirmButtonText: 'yakin'
    }).then(result => {

      if (result.value) document.location.href = target;
    });
  });
});
