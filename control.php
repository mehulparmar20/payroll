<?php
include ("view.php");
//echo "<script>alert('inside control'+".$_POST['action'].");</script>";

// Add New Employee
if($_POST['action']=='addemployee')
{
    $a = new control();
    $a->addemployee();
}

// Add New Employee
if($_POST['action']=='edit_employee')
{
    $a = new control();
    $a->editemployee();
}

// View Employee
if($_POST['action']=='view_employee')
{
    $a = new control();
    $a->viewemployee();
}

// Add New Holiday
if($_POST['action']=='addholiday')
{
    $a = new control();
    $a->addholiday();
}

if($_POST['action']=='adddepartment')
{
    $a = new control();
    $a->adddepartment();
}

// Delete Holiday
if($_POST['action']=='delete_holiday')
{
    $a = new control();
    $a->delete_holiday();
}

// leave data view here
if($_POST['action']=='leave_view')
{
   $a = new control();
   $a->leave_view();
}

// leave insert or delete code here
if($_POST['action']=='leave_insert')
{
   $a = new control();
   $a->leave_insert();
}

// leave insert or delete code here
if($_POST['action']=='leave_edit')
{
   $a = new control();
   $a->leave_edit();
}

// leave data delete here
if($_POST['action']=='leave_delete')
{
   $a = new control();
   $a->leave_delete();
}

// leave data fetch here
if($_POST['action']=='leave_fetch')
{
   $a = new control();
   $a->leave_fetch();
}

// employee request leave here
if($_POST['action']=='request_leave')
{
    $a = new control();
    $a->request_leave();
}

// employee leave request sent
if($_POST['action']=='request_leave_view')
{
    $a = new control();
    $a->request_leave_view();
}

// employee leave request sent
if($_POST['action']=='attendance_view')
{
    $a = new control();
    $a->attendance_view();
}

//add policies
if($_POST['action']=='addpolicies')
{
    $a = new control();
    $a->addpolicies();
}

//add salary
if($_POST['action']=='addsalary')
{
    $a = new control();
    $a->addsalary();
}

//update salary
if ($_POST['action'] == 'update') {
    $a = new control();
    $a->update();
}
//update policies
if ($_POST['action'] == 'pupdate') {
    $a = new control();
    $a->pupdate();
}

//add department_edit
if($_POST['action']=='department_edit')
{
    $a = new control();
    $a->department_edit();
}

if($_POST['action']=='department_add')
{
    $a = new control();
    $a->adddepartment();
}

//add designation
if($_POST['action']=='designation')
{
    $a = new control();
    $a->designation();
}

if($_POST['action']=='edit_designation')
{
    $a = new control();
    $a->edit_designation();
}

if($_POST['action']=='delete_designation')
{
    $a = new control();
    $a->delete_designation();
}

//------ Hardik -------------------------------------------------------------------------------

// add resignation
if ($_POST['action']=='add_resignation')
{
    $a = new control();
    $a->add_resignation();
}

// employee other profile info
if ($_POST['action']=='profile_info')
{
    $a = new control();
    $a->profile_info();
}

// add file function
if ($_POST['action']=='add_files')
{
    $a = new control();
    $a->add_files();
}

// admin add resignation
if ($_POST['action']=='admin_add_resignation')
{
    $a = new control();
    $a->admin_add_resignation();
}

// admin add resignation
if ($_POST['action']=='add_termination')
{
    $a = new control();
    $a->add_termination();
}

// admin add resignation
if ($_POST['action']=='show_termination')
{
    $a = new control();
    $a->show_termination();
}

// delete termination
if($_POST['action']=='delete_termination')
{
    $a = new control();
    $a->delete_termination();
}

if($_POST['action']=='fetch_termination')
{
    $a = new control();
    $a->fetch_termination();
}

if($_POST['action']=='edit_termination')
{
    $a = new control();
    $a->edit_termination();
}

if($_POST['action']=='add_experience')
{
    $a = new control();
    $a->add_experience();
}

if($_POST['action']=='show_experience')
{
    $a = new control();
    $a->show_experience();
}

if($_POST['action']=='fetch_experience')
{
    $a = new control();
    $a->fetch_experience();
}

if($_POST['action']=='edit_experience')
{
    $a = new control();
    $a->edit_experience();
}

if($_POST['action']=='fetch_joining_date')
{
    $a = new control();
    $a->fetch_joining_date();
}

if($_POST['action']=='delete_experience')
{
    $a = new control();
    $a->delete_experience();
}

if($_POST['action']=='fetch_joining_data')
{
    $a = new control();
    $a->fetch_joining_data();
}

if($_POST['action']=='show_joining')
{
    $a = new control();
    $a->show_joining();
}

if($_POST['action']=='add_joining')
{
    $a = new control();
    $a->add_joining();
}

if($_POST['action']=='fetch_joining')
{
    $a = new control();
    $a->fetch_joining();
}

if($_POST['action']=='edit_joining')
{
    $a = new control();
    $a->edit_joining();
}

if($_POST['action']=='delete_joining')
{
    $a = new control();
    $a->delete_joining();
}

if($_POST['action']=='fetch_resignation')
{
    $a = new control();
    $a->fetch_resignation();
}

if($_POST['action']=='edit_resignation')
{
    $a = new control();
    $a->edit_resignations();
}

if($_POST['action']=='delete_resignation')
{
    $a = new control();
    $a->delete_resignation();
}

// -------------master login---------------------

if($_POST['action']=='add_client')
{
    $a = new control();
    $a->add_client();
}

if($_POST['action']=='edit_client')
{
    $a = new control();
    $a->edit_client();
}

if($_POST['action']=='fetch_client')
{
    $a = new control();
    $a->fetch_client();
}

if($_POST['action']=='delete_client')
{
    $a = new control();
    $a->delete_client();
}

if($_POST['action']=='view_client')
{
    $a = new control();
    $a->view_client();
}

if($_POST['action']=='view_all_assign_cards')
{
    $a = new control();
    $a->view_all_assign_cards();
}

if($_POST['action']=='view_all_cards')
{
    $a = new control();
    $a->view_all_cards();
}

if($_POST['action']=='profile1')
{
    $a = new control();
    $a->profile1();
}

if($_POST['action']=='profile2')
{
    $a = new control();
    $a->profile2();
}

if($_POST['action']=='profile3')
{
    $a = new control();
    $a->profile3();
}

if($_POST['action']=='company_profile')
{
    $a = new control();
    $a->company_profile();
}

if($_POST['action']=='edit_company_profile')
{
    $a = new control();
    $a->edit_company_profile();
}

if($_POST['action']=='add_company_time')
{
    $a = new control();
    $a->add_company_time();
}

if($_POST['action']=='view_company_time')
{
    $a = new control();
    $a->view_company_time();
}

// Register Page
if($_POST['action']=='register_user')
{
   $a = new control();
   $a->register_user();
}
// Edit Holiday
if($_POST['action']=='edit_data')
{
   $a = new control();
   $a->edit_data();
}
//change_password
if($_POST['action']=='change_password')
{
   $a = new control();
   $a->change_password();
}

// add worl=king days
if($_POST['action']=='add_working_days')
{
   $a = new control();
   $a->add_working_days();
}
