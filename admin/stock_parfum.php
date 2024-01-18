<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (isset($_POST['submit'])) {
    if (empty($_POST['d_name']) || empty($_POST['about']) || $_POST['price'] == '') {
        $error =     '<div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>All fields Must be Fillup!</strong>
                    </div>';
    } else {
        $fname = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
        $fsize = $_FILES['file']['size'];
        $extension = explode('.', $fname);
        $extension = strtolower(end($extension));
        $fnew = uniqid() . '.' . $extension;
        $store = "Res_img/parfum/" . basename($fnew);
        if ($extension == 'jpg' || $extension == 'png' || $extension == 'gif') {
            if ($fsize >= 1000000) {
                $error =    '<div class="alert alert-danger alert-dismissible fade show">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Max Image Size is 1024kb!</strong> Try different Image.
                            </div>';
            } else {
                $sql = "INSERT INTO parfum(title,slogan,price,img) VALUE('" . $_POST['d_name'] . "','" . $_POST['about'] . "','" . $_POST['price'] . "','" . $fnew . "')";  // store the submited data ino the database :images
                mysqli_query($db, $sql);
                move_uploaded_file($temp, $store);
                $success =     '<div class="alert alert-success alert-dismissible fade show">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    New Dish Added Successfully.
                                </div>';
            }
        } elseif ($extension == '') {
            $error =    '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>select image</strong>
                        </div>';
        } else {
            $error =    '<div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>invalid extension!</strong>png, jpg, Gif are accepted.
                        </div>';
        }
    }
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>Add Menu</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body class="fix-header">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <!-- navbar -->
        <?php require_once('layout/navbar.php') ?>
        <!-- sidebar -->
        <?php require_once('layout/sidebar.php') ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <!-- Start Page Content -->
                <?php echo $error;
                echo $success; ?>
                <div class="col-lg-12">
                <div class="card">
              <h5 class="card-header">Stock Barang</h5>
              <div class="card-body">
                <div class="table-responsive text-nowrap">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>Deskripsi</th>
                        <th>Stock</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $ambilsemuadatastock = mysqli_query($conn, "select * from stock");
                      $i = 1;
                      while ($data = mysqli_fetch_array($ambilsemuadatastock)) {
                        $namabarang = $data['namabarang'];
                        $deskripsi = $data['deskripsi'];
                        $stock = $data['stock'];
                        $idb = $data['idbarang'];

                        //cek ada gambar atau tidak
                        $gambar = $data['image']; //ambil gambar
                        if ($gambar == null) {
                          //jika tidak ada gambar
                          $img = 'No Photo';
                        } else {
                          //jika ada gambar
                          $img = '<img src="images/' . $gambar . '" class="zoomable">';
                        }


                      ?>
                        <tr>
                          <td><?= $i++; ?></td>
                          <td><?= $img; ?></td>
                          <td><?= $namabarang; ?></td>
                          <td><?= $deskripsi; ?></td>
                          <td><?= $stock; ?></td>
                          <td>
                            <button type="button" class="btn btn-warning"><i class="bx bx-edit" data-bs-toggle="modal" data-bs-target="#edit<?= $idb; ?>">
                              </i></button>
                            <button type="button" class="btn btn-danger"><i class="bx bx-trash me-1" data-bs-toggle="modal" data-bs-target="#delete<?= $idb; ?>">
                              </i></button>
                          </td>
                        </tr>
                        <!-- Edit Modal -->
                        <div class="modal fade" id="edit<?= $idb; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">Edit Barang</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>


                              <!-- Modal Body -->
                              <form method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                  <input type="text" name="namabarang" value="<?= $namabarang; ?>" class="form-control" required>
                                  <br>
                                  <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control" required>
                                  <br>
                                  <input type="file" name="file" class="form-control">
                                  <br>
                                  <input type="hidden" name="idb" value="<?= $idb; ?>">
                                  <button type="submit" class="btn btn-primary" name="updatebarang">Submit</button>
                                </div>
                              </form>

                            </div>
                          </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="delete<?= $idb; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Modal Header -->
                              <div class="modal-header">
                                <h4 class="modal-title">Hapus Barang?</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>


                              <!-- Modal Body -->
                              <form method="post">
                                <div class="modal-body">
                                  Apakah Anda yakin ingin menghapus <?= $namabarang; ?>?
                                  <input type="hidden" name="idb" value="<?= $idb; ?>">
                                  <br>
                                  <br>
                                  <button type="submit" class="btn btn-danger" name="hapusbarang"><i class="bx bx-trash me-1">
                                    </i></button>
                                </div>
                              </form>
                            </div>
                          </div>

                        </div>
                      <?php
                      };

                      ?>


                    </tbody>
                  </table><br>

                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                  Tambah Barang
                </button>
                <a href="export.php" class="btn btn-dark">Export Data</a>
                <div class="modal fade" id="myModal">
                  <div class="modal-dialog">
                    <div class="modal-content">

                      <!-- Modal Header -->
                      <div class="modal-header">
                        <h4 class="modal-title">Tambah Barang</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>


                      <!-- Modal Body -->
                      <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                          <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
                          <br>
                          <input type="text" name="deskripsi" placeholder="Deskripsi Barang" class="form-control" required>
                          <br>
                          <input type="number" name="stock" class="form-control" placeholder="Stock" required>
                          <br>
                          <input type="file" name="file" class="form-control">
                          <br>
                          <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer -->
    <?php require_once('layout/footer.php') ?>

    <!-- js -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>

</body>

</html>