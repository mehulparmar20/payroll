<?php
ob_start();
session_start();
include_once ('../dbconfig.php');
?>
<!-- Add Designation Modal -->
<div id="add_designation" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Designation</h5>
                <button type="button" class="close modalDesignation"  aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--                    <form id="insert_form">-->
                <div class="form-group">
                    <label>Designation Name <span class="text-danger">*</span></label>
                    <input class="form-control" id="designation_name" type="text">
                </div>
                <div class="form-group">
                    <label>Department <span class="text-danger">*</span></label>

                        <select id="department" class="form-control">
                            <option value="no">Select Department</option>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM departments where admin_id = " . $_SESSION['admin_id'] . " ");
                            while ($row = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option value="<?php echo $row['departments_id'].",".$row['used_count']; ?>"><?php echo $row['departments_name']; ?></option>
                            <?php } ?>
                        </select>
                </div>
                <div class="submit-section">
                    <input type="submit" onclick="add_des()" class="btn btn-primary submit-btn" name="submit" id="submit" value="Submit">
                </div>
                <!--                    </form>-->
            </div>
        </div>
    </div>
</div>
<!-- /Add Designation Modal -->
<style>
    .modal.fade {
        background: rgba(0, 0, 0, 0.42);
    }
</style>
