<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    ?>
    <div id="deactivate-modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deactivate employee</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form onsubmit ="return false;">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="text-danger">Note: Once an employee is deactivated you can not activate it again.</h5>
<!--                                <p>Note: Once you deactivate employee your </p>-->
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Enter resign date:</label>
                                    <input type="date" id="resign-date" name="resign-date" class="form-control">
                                    <input type="hidden" id="employee-id" name="employee-id" class="form-control">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" name="submit" id="deactivate-employee">Deactivate</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php }