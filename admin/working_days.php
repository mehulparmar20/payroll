<?php
ob_start();
include '../dbconfig.php';
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    include'admin_header.php';
    ?>			
    <!-- Page Content -->
    <!-- Page Content -->
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Working Days</h3>
                        </div>
                    </div>
                </div>
                
                <form id="insert_working_days">
                    <div class="row">
                        <label class="col-lg-4 col-form-label"><b>Company Working Days</b></label>
                        <table class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Sunday</th>
                                    <th class="text-center">Monday</th>
                                    <th class="text-center">Tuesday</th>
                                    <th class="text-center">Wednesday</th>
                                    <th class="text-center">Thursday</th>
                                    <th class="text-center">Friday</th>
                                    <th class="text-center">Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Working Days</td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="sun" name="sun" class="check">
                                        <label for="sun" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="mon" name="mon" class="check">
                                        <label for="mon" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="tue" name="tue" class="check">
                                        <label for="tue" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="wed" name="wed" class="check">
                                        <label for="wed" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="thu" name="thu" class="check">
                                        <label for="thu" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="fri" name="fri" class="check">
                                        <label for="fri" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="status-toggle">
                                        <input type="checkbox" id="sat" name="sat" class="check">
                                        <label for="sat" class="checktoggle">checkbox</label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-6 col-md-6 col-lg-6">
                            <?php 
                                $com_ben = mysqli_query($conn, "select * from company_benefits where admin_id = ".$_SESSION['admin_id']." ");

                                $row = mysqli_fetch_array($com_ben);
                            ?>
                            <div class="form-group">
                                <label class="col-form-label">Monthly Allow Leave</label>
                                <input class="form-control" value="<?php echo $row['allow_leave']; ?>" id="allow_leave" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <input type="submit" class="btn btn-primary submit-btn" id="insert" name="insert">	
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <?php
    include 'footer.php';
}
?>
<script>
    $(document).ready(function () {
        view_working_days();
        function view_working_days()
        {
            var admin_id = $('#admin_id').val();
            $.ajax({
                url: 'read.php',
                method: 'POST',
                data: {admin_id: admin_id,
                    dataType: 'json',
                    action: 'view_working_days'},
                success: function (data) {
                    var j = JSON.parse(data);
                    if (j.sun == 1)
                    {
                        // $('#sun').is(':checked');
                        document.getElementById("sun").checked = true;
                    }
                    if (j.mon == 1)
                    {
                        // $('#mon').is(':checked');
                        document.getElementById("mon").checked = true;
                    }
                    if (j.tue == 1)
                    {
                        // $('#tue').is(':checked');
                        document.getElementById("tue").checked = true;
                    }
                    if (j.wed == 1)
                    {
                        // $('#wed').is(':checked');
                        document.getElementById("wed").checked = true;
                    }
                    if (j.thu == 1)
                    {
                        // $('#thu').is(':checked');
                        document.getElementById("thu").checked = true;
                    }
                    if (j.fri == 1)
                    {
                        // $('#fri').is(':checked');
                        document.getElementById("fri").checked = true;
                    }
                    if (j.sat == 1)
                    {
                        // $('#sat').is(':checked');
                        document.getElementById("sat").checked = true;
                    }
                }
            });
        }

        $('#insert_working_days').on('submit', function (event) {
            event.preventDefault();
            var admin_id = $('#admin_id').val();
            var allow_leave = $('#allow_leave').val();
            
            if ($('#sun').is(':checked')) {
                var sun = 1;
            } else {
                var sun = 0;
            }
            if ($('#mon').is(':checked')) {
                var mon = 1;
            } else {
                var mon = 0;
            }
            if ($('#tue').is(':checked')) {
                var tue = 1;
            } else {
                var tue = 0;
            }
            if ($('#wed').is(':checked')) {
                var wed = 1;
            } else {
                var wed = 0;
            }
            if ($('#thu').is(':checked')) {
                var thu = 1;
            } else {
                var thu = 0;
            }
            if ($('#fri').is(':checked')) {
                var fri = 1;
            } else {
                var fri = 0;
            }
            if ($('#sat').is(':checked')) {
                var sat = 1;
            } else {
                var sat = 0;
            }
            $.ajax({
                url: '../control.php',
                method: 'POST',
                data: {
                    sun: sun,
                    mon: mon,
                    tue: tue,
                    wed: wed,
                    thu: thu,
                    fri: fri,
                    sat: sat,
                    admin_id: admin_id,
                    allow_leave: allow_leave,
                    action: 'add_working_days'
                },
                success: function (data) {
                    $('#insert_working_days')[0].reset();
                    swal("Data Added Successfully!", "success");
                    window.location.href ='working_days.php';
                }
            });
        });
    });
</script>

