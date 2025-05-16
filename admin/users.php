<?php
$pageTitle = 'Users';
include 'includes/head.php';
?>

<div class="card mb-4">
  <div class="card-body">
    <div class="row d-flex justify-content-between align-items-center mb-3">
      <div class="col-auto">
        <h2 class="text-dark mb-0">Users</h2>
      </div>
      <div class="col-auto">
        <a href="user-add.php" class="btn btn-danger">Add User</a>
      </div>
    </div>
    <hr>
    <table id="myTable" class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Id</th>
          <th>Email</th>
          <th>Role</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT userId, email, userType, status FROM user";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['userId']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['userType']) . '</td>';
            echo '<td>' . htmlspecialchars($row['status']) . '</td>';
            echo '<td>';
            echo '<a href="user-edit.php?id=' . urlencode($row['userId']) . '" class="btn btn-sm btn-success mx-1">Edit</a>';
            // Delete button triggers modal
            echo '<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . htmlspecialchars($row['userId']) . '">Delete</button>';

            // Modal for password confirmation
            echo '
                        <div class="modal fade" id="deleteModal' . htmlspecialchars($row['userId']) . '" tabindex="-1" aria-labelledby="deleteModalLabel' . htmlspecialchars($row['userId']) . '" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <form method="post" action="user-delete.php?id=' . urlencode($row['userId']) . '" autocomplete="off">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="deleteModalLabel' . htmlspecialchars($row['userId']) . '">Confirm Delete</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <p>Enter your admin password to delete this user:</p>
                                  <input type="hidden" name="id" value="' . htmlspecialchars($row['userId']) . '">
                                  <div class="mb-3">
                                    <input type="password" name="admin_password" class="form-control" placeholder="Admin Password" required>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                  <button type="submit"  name="btnUserDelete" class="btn btn-danger">Delete</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        ';
            echo '</td>';
            echo '</tr>';
          }
        } else {
          echo '<tr><td colspan="5" class="text-center">No users found.</td></tr>';
        }

        $conn->close();
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'includes/foot.php'; ?>

<!-- Add these scripts before the closing </body> tag if not already present -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>