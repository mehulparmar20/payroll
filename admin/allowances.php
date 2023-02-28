<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Allowances';
    include 'admin_header.php';
    ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title"><?php echo $page; ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active"><?php echo $page; ?></li>
                </ul>
                <div class="col-auto float-right ml-auto">
                    <a href="#" data-toggle="modal" data-target="#add_allowances" onclick="resetForm()" class="btn btn-info btn-sm"><i
                                class="fa fa-plus-circle"></i> Add Allowances</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-12">
            <div id="view_data">

            </div>
        </div>
    </div>


    <?php include 'footer.php'; ?>
    <div id="add_allowances" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title a_title">Add Allowances</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="allowance-add-form">
                        <div class="form-group">
                            <label>Allowances Name<span class="text-danger"></span></label>
                            <input type="text" class="form-control" id="allowances" name="allowances">
                        </div>
                        <div class="form-group">
                            <label>Allowances description<span class="text-danger"></span></label>
                            <textarea name="allowancesDescription" id="allowancesDescription" cols="30" rows="4"></textarea>
                        </div>
						<input type="hidden" id="allowancesId" name="allowancesId">
                        <div class="submit-section">
                        <button class="btn btn-primary submit-btn" type="submit" name="submit" id="submit">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        $( document ).ready(function() {
           getAllowances();
        });
        $(document).on('submit', '#allowance-add-form', function (event) {
            event.preventDefault();
            const allowancesInputField = document.getElementById('allowances').value;
            const allowancesDescriptionInputField = document.getElementById('allowancesDescription').value;
            const allowancesId =  document.getElementById('allowancesId').value;
            
            if (valtext(allowancesInputField, 'allowance name')) {
                if (valtext(allowancesDescriptionInputField, 'allowance description')) {
                    $("#overlay").show();
                    $.ajax({
                        url: "insert.php",
                        type: "POST",
                        data: {
                            name: allowancesInputField,
                            description: allowancesDescriptionInputField,
                            id: allowancesId,
                            action: 'addAllowances'
                        },
                        success: function (data) {
                            $("#overlay").hide();
                            $("#add_allowances").modal("hide");
                            data = JSON.parse(data);
                            swal(data.message,'',data.status);
                            getAllowances();
                        }
                    });
                }
            }
        });

    function resetForm() {
      document.getElementById("allowance-add-form").reset();
    }
        


        function valtext(val, type) {
            if (val == '') {
                swal(`Enter ${type}`,'','info');
                return false;
            } else {
                return true;
            }
        }

    

        function getAllowances(id) {
            var admin_id = $("#admin_id").val();
            $("#overlay").show();
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {id: id, admin_id: admin_id, action: "getAllowances"},
                success: function (data) {
                    if(id){
                        var json_data = JSON.parse(data);
                        $("#allowances").val(json_data.allowances);
                        $("#allowancesDescription").val(json_data.description);
                        $("#allowancesId").val(json_data.id);
                        $("#add_allowances").modal("show");
                        $(".a_title").html("Edit allowance")
                    }else{
                        $("#view_data").html(data);
                        datatable()
                    }
                    $("#overlay").hide();
                }
            });
        }

        
        function datatable() {
            var table = $('#a_data').DataTable({
                scrollY: '400px',
                pageLength: 100
            });
        }
    </script>

    <?php
} else {
    header("Location:../index.php");
}

