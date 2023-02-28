<?php
session_start();
if ($_SESSION['employee'] == 'yes') {
    include '../dbconfig.php';
    include'emp_header.php';
    ?>			
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title">Break Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                    <li class="breadcrumb-item active">Break Details</li>
                </ul>
            </div>
            <?php if($portalPunch == 1){ ?>
            <div class="col-auto float-right ml-auto">
                <a href="#" class="btn add-btn" id="addTimePunch" onclick="add_break()" ><i class="fa fa-plus"></i> Add Break</a>
            </div>
            <?php } ?>
        </div>
    </div>
    <!-- /Page Header -->

    <!-- Search Filter -->
    <div class="row filter-row">
        <div class="col-sm-6 col-md-3"> 
            <div class="form-group">
                <input type="date" onchange="new_change()" value="<?php echo date("Y-m-d"); ?>" id="break_date" class="form-control"> 
                <input type="hidden"  value="<?php echo date("m/d/Y"); ?>" id="c_date" class="form-control"> 
            </div>
        </div>
        <div class="col-sm-6 col-md-4"> 
            <div class="form-group">
                <label class="form-control"><b>Break Info Date : </b><b><span id="date_view" style="color: black;font-size: 18px"></span></b></label>
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
        


    <?php include 'footer.php';
 ?>
<script type="text/javascript">
   $(document).ready(function(){
         c_change();
    });
   
        function c_change(){
            var day = $("#c_date").val();
            var admin_id = $("#admin_id").val();
            var e_id = $("#e_id").val();
            $("#date_view").html(day);
            $("#break_date").val(day);
            $("#overlay").show();
            $.ajax({
                url:"break_info_code.php",
                type:"POST",
                data:{e_id:e_id,day:day,admin_id:admin_id,action:'break_info'},
                success:function(data)
                {
                    $("#overlay").hide();
                    $('#view_data').html(data);
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                    datatable();
                }
            });
        }
       function new_change(){
           var day = $("#break_date").val();
           var e_id = $("#e_id").val();
           var admin_id = $("#admin_id").val();
           $("#date_view").html(day);
           $("#break_date").val(day);
           $("#overlay").show();
           $.ajax({
               url:"break_info_code.php",
               type:"POST",
               data:{e_id:e_id,day:day,admin_id:admin_id,action:'break_info'},
               success:function(data)
               {
                   $("#overlay").hide();
                   $('#view_data').html(data);
                   $(function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    });
                   datatable();
               }
           });
        }
        $(document).on("click", '#addTimePunch', function (event) {
             event.preventDefault();
             var card_id = $("#card_id").val();
             $.ajax({
               url:"../file.php",
               type:"POST",
               data:{id:card_id},
               success:function(data)
               {
                data =  JSON.parse(data);
                  swal(data.message, "", 'success');
                  c_change();
                  
               }
           });
        });
        
        function datatable()
        {
                var table = $('#attendance').DataTable( {
                    
                    scrollY:        400,
                    scrollX:        true,
                    scrollCollapse: true,
                    scroller:       true,
                    fixedColumns:   true,
                } );
 
                new $.fn.dataTable.FixedColumns( table );
               
        }
</script>

<?php 
}
else
{
    header("Location:../login.php");
}
