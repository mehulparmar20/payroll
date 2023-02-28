<?php
ob_start();
//session start
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    //include header.php file for header
    include 'admin_header.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Salary Settings</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Salary Settings</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row filter-row">
        <div class="col-sm-6 col-md-4">
          <select id="searchEmployee" onchange="getuserdetails(this.value)" class="form-control">
                <option value="" selected>Select Employee</option>
                <?php
                    $query = "SELECT * FROM employee WHERE admin_id = {$_SESSION['admin_id']} and employee_status = 1 and delete_status = 0 ";
                    $sql = $conn->query($query);
                    $output = '';
                    while($row = $sql->fetch_assoc())
                    {
                        $name = $row['e_firstname'].' '.$row['e_lastname'];
                        $id= $row['e_id'];
                        $output .=' <option value="'.$id.'" >'.$name.'</option>';
                    }
                    echo $output;
                    ?>
                  </select>
        </div>
        <div class="col-md-3 al-status" style="display:none">
            <!--<a href="#" class="btn btn-info btn-sm" onClick="resetform('addAllow')" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#add_allownace"><i class="fa fa-plus" aria-hidden="true"></i> Add Allownace</a>-->
        </div>
        <div class="col-md-3 al-status" style="display:none">
            <!--<a href="#" class="btn btn-info btn-sm" data-toggle="modal" onClick="resetform('addDeduct')" data-backdrop="static" data-keyboard="false" data-target="#add_deduction"><i class="fa fa-plus" aria-hidden="true"></i> Add Deduction</a>-->
        </div>
    </div>
    <!-- /Page Header -->
    <div class="row">
        <div class="col-md-12">
            <div id="view-data">
                
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_allownace" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Edit Allowance/Deductions</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form onsubmit="return false;" id="addAllow">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="ainputType">Type</label>
                  <select id="ainputType" class="form-control">
                    <option value="" selected>Select Allowance</option>
                    <option value="+">Amount</option>
                    <option value="%">Percentgae</option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="ainputAmount">Amount/Percentage</label>
                    <input type="number" class="form-control" id="ainputAmount" placeholder="123">
                    <input type="hidden" id="aeid" > 
                  </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onClick="addEmpAllowence()">Save</button>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="add_deduction" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">Edit basic salary</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-row">
              <div class="form-group col-md-6">
                <label for="dinputAmount">Amount</label>
                <input type="number" class="form-control" id="dinputAmount" placeholder="123">
                <input type="hidden" id="deid" > 
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onClick="addEmpDeduction()" >Save</button>
          </div>
        </div>
      </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let timer;
        function searchUser(e) {
            clearTimeout(timer);
            timer = setTimeout(function(){
                if(e != ""){
                    $.ajax({
                        url: "read.php",
                        type: "POST",
                        data: {
                            query:e,
                            action: "getusers"
                        },
                        cache : false,
                        success: function (data) {
                            $("#users").html(data);
                        }
                    })
                }else{
                    $('.al-status').css("display", "none");
                     $("#view-data").html('');
                }
            }, 800);
        }
        
        function getuserdetails(id){
            if(!id){
                id = $("#searchEmployee").val();
            }
            $.ajax({
                url: "read.php",
                type: "POST",
                data: {
                    id:id,
                    action: "getuservalues"
                },
                cache : false,
                success: function (data) {
                    $("#view-data").html(data);
                    $('.al-status').removeAttr("style");
                }
            })
        }
        
        const resetform = (id) => {
            $(`#${id}`).trigger("reset");
        }
        
        const addEmpAllowence = () => {
            const type = $("#ainputType").val();
            const amount = $("#ainputAmount").val();
            const id = $("#aeid").val();
            if(valid(type, "Select allowence type")){
                if(valid(amount, "Enter allowence amount/percentage")){
                    var eId = document.getElementById("searchEmployee").value;
                    if(valid(eId, "User not selected")){
                        $.ajax({
                            url: "update.php",
                            type: "POST",
                            data: {
                                id: id,
                                eId:eId,
                                type: type,
                                amount: amount,
                                action: "addEmpAllowDedu"
                            },
                            success: function (data) {
                                data = JSON.parse(data);
                                swal(data.message, "", data.status);
                                modalHelper('add_allownace',"hide");
                                getuserdetails(eId)
                            }
                        })
                    }
                }
            }
        }
        
        const getEditValue = (id, col) => {
            if(id != ""){
                $.ajax({
                    url: "read.php",
                    type: "POST",
                    data: {
                        id: id,
                        col: col,
                        action: "getEmpADValue"
                    },
                    cache : false,
                    success: function (data) {
                        data = JSON.parse(data);
                        var t = col+"Type";
                        $("#ainputType").val(data[t]);
                        $("#ainputAmount").val(data[col]);
                        $("#aeid").val(col);
                        modalHelper('add_allownace',"show");
                    }
                })
            }
        }
        
        const addEmpDeduction = () => {
            const amount = $("#dinputAmount").val();
            if(valid(amount, "Enter basic CTC amount")){
                var eId = document.getElementById("searchEmployee").value;
                if(eId != ""){
                    $.ajax({
                        url: "update.php",
                        type: "POST",
                        data: {
                            eId:eId,
                            amount: amount,
                            action: "editBasic"
                        },
                        cache : false,
                        success: function (data) {
                            data = JSON.parse(data);
                            swal(data.message, "", data.status);
                            modalHelper('add_deduction',"hide");
                            getuserdetails(eId)
                        }
                    })
                }
            }
        }
        
        const editBasicValue = (id, val) => {
            if(id != ""){
                $("#dinputAmount").val(val);
                modalHelper('add_deduction',"show");
            } 
                
        }
        
        const valid = (v, m) => {
            if(v == ""){
                swal (m, "", "info");
                return false;
            }else{
                return true;
            }
        }
        
        const modalHelper = (v, s) => {
            if(s == "show"){
                $(`#${v}`).modal('show');
            }else{
                $(`#${v}`).modal('hide');
            }
        }
    </script>
    <?php
} else {
    header("Location:../index.php");
}
?>