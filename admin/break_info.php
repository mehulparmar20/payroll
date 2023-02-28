<?php

session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Break Details';
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
            </div>
        </div>
    </div>

    <div id="hiddiv"></div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <style>
            .DTFC_LeftBodyLiner {
                overflow-x: hidden;
            }
        </style>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <input type="date" onchange="new_change()" id="break_date" class="form-control">
                <input type="hidden" value="<?php echo date("m/d/Y"); ?>" id="c_date" class="form-control">
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="form-group">
                <label class="form-control"><b>Break Info Date : </b><b><span id="date_view"
                                                                              style="color: black;font-size: 18px"></span></b></label>
            </div>
        </div>
        <divclass="col-sm-6 col-md-4">
            <div class="form-group">
                <i class="fa fa-refresh" onclick="new_change()" info="Refresh" style="margin-top: -1px; font-size: 24px; border: 1px solid; border-radius: 25px; background: white; padding: 10px; cursor: pointer;" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <!-- /Search Filter -->

    <div class="row">
        <div class="col-md-12">
            <div id="view_data">

            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <div id="edit_break" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Break</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Name <span class=" text-danger"
                                                                         style="font-size:20px">*</span></label>
                                <input class="form-control" id="name" disabled name="name" type="text">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Break IN <span class=" text-danger"
                                                                             style="font-size:20px">*</span></label>
                                <div><input class="form-control" id="break_in" disabled type="text"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Break OUT <span class=" text-danger"
                                                                              style="font-size:20px">*</span></label>
                                <div><input class="form-control" id="break_out" disabled type="text"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Employee Card No </label>
                                <input class="form-control" disabled id="emp_cardno" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Total Break Time<span class=" text-danger"
                                                                                    style="font-size:20px">*</span></label>
                                <input class="form-control" disabled id="break_time" name="break_time" type="text">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Violation Fine<span class=" text-danger"
                                                                                  style="font-size:20px">*</span></label>
                                <input class="form-control" id="fine" name="fine" type="text">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Comment<span class=" text-danger"
                                                                           style="font-size:20px">*</span></label>
                                <input class="form-control" id="comment" name="comment" type="text">
                                <input class="form-control" id="b_id" type="hidden">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <input type="submit" class="btn btn-primary submit-btn" value="Save" onclick="break_update()">
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete Employee Modal -->
    <div class="modal custom-modal fade" id="delete_break" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3>Delete Break</h3>
                        <p>Are you sure want to delete?</p>
                    </div>
                    <div class="modal-btn delete-action">
                        <div class="row">
                            <div class="col-6">
                                <a href="javascript:void(0);" onclick="del_break()"
                                   class="btn btn-primary continue-btn">Delete</a>
                                <input type="hidden" value="" id="del_val">
                            </div>
                            <div class="col-6">
                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Employee Modal -->
    <script type="text/javascript">
        $(document).ready(function () {
            // Session upadte
            setInterval(function () {
                $('#hiddiv').load('timeout.php');
            }, 100000);
            c_change();
        });
        // Break delete
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            swal({
              title: "Are you sure?",
              text: "Once deleted, you will not be able to recover.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#overlay").show();
                $.ajax({
                    url: 'delete.php',
                    type: 'post',
                    data: {b_id: id, action: 'break_delete'},
                    success: function (data) {
                        $("#delete_break").modal("hide");
                        $("#overlay").hide();
                        swal("Break Delete successfully","",'success');
                        makeDecision(id);
                    }
                });
              }
            });
        });

        function del_break() {
            var id = $("#del_val").val();
            
        }

        //edit function
        $(document).on('click', '.edit', function () {
            var id = $(this).attr("id");
            $("#overlay").show();
            $.ajax({
                url: 'read.php',
                type: 'POST',
                data: {
                    id: id, action: 'fetch_break'
                },
                success: function (arr) {
                    var data = JSON.parse(arr);
                    var break_in = new Date(data.break_time * 1000);
                    var break_time = new Date(data.out_time * 1000);
                    var datein = break_in.toGMTString();
                    var dateout = break_time.toGMTString();
                    $("#name").val(data.emp_name);
                    $("#emp_cardno").val(data.employee_cardno);
                    $("#break_in").val(datein);
                    $("#break_out").val(dateout);
                    $("#break_time").val(data.total_time);
                    $("#fine").val(data.fine);
                    $("#comment").val(data.comment);
                    $("#b_id").val(data.b_id);
                    $("#edit_break").modal("show");
                    $("#overlay").hide();
                }
            });
        });
        function emptyfield() {
            $("#name").val('');
            $("#emp_cardno").val('');
            $("#break_in").val('');
            $("#break_out").val('');
            $("#break_time").val('');
            $("#fine").val('');
            $("#comment").val('');
            $("#b_id").val('');
        }
        function break_update() {
            var break_time = $("#break_time").val();
            var fine = $("#fine").val();
            var comment = $("#comment").val();
            var b_id = $("#b_id").val();

            $.ajax({
                url: "update.php",
                type: "POST",
                data: {
                    break_time: break_time,
                    fine: fine,
                    comment: comment,
                    b_id: b_id,
                    action: 'break_edit'
                },
                success: function (data) {
                    $("#edit_break").modal("hide");
                    swal(data.trim(),"",'success');
                    var day = $("#break_date").val();
                    if (day == '') {
                        c_change();
                    } else {
                        new_change();
                    }
                }
            });
        }


        function c_change() {
            var day = $("#c_date").val();
            var admin_id = $("#admin_id").val();
            $("#date_view").html(day);
            $("#break_date").val(day);
            $("#overlay").show();
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {day: day, admin_id: admin_id, action: 'break_info'},
                success: function (data) {
                    $('#view_data').html(data);
                    datatable();
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                    $("#overlay").hide();
                }
            });
        }

        function new_change() {
            var day = $("#break_date").val();
            var admin_id = $("#admin_id").val();
            $("#date_view").html(day);
            $("#break_date").val(day);
            $("#overlay").show();
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {day: day, admin_id: admin_id, action: 'break_info'},
                success: function (data) {
                    $('#view_data').html(data);
                    datatable();
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                    $("#overlay").hide();
                }
            });
        }
        
        function makeDecision(id){
            var table = document.getElementById('attendance');
            var table2 = document.getElementsByClassName('DTFC_Cloned');
            for (var j = 0; j < table.rows.length; j++) {
                var value = table.rows[j].cells[0].getAttribute("data-id");
                if (typeof value !== "undefined") {
                    if (value == `row-${id}`) {
                        table.deleteRow(j);
                        table2.deleteRow(j);
                        break;
                    }
                }
            }
        }

        function datatable() {
            var table = $('#attendance').DataTable({
                scrollY: 400,
                scrollX: true,
                scrollCollapse: true,
                scroller: true,
                pageLength: 100,
                // fixedColumns: true,
            });
        }
    </script>

    <?php
} else {
    header("Location:../login.php");
}
