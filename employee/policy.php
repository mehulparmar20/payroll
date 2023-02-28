<?php
include '../dbconfig.php';

$sql = "select * from company policy where admin_id = ".$_POST['admin_id']." and policy_department = ".$_POST['dp_name']." ";
$output = '';
while ($row = mysqli_fetch_array($sql))
{
    $output ='      <div class="card flex-fill">
                        <div class="bg-gradient-info" style=" padding: 0.1rem 1.25rem; margin-bottom: 0;  background-color: rgba(0,0,0,.03);  border-bottom: 1px solid rgba(0,0,0,.125);">
                            <center><h4> Company Policy</h4></center>						
                        </div>
                        <div class="card-body marquee holder" id="marquee" >
                            <ul class="media-list" id="ticker01" >
                                <li class="media" id="noticescroll">
                                    <div class="media-body">
                                        <a href="#">Department:'.$row['policy_department'].'.</a>
                                        <span class="text-dark font-size-sm" style="text-align: right;">(02-12-2019 08:45:52am)</span> <br>
                                        '.$row['policy_description'].'

                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>' ;  
}

echo $output; 

?>

