<?php

$conn = mysqli_connect('localhost', 'root', '', 'php_crud_image');


function query($param) {
  global $conn;
  
  $query = mysqli_query($conn, $param);
  $rows = [];
  
  if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
      $rows[] = $row;
    }
  }
  
  return $rows;
}


function base_url($param = []) {

  $base_url = 'http://localhost:8000/page/php_crud_image/';
  
  $result = (!$param) ? $base_url : $base_url . $param;
  
  return $result;
}


function view($target, $data = []) {
  
  require_once $target . '.php';
}


function set_flashdata($param1, $param2, $param3) {

  $message = trim(stripslashes(htmlspecialchars($param1)));
  $action = trim(rtrim(stripslashes(htmlspecialchars($param2))));
  $type = trim(rtrim(stripslashes(htmlspecialchars($param3))));
  
  return $_SESSION['flash'] = [
    'message' => $message,
    'action' => $action,
    'type' => $type
  ];
}


function flashdata() {
  
  if (isset($_SESSION['flash'])) {
    echo '<div class="alert alert-'. $_SESSION['flash']['type'] .' alert-dismissible fade show" role="alert">
            Data mahasiswa <strong>'. $_SESSION['flash']['message'] .'</strong> '. $_SESSION['flash']['action']  .'
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
      
    unset($_SESSION['flash']);
  }
}


function set_userdata($param1, $param2) {
  

  $message = trim(stripslashes(htmlspecialchars($param1)));
  $type = trim(rtrim(stripslashes(htmlspecialchars($param2))));
  
  return $_SESSION['error'] = [
    'message' => $message,
    'type' => $type
  ];
}


function userdata() {
  
  
  if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-'. $_SESSION['error']['type'] .' alert-dismissible fade show" role="alert">
            '. $_SESSION['error']['message'] .'
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';

    unset($_SESSION['error']);
  }
}


function set_value($nama, $nrp, $email, $jurusan) {
  
  return $_SESSION['value'] = [
    'nama' => $nama,
    'nrp' => $nrp,
    'email' => $email,
    'jurusan' => $jurusan
  ];
}


function list_jurusan() {
  
  $list = ['teknik informatika', 'teknik planologi', 'teknik industri', 'teknik pangan', 'teknik mesin'];
  
  return $list;
}


function insert($nama, $nrp, $email, $jurusan) {
  global $conn;
  
  if (!validation($nama, $nrp, $email, $jurusan)) {
    
    
    header('Location: insert.php');
    exit();
  } else {
    
    
   
    
    if (query("SELECT nrp FROM mahasiswa WHERE nrp = '$nrp'")) {
      
      
      // jika nrp sudah digunakan oleh pengguna lain
      set_userdata('maaf, nrp ini sudah digunakan oleh pengguna lain', 'danger');

      header('Location: insert.php');
      exit();
    }
       
    if (query("SELECT email FROM mahasiswa WHERE email = '$email'")) {
        
      set_userdata('maaf, email ini sudah digunakan oleh pengguna lain', 'danger');

      header('Location: insert.php');
      exit();
    }
    
    $query = "INSERT INTO mahasiswa VALUES('', '$nama', '$nrp', '$email', '$jurusan')";

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
  }
}


function validation($nama, $nrp, $email, $jurusan) {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (empty($nama) && empty($nrp) && empty($email) && empty($jurusan)) {
      
      set_userdata('isi semua field terlebih dahulu dengan benar', 'danger');
      
      return false;
    }
    
    if (empty($nama)) {
      
      set_userdata('field nama tidak boleh kosong', 'danger');
      
      return false;
    } else if (strlen($nama) <= 5) {
      
      set_userdata('nama anda terlalu pendek', 'danger');
      
      return false;
    } else if (!preg_match("/^[a-zA-Z ]*$/", $nama)) {
      
      
      // jika field nama diisi selain dengan huruf
      set_userdata('field nama hanya boleh diisi dengan huruf saja', 'danger');
      
      return false;
    }
    
    if (empty($nrp)) {
      
      set_userdata('field nis tidak boleh kosong', 'danger');
      
      return false;
    } else if (!preg_match("/^[0-9]*$/", $nrp)) {
      
      set_userdata('field nis hanya boleh diisi dengan angka', 'danger');
      
      return false;
    } else if (strlen($nrp) <= 8 || strlen($nrp) >= 11) {
      
      // jika panjang karakter field nrp kurang dari aturan dan melebihi aturan
      set_userdata('field nis minimal dan maximal adalah 10 character', 'danger');
      
      return false;
    }
    
    if (empty($email)) {
      
      
      // jika field email kosong
      set_userdata('field email tidak boleh kosong', 'danger');
      
      return false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      
      
      // jika field email diisi dengan format email yang tidak valid
      set_userdata('bukan berupa format email yang valid', 'danger');
      
      return false;
    }
    
    if (empty($jurusan)) {
      
      set_userdata('field jurusan tidak boleh kosong', 'danger');
      
      return false;
    }
    
    return true;
  }
}

function delete($id) {
  global $conn;
  
  $query = "DELETE FROM mahasiswa WHERE id = '$id'";
  
  mysqli_query($conn, $query); 
  
  return mysqli_affected_rows($conn);
}


function edit($id, $nama, $nrp, $email, $jurusan, $nrp_lama, $email_lama) {
  global $conn;
  
  if (!validation($nama, $nrp, $email, $jurusan)) {

    header('Location: edit.php?id=' . $id);
    exit();
  } else {
    
    if ($nrp !== $nrp_lama) {
      
      if (query("SELECT nrp FROM mahasisea WHERE nrp = '$nrp'")) {
        
        
        // jika nrp sudah digunakan oleh pengguna lain
        set_userdata('maaf, nis ini sudah digunakan oleh pengguna lain', 'danger');
        
        header('Location: edit.php?id=' . $id);
        exit();
      } 
    }
    
    if ($email !== $email_lama) {
    
      if (query("SELECT email FROM mahasiswa WHERE email = '$email'")) {
        
        
        // jika email sudah pernah digunakan oleh pengguna lain
        set_userdata('maaf, email ini sudah digunakan oleh pengguna lain', 'danger');
        
        header('Location: edit.php?id=' . $id);
        exit();
      }
    }
    
    $query = "UPDATE mahasiswa SET nama = '$nama', nrp = '$nrp', email = '$email', jurusan = '$jurusan' WHERE id = '$id'";
    
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
  }
}


function search($keyword) {
  
  
  // perintah query
  $query = "SELECT * FROM mahasiswa WHERE
              nama LIKE '%$keyword%' OR
              nrp LIKE '%$keyword%' OR
              email LIKE '%$keyword%' OR
              jurusan LIKE '%$keyword%' 
            ORDER BY id DESC";
            
  return query($query);
}