
<!-- Add Department Modal -->
    <div id="add_department" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Department</h5>
                    <button type="button" class="close modalDepartment"  aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Department Name <span class="text-danger">*</span></label>
                        <input id="departments_name" name="departments_name" class="form-control" type="text">
                    </div>
                    <div class="submit-section">
                        <button type="submit" id="submit" name="submit" onclick="add_department()" class="btn btn-primary submit-btn"> Add </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Department Modal  -->
<style>
    .modal.fade {
        background: rgba(0, 0, 0, 0.42);
    }
</style>