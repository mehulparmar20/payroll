</div>
				<!-- /Page Content -->

            </div>
			<!-- /Page Wrapper -->
		 <script src="https://code.jquery.com/jquery-3.3.1.js"></script>	
			<script>
                function salary_auth()
                {
                    var e_id = $("#e_id").val();
                    var password = $("#password").val();
                    
                    $.ajax({
                        url: 'fetch.php',
                        type: 'POST',
                        data: {e_id: e_id, password: password, action: 'salary_auth'},
                        success: function (data)
                        {
                            var data = data.trim();
                            var valid = "true";
                            if (data == valid){
                                window.location.href = 'employee_salary.php';
                            } else{
                                swal("Password Not Match");
                            }
                        }
                    });
                }
            
        
        $(document).ready(function(){
            //alert("Network is Slow.... Please Wait.");
            //$("#announcement").modal('show');
        });

	</script>		
		    <!---Sweet Alert--->
        <script src="app/js/sweet_alert.js"></script>
                <!-- Validation File -->
        <script src="app/js/employee_validation.js"></script>        
                        
		        <!-- jQuery -->
        <script data-cfasync="false" src="app/js/email-decode.min.js"></script>
       
		 <!--<script src="app/js/jquery-3.2.1.min.js"></script>-->
		        <!-- Bootstrap Core JS -->
        <script src="app/js/popper.min.js"></script>
        <script src="app/js/bootstrap.min.js"></script>
		
		    <!-- Slimscroll JS -->
		<script src="app/js/jquery.slimscroll.min.js"></script>
		
		    <!-- DataTables JS -->
		<script src="app/js/datatable/jquery.datatables.min.js" rel="stylesheet"></script>
        <script src="app/js/datatable/datatables.bootstrap4.min.js" ></script>

		<!-- Chart JS -->
		<script src="app/plugins/raphael/raphael.min.js"></script>
		<script src="app/js/chart.js"></script>
        <script src="app/js/modal.js"></script>
		
                <!-- Datetimepicker JS -->
		<script src="app/js/moment.min.js"></script>
                <script src="app/js/bootstrap-datetimepicker.min.js"></script>
		<!-- Custom JS -->
		<script src="app/js/app.js"></script>
                <script src="../assets/js/ajax.js"></script>
				<script src="app/js/custom.js"></script>
		
    </body>
</html>				