<?php
session_start();
if(isset($_SESSION['substatus'])){
    header("location:plan.php");
}
if ($_SESSION['admin'] == 'yes') {
    $page = 'Manage Shift';
    include'admin_header.php';
    ?>
    <!-- Page Content -->
    <div class="content container-fluid">
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        
        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
    </style>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title"><?php echo $page; ?></h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active"><?php echo $page; ?></li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_shift"><i
                            class="fa fa-plus"></i> Add Shift</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="company_table">

            </div>
        </div>
        <!-- /Page Header -->
    <?php include 'footer.php'; ?>
    <!-- Add company shift Modal -->
    <div id="add_shift" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Shift No <span class="text-danger">*</span></label>
                        <select id="shift_no" class="custom-select" >
                            <option value="">Select Shift</option>
                            <option value="Shift 1">Shift 1</option>
                            <option value="Shift 2">Shift 2</option>
                            <option value="Shift 3">Shift 3</option>
                            <option value="Shift 4">Shift 4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Company Start Time <span class="text-danger">*</span></label>
                        <input class="form-control" id="start_time" type="time">
                    </div>
                    <div class="form-group">
                        <label>Company Start Time <span class="text-danger">*</span></label>
                        <input class="form-control" id="end_time" type="time">
                    </div>
                    <div class="form-group">
                        <label>Company Break Time(in Minute) <span class="text-danger">*</span></label>
                        <input class="form-control" id="break_time" type="number">
                    </div>
                    <div class="form-group">
                        <label>Company Break Fine(in Ruppes) <span class="text-danger">*</span></label>
                        <input class="form-control" id="break_fine" type="number">
                    </div>
                    <div class="form-group">
                        <label>Company Late Fine(in Ruppes) <span class="text-danger">*</span></label>
                        <input class="form-control" id="late_fine" type="number">
                    </div>
                    <div class="form-group">
                        <label>Time Zone <span class="text-danger">*</span></label>
                        <select id="timezone" class="custom-select">
                            <option value="">Select Your TimeZone</option>
                            <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                            <option value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>
                            <option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
                            <option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
                            <option value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>
                            <option value="America/Anchorage">(GMT-09:00) Alaska</option>
                            <option value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>
                            <option value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
                            <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                            <option value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>
                            <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                            <option value="America/Dawson_Creek">(GMT-07:00) Arizona</option>
                            <option value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>
                            <option value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                            <option value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>
                            <option value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>
                            <option value="America/New_York">(GMT-05:00) Eastern Time (US & Canada)</option>
                            <option value="America/Havana">(GMT-05:00) Cuba</option>
                            <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                            <option value="America/Caracas">(GMT-04:30) Caracas</option>
                            <option value="America/Santiago">(GMT-04:00) Santiago</option>
                            <option value="America/La_Paz">(GMT-04:00) La Paz</option>
                            <option value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>
                            <option value="America/Campo_Grande">(GMT-04:00) Brazil</option>
                            <option value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>
                            <option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
                            <option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
                            <option value="America/Araguaina">(GMT-03:00) UTC-3</option>
                            <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                            <option value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>
                            <option value="America/Godthab">(GMT-03:00) Greenland</option>
                            <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
                            <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                            <option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
                            <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                            <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                            <option value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>
                            <option value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>
                            <option value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>
                            <option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
                            <option value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>
                            <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                            <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                            <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                            <option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
                            <option value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
                            <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                            <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                            <option value="Asia/Gaza">(GMT+02:00) Gaza</option>
                            <option value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>
                            <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                            <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                            <option value="Asia/Damascus">(GMT+02:00) Syria</option>
                            <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                            <option value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
                            <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                            <option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
                            <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                            <option value="Asia/Kabul">(GMT+04:30) Kabul</option>
                            <option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
                            <option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
                            <option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                            <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                            <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                            <option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
                            <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                            <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                            <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                            <option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                            <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                            <option value="Australia/Perth">(GMT+08:00) Perth</option>
                            <option value="Australia/Eucla">(GMT+08:45) Eucla</option>
                            <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                            <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                            <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                            <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                            <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                            <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                            <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                            <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                            <option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
                            <option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
                            <option value="Asia/Magadan">(GMT+11:00) Magadan</option>
                            <option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
                            <option value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>
                            <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                            <option value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                            <option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
                            <option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
                            <option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>
                        </select>
                    </div>
                    <div class="submit-section">
                        <input type="submit" onclick="add_shift()" class="btn btn-primary submit-btn" name="submit" id="submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add company Shift Modal -->
    <!-- Edit company shift Modal -->
    <div id="edit_shift" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Designation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Shift No <span class="text-danger">*</span></label>
                        <select id="ushift_no" class="custom-select" >
                            <option value="">Select Shift</option>
                            <option value="Shift 1">Shift 1</option>
                            <option value="Shift 2">Shift 2</option>
                            <option value="Shift 3">Shift 3</option>
                            <option value="Shift 4">Shift 4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Company Start Time <span class="text-danger">*</span></label>
                        <input class="form-control" id="ustart_time" type="time">
                        <input class="form-control" id="time_id" type="hidden">
                    </div>
                    <div class="form-group">
                        <label>Company Start Time <span class="text-danger">*</span></label>
                        <input class="form-control" id="uend_time" type="time">
                    </div>
                    <div class="form-group">
                        <label>Company Break Time(in Minute) <span class="text-danger">*</span></label>
                        <input class="form-control" id="ubreak_time" type="number">
                    </div>
                    <div class="form-group">
                        <label>Company Late Fine(in Ruppes) <span class="text-danger">*</span></label>
                        <input class="form-control" id="ulate_fine" type="number">
                    </div>
                    <div class="form-group">
                        <label>Company Break Fine(in Ruppes) <span class="text-danger">*</span></label>
                        <input class="form-control" id="ubreak_fine" type="number">
                    </div>
                    <div class="form-group">
                        <label>Time Zone <span class="text-danger">*</span></label>
                        <select id="utimezone" class="custom-select">
                            <option value="">Select Your TimeZone</option>
                            <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                            <option value="America/Adak">(GMT-10:00) Hawaii-Aleutian</option>
                            <option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
                            <option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
                            <option value="Pacific/Gambier">(GMT-09:00) Gambier Islands</option>
                            <option value="America/Anchorage">(GMT-09:00) Alaska</option>
                            <option value="America/Ensenada">(GMT-08:00) Tijuana, Baja California</option>
                            <option value="Etc/GMT+8">(GMT-08:00) Pitcairn Islands</option>
                            <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                            <option value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>
                            <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                            <option value="America/Dawson_Creek">(GMT-07:00) Arizona</option>
                            <option value="America/Belize">(GMT-06:00) Saskatchewan, Central America</option>
                            <option value="America/Cancun">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                            <option value="Chile/EasterIsland">(GMT-06:00) Easter Island</option>
                            <option value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>
                            <option value="America/New_York">(GMT-05:00) Eastern Time (US & Canada)</option>
                            <option value="America/Havana">(GMT-05:00) Cuba</option>
                            <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                            <option value="America/Caracas">(GMT-04:30) Caracas</option>
                            <option value="America/Santiago">(GMT-04:00) Santiago</option>
                            <option value="America/La_Paz">(GMT-04:00) La Paz</option>
                            <option value="Atlantic/Stanley">(GMT-04:00) Faukland Islands</option>
                            <option value="America/Campo_Grande">(GMT-04:00) Brazil</option>
                            <option value="America/Goose_Bay">(GMT-04:00) Atlantic Time (Goose Bay)</option>
                            <option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
                            <option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
                            <option value="America/Araguaina">(GMT-03:00) UTC-3</option>
                            <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                            <option value="America/Miquelon">(GMT-03:00) Miquelon, St. Pierre</option>
                            <option value="America/Godthab">(GMT-03:00) Greenland</option>
                            <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
                            <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                            <option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
                            <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                            <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                            <option value="Europe/Belfast">(GMT) Greenwich Mean Time : Belfast</option>
                            <option value="Europe/Dublin">(GMT) Greenwich Mean Time : Dublin</option>
                            <option value="Europe/Lisbon">(GMT) Greenwich Mean Time : Lisbon</option>
                            <option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
                            <option value="Africa/Abidjan">(GMT) Monrovia, Reykjavik</option>
                            <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                            <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                            <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                            <option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
                            <option value="Africa/Windhoek">(GMT+01:00) Windhoek</option>
                            <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                            <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                            <option value="Asia/Gaza">(GMT+02:00) Gaza</option>
                            <option value="Africa/Blantyre">(GMT+02:00) Harare, Pretoria</option>
                            <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                            <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                            <option value="Asia/Damascus">(GMT+02:00) Syria</option>
                            <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                            <option value="Africa/Addis_Ababa">(GMT+03:00) Nairobi</option>
                            <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                            <option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
                            <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                            <option value="Asia/Kabul">(GMT+04:30) Kabul</option>
                            <option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
                            <option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
                            <option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                            <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                            <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                            <option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
                            <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                            <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                            <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                            <option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                            <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                            <option value="Australia/Perth">(GMT+08:00) Perth</option>
                            <option value="Australia/Eucla">(GMT+08:45) Eucla</option>
                            <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                            <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                            <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                            <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                            <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                            <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                            <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                            <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                            <option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
                            <option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
                            <option value="Asia/Magadan">(GMT+11:00) Magadan</option>
                            <option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
                            <option value="Asia/Anadyr">(GMT+12:00) Anadyr, Kamchatka</option>
                            <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                            <option value="Etc/GMT-12">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                            <option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
                            <option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
                            <option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>
                        </select>
                    </div>
                    <div class="submit-section">
                        <input type="submit" onclick="edit_shift()" class="btn btn-primary submit-btn" name="submit" id="submit" value="Submit">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit company Shift Modal -->
<script>

    $(document).ready(function () {
        view_time();
    });

    function view_time()
    {
        var admin_id = $('#admin_id').val();
        $.ajax({
            url: 'read.php',
            method: 'POST',
            data: {
                admin_id: admin_id,
                action: 'view_company_time'
            },
            success: function (data) {
                var get_data = JSON.parse(data);
                var table = '<table id="company_time" class="table table-striped custom-table mb-0">' +
                    '                        <thead>' +
                    '                            <tr>' +
                    '                                <th>No</th>' +
                    '                                <th><center>Shift No</center></th>' +
                    '                                <th><center>Company Start Time</center></th>' +
                    '                                <th><center>Company End Time</center></th>' +
                    '                                <th><center>Company Break Time</center></th>' +
                    '                                <th><center>Break Fine</center></th>' +
                    '                                <th><center>Late Fine</center></th>' +
                    '                                <th class="text-right">Action</th>' +
                    '                            </tr>' +
                    '                        </thead>' +
                    '                        <tbody>';

                for (var i = 0; i < get_data.length; i++) {
                    var id = get_data[i].time_id;
                    var shift_no = get_data[i].shift_no;
                    var start_time = get_data[i].company_in_time;
                    var end_time = get_data[i].company_out_time;
                    var break_time = get_data[i].company_break_time;
                    var counter_used = get_data[i].counter_used;
                    var late_fine = get_data[i].late_fine;
                    var break_fine = get_data[i].break_fine;
                    var action = '<a class="edit_shift" href="#" id="' + id + '"  title="Edit" style="color:black"><i class="fa fa-pencil m-r-5"></i> </a>';
                    if (counter_used > 0) {
                        action += '<a href="#" title="This Data is Used in Other Place You Can not Delete This." style="color:black;cursor: not-allowed"><i class="fa fa-trash-o m-r-5"></i></a>';
                    } else {
                        action += '<a class="delete_shift" href="#" id="' + id + '" title="Delete" style="color:black"><i class="fa fa-trash-o m-r-5"></i></a>';
                    }
                    table += '<tr>' +
                        '                   <td>' + (i + 1) + '</td>' +
                        '                   <td><center>' + shift_no + '</center></td>' +
                        '                   <td><center>' + start_time + '</center></td>' +
                        '                   <td><center>' + end_time + '</center></td>' +
                        '                   <td><center>' + break_time + '</center></td>' +
                        '                   <td><center>' + break_fine + '</center></td>' +
                        '                   <td><center>' + late_fine + '</center></td>' +
                        '                   <td class="text-right">' + action + '</td>' +
                        '               </tr>';
                }
                table += '</tbody></table>';
                $("#company_table").html(table);
                $("#company_time").DataTable();
            }
        });
    }
    function add_shift() {
        var shiftNo = $('#shift_no').val();
        var startTime = $('#start_time').val();
        var endTime = $('#end_time').val();
        var breakTime = $('#break_time').val();
        var adminId = $('#admin_id').val();
        var timezone = $('#timezone').val();
        var breakFine = $('#break_fine').val();
        var lateFine = $('#late_fine').val();
        if(breakFine == ''){
            breakFine = 0;
        }
        if(lateFine == ''){
            lateFine = 0;
        }
        if (val_shift(shift_no)) {
            if (val_start_time(start_time)) {
                if (val_end_time(end_time)) {
                    if (val_break_time(break_time)) {
                        if (val_timezone(timezone)) {
                            $.ajax({
                                url: 'insert.php',
                                method: 'POST',
                                data: {
                                    start_time: startTime,
                                    timezone: timezone,
                                    end_time: endTime,
                                    break_time: breakTime,
                                    admin_id: adminId,
                                    shift_no:shiftNo,
                                    break_fine:breakFine,
                                    late_fine:lateFine,
                                    action: 'add_company_time'
                                },
                                success: function (data) {
                                    $('#shift_no').val('');
                                    $('#start_time').val('');
                                    $('#end_time').val('');
                                    $('#break_time').val('');
                                    $('#timezone').val('');
                                    $('#break_fine').val('');
                                    $('#late_fine').val('');
                                    $('#add_shift').modal('hide');
                                    swal("","Shift Added Successfully!", "success");
                                    view_time();
                                }
                            });
                        }
                    }
                }
            }
        }
    }

    function edit_shift() {
        var shift_no = $('#ushift_no').val();
        var start_time = $('#ustart_time').val();
        var end_time = $('#uend_time').val();
        var break_time = $('#ubreak_time').val();
        var time_id = $('#time_id').val();
        var timezone = $('#utimezone').val();
        var break_fine = $('#ubreak_fine').val();
        var late_fine = $('#ulate_fine').val();
        if(break_fine == ''){
            break_fine = 0;
        }
        if(late_fine == ''){
            late_fine = 0;
        }
        if (val_shift(shift_no)) {
            if (val_start_time(start_time)) {
                if (val_end_time(end_time)) {
                    if (val_break_time(break_time)) {
                        if (val_timezone(timezone)) {
                            $.ajax({
                                url: 'update.php',
                                method: 'POST',
                                data: {
                                    start_time: start_time,
                                    timezone: timezone,
                                    end_time: end_time,
                                    break_time: break_time,
                                    time_id: time_id,
                                    shift_no: shift_no,
                                    break_fine:break_fine,
                                    late_fine:late_fine,
                                    action: 'edit_company_time'
                                },
                                success: function (data) {
                                    $('#edit_shift').modal('hide');
                                    swal("", "Shift Edit Successfully!", "success");
                                    view_time();
                                }
                            });
                        }
                    }
                }
            }
        }
    }

    $(document).on('click', '.edit_shift', function () {
        var shift_id = $(this).attr("id");
        $.ajax({
            url: "read.php",
            method: "POST",
            data: {shift_id: shift_id, action: 'shift_fetch'},
            success: function (data) {
                var user = JSON.parse(data);
                $('#ustart_time').val(user.company_in_time);
                $('#uend_time').val(user.company_out_time);
                $('#ubreak_time').val(user.company_break_time);
                $('#ushift_no').val(user.shift_no);
                $('#time_id').val(user.time_id);
                $('#utimezone').val(user.timezone);
                $('#ubreak_fine').val(user.break_fine);
                $('#ulate_fine').val(user.late_fine);
                $('#edit_shift').modal('show');
            }
        });
    });
        function val_start_time(val) {
            if (val == '') {
                swal({title:'Please Select Company Start Time'});
                return false;
            } else {
                return true;
            }
        }
        function val_shift(val) {
            if (val == '') {
                swal({title:'Please Select Company Shift No'});
                return false;
            } else {
                return true;
            }
        }
        function val_end_time(val) {
            if (val == '') {
                swal({title:'Please Select Company End Time'});
                return false;
            } else {
                return true;
            }
        }
        function val_break_time(val) {
            if (val == '') {
                swal({title:'Please Enter Company Break Time'});
                return false;
            } else {
                return true;
            }
        }

        function val_timezone(val) {
            if (val == '') {
                swal({title:'Please Select Company Timezone'});
                return false;
            } else {
                return true;
            }
        }

    $(document).on('click', '.delete_shift', function () {
        var time_id = $(this).attr("id");
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "delete.php",
                    method: "POST",
                    data: {time_id: time_id, action: 'company_time_delete'},
                    datatype: "text",
                    success: function (data) {
                        swal({title:"Shift Delete Successfully.",icon:'success'});
                        view_time();
                    }
                });
            } else {
                swal({title:"Your record is safe!"});
            }
        });
    });

</script>
<?php 
}
else
{
    header("Location:../index.php");
}

