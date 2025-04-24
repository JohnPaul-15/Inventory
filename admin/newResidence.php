<?php 
include_once '../connection.php';
session_start();

try{
  if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'){
    $user_id = $_SESSION['user_id'];
    $sql_user = "SELECT * FROM `users` WHERE `id` = ? ";
    $stmt_user = $con->prepare($sql_user) or die ($con->error);
    $stmt_user->bind_param('s',$user_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $row_user = $result_user->fetch_assoc();
    $first_name_user = $row_user['first_name'];
    $last_name_user = $row_user['last_name'];
    $user_type = $row_user['user_type'];
    $user_image = $row_user['image'];
  
    $sql = "SELECT * FROM `barangay_information`";
    $query = $con->prepare($sql) or die ($con->error);
    $query->execute();
    $result = $query->get_result();
    while($row = $result->fetch_assoc()){
        $barangay = $row['barangay'];
        $zone = $row['zone'];
        $district = $row['district'];
        $image = $row['image'];
        $image_path = $row['image_path'];
        $id = $row['id'];
    }
  }else{
    echo '<script>window.location.href = "../login.php";</script>';
  }
}catch(Exception $e){
  echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory System - Item Management</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../assets/plugins/sweetalert2/css/sweetalert2.min.css">
  
 <style>
    .item-image {
      height: 60px;
      width: auto;
      max-width: 80px;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .badge-request {
      background-color: #ffc107;
      color: #212529;
    }
    .badge-return {
      background-color: #17a2b8;
      color: white;
    }
 </style>
</head>
<body class="hold-transition dark-mode sidebar-mini layout-footer-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__wobble " src="../assets/dist/img/loader.gif" alt="AdminLTELogo" height="70" width="70">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <h5><a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></h5>
      </li>
      <li class="nav-item d-none d-sm-inline-block" style="font-variant: small-caps;">
        <h5 class="nav-link text-white" >Inventory System</h5>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="myProfile.php" class="dropdown-item">
            <div class="media">
              <?php 
                if($user_image != '' || $user_image != null || !empty($user_image)){
                  echo '<img src="../assets/dist/img/'.$user_image.'" class="img-size-50 mr-3 img-circle alt="User Image">';
                }else{
                  echo '<img src="../assets/dist/img/image.png" class="img-size-50 mr-3 img-circle alt="User Image">';
                }
              ?>
              <div class="media-body">
                <h3 class="dropdown-item-title py-3">
                  <?= ucfirst($first_name_user) .' '. ucfirst($last_name_user) ?>
                </h3>
              </div>
            </div>
          </a>         
          <div class="dropdown-divider"></div>
          <a href="../logout.php" class="dropdown-item dropdown-footer">LOGOUT</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="#" class="brand-link text-center">
    <?php 
        if($image != '' || $image != null || !empty($image)){
          echo '<img src="'.$image_path.'" id="logo_image" class="img-circle elevation-5 img-bordered-sm" alt="logo" style="width: 70%;">';
        }else{
          echo ' <img src="../assets/logo/logo.png" id="logo_image" class="img-circle elevation-5 img-bordered-sm" alt="logo" style="width: 70%;">';
        }
      ?>
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../assets/dist/img/logo.png" class="img-circle elevation-5 img-bordered-sm" alt="User Image">
        </div>
        <div class="info text-center">
          <a href="#" class="d-block text-bold"><?= strtoupper($user_type) ?></a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link bg-indigo">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="newResidence.php" class="nav-link active">
              <i class="nav-icon fas fa-boxes"></i>
              <p>Inventory</p>
            </a>
          </li>
          <li class="nav-item ">
            <a href="usersResident.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Team Members</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content mt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-indigo">
              <div class="card-header">
                <h3 class="card-title">Item Management</h3>
                <button class="btn btn-success float-right" data-toggle="modal" data-target="#addItemModal">
                  <i class="fas fa-plus"></i> Add Item
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="all-items-tab" data-toggle="pill" href="#all-items" role="tab" aria-controls="all-items" aria-selected="true">All Items</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="requests-tab" data-toggle="pill" href="#requests" role="tab" aria-controls="requests" aria-selected="false">Check Requests</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="returns-tab" data-toggle="pill" href="#returns" role="tab" aria-controls="returns" aria-selected="false">Pending Returns</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="inventoryTabsContent">
                    <div class="tab-pane fade show active" id="all-items" role="tabpanel" aria-labelledby="all-items-tab">
                      <div class="table-responsive">
                      <table id="itemsTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Return</th>
                            <th>On Hand</th>
                            <th>Borrowed</th>
                            <th>Available</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                          <!-- Existing sample row -->
                          <tr>
                            <td>1</td>
                            <td>Laptop</td>
                            <td>
                              <input type="number" class="form-control return-qty" data-id="1" min="0" max="5" value="0">
                            </td>
                            <td class="on-hand">15</td>
                            <td class="borrowed">5</td>
                            <td class="available">10</td>
                            <td>
                              <button class="btn btn-sm btn-primary edit-btn" data-id="1"><i class="fas fa-edit"></i></button>
                              <button class="btn btn-sm btn-info request-btn" data-id="1"><i class="fas fa-hand-holding"></i></button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="requests" role="tabpanel" aria-labelledby="requests-tab">
                      <div class="table-responsive">
                      <table id="itemsTable" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Item</th>
                              <th>Return</th>
                              <th>On Hand</th>
                              <th>Borrowed</th>
                              <th>Available</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Sample Request Data -->
                            <tr>
                              <td>1</td>
                              <td>Laptop</td>
                              <td>John Doe</td>
                              <td>2</td>
                              <td>2023-06-15</td>
                              <td><span class="badge badge-warning">Pending</span></td>
                              <td>
                                <button class="btn btn-sm btn-success approve-btn" data-id="1"><i class="fas fa-check"></i></button>
                                <button class="btn btn-sm btn-danger reject-btn" data-id="1"><i class="fas fa-times"></i></button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                      <div class="table-responsive">
                        <table id="returnsTable" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Item</th>
                              <th>Borrowed By</th>
                              <th>Quantity</th>
                              <th>Date Borrowed</th>
                              <th>Due Date</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Sample Return Data -->
                            <tr>
                              <td>1</td>
                              <td>Laptop</td>
                              <td>John Doe</td>
                              <td>2</td>
                              <td>2023-06-01</td>
                              <td>2023-06-15</td>
                              <td>
                                <button class="btn btn-sm btn-success return-btn" data-id="1"><i class="fas fa-undo"></i> Mark Returned</button>
                              </td>
                            </tr>
                            <tr>
                              <td>2</td>
                              <td>Camera</td>
                              <td>Mike Johnson</td>
                              <td>1</td>
                              <td>2023-06-05</td>
                              <td>2023-06-19</td>
                              <td>
                                <button class="btn btn-sm btn-success return-btn" data-id="2"><i class="fas fa-undo"></i> Mark Returned</button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Add Item Modal -->
  <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-indigo">
          <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addItemForm" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row justify-content-center"> <!-- Added justify-content-center here -->
              <div class="col-md-8"> <!-- Changed from col-md-4 to col-md-8 and removed empty column -->
                <div class="form-group text-center"> <!-- Added text-center -->
                  <label for="itemName">Item Name</label>
                  <input type="text" class="form-control text-center mx-auto" style="max-width: 400px;" id="itemName" name="itemName" required>
                </div>

                <div class="row justify-content-center"> <!-- Added justify-content-center -->
                  <div class="col-md-5 text-center"> <!-- Added text-center and adjusted column size -->
                    <div class="form-group">
                      <label for="itemQuantity">Initial Quantity</label>
                      <input type="number" class="form-control text-center mx-auto" style="max-width: 150px;" id="itemQuantity" name="itemQuantity" min="1" value="1" required>
                    </div>
                  </div>
                  <div class="col-md-5 text-center"> <!-- Added text-center and adjusted column size -->
                    <div class="form-group">
                      <label for="itemCategory">Category</label>
                      <select class="form-control text-center mx-auto" style="max-width: 200px;" id="itemCategory" name="itemCategory">
                        <option value="Electronics">Electronics</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Tools">Tools</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-center"> <!-- Added justify-content-center -->
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Item</button>
          </div>
        </form>
      </div>
    </div>
</div>

  <!-- Edit Item Modal -->
  <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-indigo">
          <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="editItemForm" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" id="editItemId" name="editItemId">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="editItemImage">Item Image</label>
                  <div class="text-center">
                    <img id="editItemImagePreview" src="../assets/dist/img/blank_image.png" class="img-thumbnail item-image" style="cursor: pointer;">
                    <input type="file" name="editItemImage" id="editItemImage" style="display: none;" accept="image/*">
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label for="editItemName">Item Name</label>
                  <input type="text" class="form-control" id="editItemName" name="editItemName" required>
                </div>
                <div class="form-group">
                  <label for="editItemDescription">Description</label>
                  <textarea class="form-control" id="editItemDescription" name="editItemDescription" rows="2"></textarea>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="editItemQuantity">Current Quantity</label>
                      <input type="number" class="form-control" id="editItemQuantity" name="editItemQuantity" min="1" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="editItemCategory">Category</label>
                      <select class="form-control" id="editItemCategory" name="editItemCategory">
                        <option value="Electronics">Electronics</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Tools">Tools</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Item</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Request Item Modal -->
  <div class="modal fade" id="requestItemModal" tabindex="-1" role="dialog" aria-labelledby="requestItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="requestItemModalLabel">Request Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="requestItemForm" method="POST">
          <div class="modal-body">
            <input type="hidden" id="requestItemId" name="requestItemId">
            <div class="form-group">
              <label for="requestItemName">Item Name</label>
              <input type="text" class="form-control" id="requestItemName" readonly>
            </div>
            <div class="form-group">
              <label for="requestItemAvailable">Available Quantity</label>
              <input type="text" class="form-control" id="requestItemAvailable" readonly>
            </div>
            <div class="form-group">
              <label for="requestQuantity">Quantity to Request</label>
              <input type="number" class="form-control" id="requestQuantity" name="requestQuantity" min="1" value="1" required>
            </div>
            <div class="form-group">
              <label for="requestPurpose">Purpose</label>
              <textarea class="form-control" id="requestPurpose" name="requestPurpose" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label for="requestDueDate">Due Date</label>
              <input type="date" class="form-control" id="requestDueDate" name="requestDueDate" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-info">Submit Request</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; <?php echo date("Y"); ?> - <?php echo date('Y', strtotime('+1 year'));  ?> </strong>
    <div class="float-right d-none d-sm-inline-block"></div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="../assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../assets/dist/js/adminlte.js"></script>
<script src="../assets/plugins/popper/umd/popper.min.js"></script>
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../assets/plugins/sweetalert2/js/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
  // Initialize DataTables only once
  var itemsTable = $('#itemsTable').DataTable({
    "responsive": true,
    "autoWidth": false,
    "order": [[0, 'asc']]
  });
  
  // Initialize counter based on existing rows
  let itemCounter = $('#itemsTableBody tr').length > 0 ? 
    Math.max(...$('#itemsTableBody tr').map(function() {
      return parseInt($(this).find('td:first').text());
    }).get()) + 1 : 1;

  // Handle form submission for adding new item
  $('#addItemForm').submit(function(e) {
    e.preventDefault();
    
    const itemName = $('#itemName').val();
    const itemQuantity = parseInt($('#itemQuantity').val());
    const itemCategory = $('#itemCategory').val();
    
    if (!itemName || isNaN(itemQuantity)) {
      Swal.fire('Error', 'Please fill all fields correctly', 'error');
      return;
    }

    // Add new row to the DataTable
    itemsTable.row.add([
      itemCounter,
      itemName,
      '<input type="number" class="form-control return-qty" data-id="'+itemCounter+'" min="0" value="0">',
      itemQuantity,
      '0',
      itemQuantity,
      '<button class="btn btn-sm btn-primary edit-btn" data-id="'+itemCounter+'"><i class="fas fa-edit"></i></button> ' +
      '<button class="btn btn-sm btn-info request-btn" data-id="'+itemCounter+'"><i class="fas fa-hand-holding"></i></button>'
    ]).draw();
    
    itemCounter++;
    
    // Reset form and close modal
    $('#addItemForm')[0].reset();
    $('#addItemModal').modal('hide');
    
    // Reinitialize return functionality
    initReturnFunctionality();
    
    Swal.fire('Success!', 'Item added successfully', 'success');
  });

  // Initialize return functionality
  function initReturnFunctionality() {
    $('#itemsTable').off('change', '.return-qty').on('change', '.return-qty', function() {
      const returnQty = parseInt($(this).val()) || 0;
      const $row = $(this).closest('tr');
      const borrowed = parseInt($row.find('td:eq(4)').text());
      const onHand = parseInt($row.find('td:eq(3)').text());

      // Validate
      if (returnQty < 0) {
        $(this).val(0);
        return;
      }
      if (returnQty > borrowed) {
        $(this).val(borrowed);
        Swal.fire('Error', 'Cannot return more than borrowed quantity', 'error');
        return;
      }

      // Update values
      const newBorrowed = borrowed - returnQty;
      const newOnHand = onHand + returnQty;
      $row.find('td:eq(4)').text(newBorrowed);
      $row.find('td:eq(3)').text(newOnHand);
      $row.find('td:eq(5)').text(newOnHand - newBorrowed);
      $(this).val(0);
    });
  }

  // Initialize for existing items
  initReturnFunctionality();

  // ... keep your other existing event handlers ...
});
</script>

<script>
  $(document).ready(function() {
    // Initialize DataTables
    $('#itemsTable').DataTable({
      "responsive": true,
      "autoWidth": false,
      "order": [[0, 'asc']]
    });
    
    $('#requestsTable').DataTable({
      "responsive": true,
      "autoWidth": false,
      "order": [[4, 'desc']]
    });
    
    $('#returnsTable').DataTable({
      "responsive": true,
      "autoWidth": false,
      "order": [[5, 'asc']]
    });

    // Image preview for add modal
    $('#itemImage').change(function() {
      readURL(this, '#itemImagePreview');
    });
    
    $('#itemImagePreview').click(function() {
      $('#itemImage').click();
    });

    // Image preview for edit modal
    $('#editItemImage').change(function() {
      readURL(this, '#editItemImagePreview');
    });
    
    $('#editItemImagePreview').click(function() {
      $('#editItemImage').click();
    });

    // Function to display image preview
    function readURL(input, previewId) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
          $(previewId).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }

    // Handle edit button click
    $('.edit-btn').click(function() {
      var itemId = $(this).data('id');
      // In a real application, you would fetch the item data via AJAX
      // Here we're just simulating with sample data
      $('#editItemId').val(itemId);
      $('#editItemName').val(itemId == 1 ? 'Laptop' : (itemId == 2 ? 'Projector' : 'Camera'));
      $('#editItemDescription').val(itemId == 1 ? 'Dell Latitude 15" laptop' : (itemId == 2 ? 'HD Projector 3000 lumens' : 'Canon DSLR Camera'));
      $('#editItemQuantity').val(itemId == 1 ? '15' : (itemId == 2 ? '8' : '12'));
      $('#editItemCategory').val(itemId == 1 ? 'Electronics' : (itemId == 2 ? 'Equipment' : 'Electronics'));
      $('#editItemImagePreview').attr('src', itemId == 1 ? '../assets/dist/img/laptop.png' : (itemId == 2 ? '../assets/dist/img/projector.png' : '../assets/dist/img/camera.png'));
      $('#editItemModal').modal('show');
    });

    // Handle request button click
    $('.request-btn').click(function() {
      var itemId = $(this).data('id');
      // In a real application, you would fetch the item data via AJAX
      $('#requestItemId').val(itemId);
      $('#requestItemName').val(itemId == 1 ? 'Laptop' : (itemId == 2 ? 'Projector' : 'Camera'));
      $('#requestItemAvailable').val(itemId == 1 ? '10' : (itemId == 2 ? '5' : '5'));
      $('#requestQuantity').attr('max', itemId == 1 ? '10' : (itemId == 2 ? '5' : '5'));
      $('#requestItemModal').modal('show');
    });

    // Handle approve request button click
    $('.approve-btn').click(function() {
      var requestId = $(this).data('id');
      Swal.fire({
        title: 'Approve Request?',
        text: "Are you sure you want to approve this request?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, approve it!'
      }).then((result) => {
        if (result.isConfirmed) {
          // In a real application, you would make an AJAX call here
          Swal.fire(
            'Approved!',
            'The request has been approved.',
            'success'
          ).then(() => {
            // Reload the page or update the table via AJAX
            location.reload();
          });
        }
      });
    });

    // Handle reject request button click
    $('.reject-btn').click(function() {
      var requestId = $(this).data('id');
      Swal.fire({
        title: 'Reject Request?',
        text: "Are you sure you want to reject this request?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, reject it!'
      }).then((result) => {
        if (result.isConfirmed) {
          // In a real application, you would make an AJAX call here
          Swal.fire(
            'Rejected!',
            'The request has been rejected.',
            'success'
          ).then(() => {
            // Reload the page or update the table via AJAX
            location.reload();
          });
        }
      });
    });

    // Handle return item button click
    $('.return-btn').click(function() {
      var returnId = $(this).data('id');
      Swal.fire({
        title: 'Mark as Returned?',
        text: "Are you sure you want to mark this item as returned?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, mark it!'
      }).then((result) => {
        if (result.isConfirmed) {
          // In a real application, you would make an AJAX call here
          Swal.fire(
            'Returned!',
            'The item has been marked as returned.',
            'success'
          ).then(() => {
            // Reload the page or update the table via AJAX
            location.reload();
          });
        }
      });
    });

    // Form submission for adding new item
    $('#addItemForm').submit(function(e) {
      e.preventDefault();
      // In a real application, you would make an AJAX call here
      Swal.fire(
        'Success!',
        'New item has been added to inventory.',
        'success'
      ).then(() => {
        $('#addItemModal').modal('hide');
        // Reset form
        this.reset();
        $('#itemImagePreview').attr('src', '../assets/dist/img/blank_image.png');
        // Reload the page or update the table via AJAX
        location.reload();
      });
    });

    // Form submission for editing item
    $('#editItemForm').submit(function(e) {
      e.preventDefault();
      // In a real application, you would make an AJAX call here
      Swal.fire(
        'Success!',
        'Item has been updated.',
        'success'
      ).then(() => {
        $('#editItemModal').modal('hide');
        // Reload the page or update the table via AJAX
        location.reload();
      });
    });

    // Form submission for requesting item
    $('#requestItemForm').submit(function(e) {
      e.preventDefault();
      // In a real application, you would make an AJAX call here
      Swal.fire(
        'Request Submitted!',
        'Your item request has been submitted for approval.',
        'success'
      ).then(() => {
        $('#requestItemModal').modal('hide');
        // Reload the page or update the table via AJAX
        location.reload();
      });
    });
  });
</script>

</body>
</html>