<?php
ob_start();
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Deduction';
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
                    <a href="#" data-toggle="modal" data-target="#add_deduction" onclick="resetForm()" class="btn btn-info btn-sm"><i
                                class="fa fa-plus-circle"></i> Add Deduction</a>
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
    <div id="add_deduction" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title a_title">Add Deduction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deduction-add-form">
                        <div class="form-group">
                            <label>Deduction Name<span class="text-danger"></span></label>
                            <input type="text" class="form-control" id="deduction" name="allowances">
                        </div>
                        <div class="form-group">
                            <label>Deduction description<span class="text-danger"></span></label>
                            <textarea name="allowancesDescription" id="deductionDescription" cols="30" rows="4"></textarea>
                        </div>
						<input type="hidden" id="deductionId" name="deductionId">
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
           getDeductions();
        });
        $(document).on('submit', '#deduction-add-form', function (event) {
            event.preventDefault();
            const deductionName = document.getElementById('deduction').value;
            const deductionDescription = document.getElementById('deductionDescription').value;
            const deductionId =  document.getElementById('deductionId').value;
            
            if (valtext(deductionName, 'allowance name')) {
                if (valtext(deductionDescription, 'allowance description')) {
                    $("#overlay").show();
                    $.ajax({
                        url: "insert.php",
                        type: "POST",
                        data: {
                            name: deductionName,
                            description: deductionDescription,
                            id: deductionId,
                            action: 'addDeduction'
                        },
                        success: function (data) {
                            $("#overlay").hide();
                            $("#add_deduction").modal("hide");
                            data = JSON.parse(data);
                            swal(data.message,'',data.status);
                            getDeductions();
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

    

        function getDeductions(id) {
            var admin_id = $("#admin_id").val();
            $("#overlay").show();
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {id: id, admin_id: admin_id, action: "getDeductions"},
                success: function (data) {
                    if(id){
                        var json_data = JSON.parse(data);
                        $("#deduction").val(json_data.deductions);
                        $("#deductionDescription").val(json_data.description);
                        $("#deductionId").val(json_data.id);
                        $("#add_deduction").modal("show");
                        $(".a_title").html("Edit deduction")
                    }else{
                        $("#view_data").html(data);
                        datatable()
                    }
                    $("#overlay").hide();
                }
            });
        }

        
        function datatable() {
            var table = $('#d_data').DataTable({
                scrollY: '400px',
                pageLength: 100
            });
        }
    </script>

    <?php
} else {
    header("Location:../index.php");
}

