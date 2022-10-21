<?php include "../includes/db.php"; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin - Dashboard</title>
    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">
  </head>
  <body id="page-top">
    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">
      <a class="navbar-brand mr-1" href="../index.php">Start Bootstrap</a>
      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>
    </nav>
    <div id="wrapper">
      <!-- Sidebar -->
      <ul class="sidebar navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="portfolios.php">
            <i class="fas fa-fw fa-folder"></i>
            <span>Portfolios</span>
          </a>
        </li>
      </ul>
      <div id="content-wrapper">
        <div class="container-fluid">
          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="portfolios.php">Portfolios</a>
            </li>
          </ol>
          <a class="btn btn-large btn-primary" data-toggle="modal" data-target="#add_modal" style="float:right;margin-bottom:20px;color:#fff;">Add New Portfolio</a>
          <table class="table table-bordered">
            <thead class="thead-dark">
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Text</th>
                <th>Image</th>
                <th>Edit - Delete</th>
              </tr>
            </thead>
            <tbody>
              <?php
                if(isset($_GET['delete'])){
                  $del_id = mysqli_real_escape_string($connect, $_GET['delete']);
                  $portfolios = "SELECT * FROM portfolios WHERE portfolio_id = {$del_id} ";
                  $portfolio = mysqli_query($connect, $portfolios);
                  $port = mysqli_fetch_assoc($portfolio);
                  $portfolio_img = $port["portfolio_img"];
                  if($portfolio_img != ""){
                    $files = "../assets/img/portfolio/$portfolio_img";
                    $del_file = unlink($files);
                  }
                  $query = "DELETE FROM portfolios WHERE portfolio_id = {$del_id} ";
                  $del_port_query = mysqli_query($connect,$query);
                  header("Location: portfolios.php");
                }
              ?>
              <?php
                if(isset($_POST["add_portfolio"])){
                  $portfolio_title = $_POST["portfolio_title"];
                  $portfolio_text = $_POST["portfolio_text"];
                  $portfolio_image = $_FILES["image"]["name"];
                  $portfolio_image_temp = $_FILES["image"]["tmp_name"];
                  move_uploaded_file($portfolio_image_temp, "../assets/img/portfolio/$portfolio_image");
                  $query = "INSERT INTO portfolios (portfolio_title, portfolio_text, portfolio_img)";
                  $query .= "VALUES ('{$portfolio_title}', '{$portfolio_text}', '{$portfolio_image}')";
                  $create_portfolio_query = mysqli_query($connect, $query);
                  header("Location: portfolios.php");
                }
              ?>
              <?php
                if(isset($_POST["edit_portfolio"])) {
                  $portfolio_title = $_POST["portfolio_title"];
                  $portfolio_text = $_POST["portfolio_text"];
                  $portfolio_img = $_FILES["image"]["name"];
                  $portfolio_img_temp = $_FILES["image"]["tmp_name"];
                  if(empty($portfolio_img)) {
                      $query2 = "SELECT * FROM portfolios WHERE portfolio_id = '$_POST[portfolio_id]'";
                      $select_image = mysqli_query($connect, $query2);
                      while($row = mysqli_fetch_array($select_image)) {
                          $portfolio_img = $row["portfolio_img"];
                      }
                  }
                  move_uploaded_file($portfolio_img_temp, "../assets/img/portfolio/$portfolio_img");
                  $sql_query2 = "UPDATE portfolios SET portfolio_title = '{$portfolio_title}', portfolio_text = '{$portfolio_text}', portfolio_img = '{$portfolio_img}' WHERE portfolio_id = '$_POST[portfolio_id]'";
                  $edit_portfolio_query = mysqli_query($connect, $sql_query2);
                  header("Location: portfolios.php");
                }
              ?>
              <?php 
                $sql_query = "SELECT * FROM portfolios ORDER BY portfolio_id ASC";
                $select_all_portfolios = mysqli_query($connect, $sql_query);
                $x = 1;
                while ($row = mysqli_fetch_assoc($select_all_portfolios)) {
                  $portfolio_id = $row["portfolio_id"];
                  $portfolio_title = $row["portfolio_title"];
                  $portfolio_text = $row["portfolio_text"];
                  $portfolio_img = $row["portfolio_img"];
                  echo "<tr>
                    <td>{$portfolio_id}</td>
                    <td>{$portfolio_title}</td>
                    <td>{$portfolio_text}</td>
                    <td><img src='../assets/img/portfolio/$portfolio_img' width='65px'></td>
                    <td>
                        <div class='dropdown'>
                            <button class='btn btn-primary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                Actions
                            </button>
                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                <a class='dropdown-item' data-toggle='modal' data-target='#edit_modal$x' href='#'>Edit</a>
                                <div class='dropdown-divider'></div>
                                <a class='dropdown-item' href='portfolios.php?delete={$portfolio_id}'>Delete</a>
                            </div>
                        </div>
                    </td>
                </tr>";
              ?>
              <div id="edit_modal<?php echo $x; ?>" class="modal fade">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Edit Portfolio</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                          <label for="portfolio_title">Portfolio Title</label>
                          <input type="text" class="form-control" name="portfolio_title" value="<?php echo $portfolio_title; ?>">
                        </div>
                        <div class="form-group">
                          <label for="portfolio_text">Portfolio Text</label>
                          <textarea class="form-control" name="portfolio_text" cols="20" row="5"><?php echo $row["portfolio_text"]; ?></textarea>
                        </div>
                        <div class="form-group">
                          <img src="../assets/img/portfolio/<?php echo $portfolio_img; ?>" width="65px" class="mb-3">
                          <input type="file" class="form-control" name="image">
                        </div>
                        <div class="form-group">
                          <input type="hidden" name="portfolio_id" value="<?php echo $row["portfolio_id"]; ?>">
                          <input type="submit" class="btn btn-primary" name="edit_portfolio" value="Edit Portfolio">
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <?php $x++; } ?>
            </tbody>
          </table>
          <div id="add_modal" class="modal fade">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add New Portfolio</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="portfolio_title">Portfolio Title</label>
                      <input type="text" class="form-control" name="portfolio_title">
                    </div>
                    <div class="form-group">
                      <label for="portfolio_text">Portfolio Text</label>
                      <textarea class="form-control" name="portfolio_text" cols="20" row="5"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="portfolio_image">Portfolio Image</label>
                      <input type="file" class="form-control" name="image">
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-primary" name="add_portfolio" value="Add Portfolio">
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Start Bootstrap</span>
            </div>
          </div>
        </footer>
      </div>
      <!-- /.content-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Demo scripts for this page-->
    <script src="js/demo/datatables-demo.js"></script>
    <script src="js/demo/chart-area-demo.js"></script>
  </body>
</html>
