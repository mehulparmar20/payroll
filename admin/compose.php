<?php
session_start();
if ($_SESSION['admin'] == 'yes') {
    include '../dbconfig.php';
    include'admin_header.php';
    ?>			
    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Compose</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Compose</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" id="to" placeholder="To" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="text" id="subject" placeholder="Subject" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea rows="4" id="message" class="form-control" placeholder="Enter your message here"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div class="text-center">
                                <button class="btn btn-success" id="insert" onclick="send_mail()" type="submit"><span>Send</span> <i class="fa fa-send m-l-5"></i></button>
                                <button class="btn btn-primary m-l-5" type="reset"><span>Clear</span> <i class="fa fa-trash-o m-l-5"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Content -->

    <?php include 'footer.php'; ?>
    
<script>
        function send_mail() {
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var admin_id = $('#admin_id').val();
            if(val_to(to))
            {
                if(val_subject(subject))
                    {
                        if(val_message(message))
                        {
                            $('.wrap-loader').show();
                            $.ajax({
                                url:'action.php',
                                method:'POST',
                                data:{to:to,
                                    subject:subject,
                                    message:message,
                                    admin_id:admin_id,
                                    action:'email_send'
                                    },
                                success: function (data){
                                    $('.wrap-loader').hide();
                                    swal({title:'Email Sent Successfully.'});
                                        window.location.href = 'compose.php';
                                    }
                            });
                        }
                        
                    }
                }
            }
            
            
            function val_to(val)
            {
                if (val == '') {
                swal({title:'Please Enter Email'});
                return false;
                } else if (val.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
                    swal({title:'Please Enter valid Email'});
                    return false;
                } else {
                    return true;
                }
            }
            function val_subject(val)
            {
                if (val == '') {
                    swal({title:'Please Enter Subject'});
                return false;
                } else {
                    return true;
                }
            }
            function val_message(val)
            {
                if (val == '') {
                    swal({title:'Please Enter Message'});
                return false;
                } else {
                    return true;
                }
            }
        
    
</script>
<?php
}else{
header("location:../index.php");
}

