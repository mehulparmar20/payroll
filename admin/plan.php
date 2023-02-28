<?php
ob_start();
session_start();
if ($_SESSION['admin'] == 'yes') {
    $page = 'Subscriptions';
    include'admin_header.php';
    ?>

<!-- Page Header -->
<div class="page-header">

    <div class="row">
        <div class="col-sm-8 col-4">
            <h3 class="page-title">Subscriptions</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Subscriptions</li>
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="#" class="btn btn-info btn-sm" id="manage-subscription"><i class="fa fa-cog"></i> Manage Card</a>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="#" class="btn btn-info btn-sm" data-toggle="modal" id="buy_cards-btn"><i
                        class="fa fa-plus-circle"></i> Buy Employee card</a>
        </div>
    </div>
</div>
<!-- /Page Header -->
    <div class="row">
        <div class="col-md-4 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Current Plan</b></span>
                <h3 style="color: #0d1837"><b>Advance</b></h3>
                <span style="color: black" id="renew-date" ></span>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Total Employee</b></span>
                <h3 style="color: black" id="employee-total-counter"></h3>
            </div>
        </div>
        <div class="col-md-4 d-flex">
            <div class="stats-info flex-fill">
                <span style="color: black"><b>Remaining Employee</b></span>
                <h3 style="color: black" id="employee-remaining-counter"></h3>
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card card-table mb-0">
            <div class="card-body">
                <div id="payment-history">

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Plan Details -->
    
<?php include 'footer.php'; ?>


    <!-- buy extra cards modal -->
    <div class="plan-container"></div>
    <!-- buy extra cards modal -->
    <script>
        fatchInvoice();
        employeereaming();
    </script>
<?php 
}else{
    header("location:../index.php");
}
?>