<?php


session_start();
require_once 'function/functions.php';

$data['title'] = 'Halaman ubah data';
$data['jurusan'] = list_jurusan();

$id = trim(rtrim(mysqli_real_escape_string($conn, $_GET['id'])));
$row = query("SELECT * FROM mahasiswa WHERE id = '$id'")[0];

view('templates/header', $data);


if (isset($_POST['submit'])) {
  
  $nama = trim(stripslashes(htmlspecialchars($_POST['nama'])));
  $nrp = trim(rtrim(stripslashes(htmlspecialchars($_POST['nrp']))));
  $email = trim(stripslashes(htmlspecialchars($_POST['email'])));
  $jurusan = trim(stripslashes(htmlspecialchars($_POST['jurusan'])));
  
  set_value($nama, $nrp, $email, $jurusan);
  
  if (edit($id, $nama, $nrp, $email, $jurusan, $row['nrp'], $row['email'], $row['gambar']) > 0) {
    
    set_flashdata('berhasil', 'diubah', 'success');
    
    header('Location: index.php');
    exit();
  } else {
    
    set_flashdata('gagal', 'diubah', 'danger');
    
    header('Location: index.php');
    exit();
  }
}

?>

<div class="container mt-3 mb-3">
  <div class="row">
    <div class="col-md-9">

      <div class="card shadow rounded">
        <div class="card-header">
          <div class="float-right">
            <small class="text-black-50">form ubah data</small>
          </div>
        </div>
        <div class="card-body">
          <div class="flash-container">
            <?= userdata(); ?>
          </div>
          <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="nama"><small class="text-black-50">Nama Lengkap</small></label>
              <input type="text" name="nama" class="form-control" id="nama" placeholder="nama" autocomplete="off" value="<?= $row['nama']; ?>">
            </div>
            <div class="form-group">
              <label for="nrp"><small class="text-black-50">NRP</small></label>
              <input type="text" name="nrp" class="form-control" id="nrp" placeholder="nrp" autocomplete="off" value="<?= $row['nrp']; ?>">
            </div>
            <div class="form-group">
              <label for="email"><small class="text-black-50">Alamat Email</small></label>
              <input type="text" name="email" class="form-control" id="email" placeholder="example@example.com" autocomplete="off" value="<?= $row['email']; ?>">
            </div>
            <div class="form-group">
              <label for="jurusan"><small class="text-black-50">Jurusan</small></label>
              <select name="jurusan" id="jurusan" class="form-control">
                <?php foreach ($data['jurusan'] as $jurusan) : ?>
                <?php if ($jurusan === $row['jurusan']) : ?>
                  <option value="<?= $jurusan; ?>" selected><?= strtolower($jurusan); ?></option>
                <?php else : ?>
                  <option value="<?= $jurusan; ?>"><?= strtolower($jurusan); ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
              </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary text-light float-right">
              <small class="fas fa-fw fa-edit mr-1"></small>
              <small>ubah data</small>
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<?php


$data['javascript'] = 'validation.js';

view('templates/footer', $data);

?>