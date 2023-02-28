

function uploademployeeExcel(){
    var file = document.getElementById('upempexcel').files;
    var form_data = new FormData();
    form_data.append("action","addemployee");
    form_data.append("entry_type","excel");
    form_data.append("file",file[0]);
    $("#overlay").show();
    if(file != '') {
        $.ajax({
            url: "insert.php",
            type: "POST",
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            datatype: "text",
            success: function (response) {
                var data =  JSON.parse(response);
                if (data.Error == 0) {
                    $("#overlay").hide();
                    swal({
                        title: "Success",
                        text: "Data Added Success fully.",
                        icon: "success",
                        button: "ok!"
                    });
                    $('#upempexcel').val('');
                    $("#addexcelemployee").modal('hide');
                }
            }
        });
    }
}

$(document).on("click", "#closeexcelimport", function () {
    $("#addexcelemployee").modal('hide');
});

$(document).on("change","#total-card", function(){
    const quntity = this.value;
    const price = 40 * quntity;
    const gst = price * 18 / 100;
    $("#cards-gst").html(gst);
    $("#cards-quintity").html(quntity);
    $("#cards-total").html(price + gst);
});

$(document).on("click", "#buyextra-employee", function () {
    var quntity = $("#total-card").val();
    if(quntity !== 0) {
        $(".wrap-loader").show();
        $.ajax({
            url: 'stripe.php',
            type: 'POST',
            data: {quntity: quntity, action: 'buyemployee'},
            success: function (data) {
                $('#buy_cards').modal('hide');
                swal("Thank you! Your order was successfully submitted!",'','success');
                $(".wrap-loader").hide();
                employeereaming();
            }
        });
    }
});

function shiftchange() {
    var shift = $("#shift").val();
    var page = $("#page").val();
    $(".wrap-loader").show();
    $.ajax({
        url: 'read.php',
        type: 'POST',
        data: {shift: shift, action: 'shift_change'},
        success: function (data) {
            $(".wrap-loader").hide();
            if (page != 'index.php') {
                location.reload();
            } else {
                show(true);
                chart();
            }
        }
    });
}

$(document).on("click", "#buy_cards-btn", function (e) {
    e.preventDefault();
    $('.plan-container').load('buy-employeemodal.php', function (result) {
        $('#buy_cards').modal({show: true});
    });
});

function timeformatConvert(time) {
    // Check correct time format and split into components
    time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

    if (time.length > 1) { // If time format correct
        time = time.slice(1);  // Remove full string match value
        time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
    }
    return time.join(''); // return adjusted time or original string
}

function salary_auth() {
    var password = $("#password").val();
    $(".wrap-loader").show();
    $.ajax({
        url: 'read.php',
        type: 'POST',
        data: {password: password, action: 'salary_auth'},
        dataType: 'json',
        success: function (data) {
            var valid = "valid";
            if (data.status == valid) {
                window.location.href = 'salary.php';
            } else {
                $("#error-message").html("Invalid Enter Password");
                $(".wrap-loader").hide();
            }
        }
    });
}

function processusertable(data, page_array, page, total_links, no) {
    var table = `<table class="table table-striped custom-table mb-0">
                    <thead>
                      <tr>
                         <th><center><b>No</b></center></th>
                         <th><center><b>Name*</b></center></th>
                         <th><center><b>Email*</b></center></th>
                         <th><center><b>Mobile</b></center></th>
                         <th class="text-nowrap"><center><b>Join Date</b></center></th>
                         <th class="text-right no-sort"><b>Action</b></th>
                      </tr>
                    </thead>
                    <tbody>`;


    var i = 0;
    const size = data.length;
    for (i; i < size; i++) {
        var row = data[i];
        if (row.employee_profile != '') {
            var profile = `<img alt="" height="100%" src="../employee/employee_profile/${row.employee_profile}">`;
        } else {
            var profile = `<img alt="" height="100%" src="app/img/user.png">`;
        }
        
        if(row.isVipUser == 1){
            var isVipUsercheck = `<a class="dropdown-item " href="#" onclick="isVipUser(this.name, 0)" id="remove" name="${row.e_id}" style="color:black" title="Remove from VIP User" ><i class="fa fa-user-plus m-r-5"> </i> Remove as VIP User</a>`;
        }else{
            var isVipUsercheck = `<a class="dropdown-item " href="#" onclick="isVipUser(this.name,1)" id="add" name="${row.e_id}" style="color:black" title="Add as VIP User" ><i class="fa fa-user-plus m-r-5"> </i> Add as VIP User</a>`;
        }
        var joindate = row.join_date;
        table += `<tr>
                        <td>${no++}</td>
                        <td>
                            <h2 class="table-avatar">
                                    <a href="profile.php?id=${row.e_id}&name=${row.e_firstname}&nbsp;${row.e_lastname}" class="avatar">${profile}</a>
                                    <a href="profile.php?id=${row.e_id}&name=${row.e_firstname}&nbsp;${row.e_lastname}">${row.e_firstname}&nbsp;${row.e_lastname}<span>${row.departments_name}</span></a>
                            </h2>
                        </td>
                        <td><center>${row.e_email}</center></td>
                        <td><center>${row.e_phoneno}</center></td>
                        <td><center>${joindate}</center></td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item " onclick="updateUserPassword(this.id)" title="Update Password" href="#" id="${row.e_id}" style="color:black" title="Edit" ><i class="fa fa-key" aria-hidden="true"></i> Update Password</a>
                                        <a class="dropdown-item view_doc" data-target="#view_document" title="View Document" href="#" data-toggle="modal" id="${row.e_id}" style="color:black" title="Edit" ><i class="fa fa-eye" aria-hidden="true"></i> View Document</a>
                                        <a class="dropdown-item edit" href="#" data-toggle="modal"  data-target="#edit_employee" id="${row.e_id}" style="color:black" title="Edit" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                        <a class="dropdown-item delete" href="#"  id="deactivate-user" name="${row.emp_cardid}" style="color:black" title="Deactivate" ><i class="fa fa-trash-o m-r-5"> </i> Deactivate</a>
                                        <div class="vip-user${row.e_id}" >${isVipUsercheck}</div>
                                    </div>
                            </div>
                        </td>
                      </tr>`;
    }
    table += `</tbody>
              </table><br/>
              <div class="col-auto float-right ml-auto">
                 <ul class="pagination">`;
    var psize = page_array.length;
    var pagelink = ``;
    var previous_link = '';
    var next_link = '';
    for (var k = 0; k < psize; k++) {
        if (page == page_array[k]) {
            pagelink += `<li class="page-item active">
                            <a class="page-link" href="#">${page_array[k]}<span class="sr-only">(current)</span></a>
                        </li>`;

            var previous_id = page_array[k] - 1;
            if (previous_id > 0) {
                previous_link = `<li class="page-item">
                                    <a class="page-link" href="javascript:void(0)" data-page_number="${previous_id}">Previous</a>
                                </li>`;
            } else {
                previous_link = `<li class="page-item disabled">
                                    <a class="page-link" href="#">Previous</a>
                                </li>`;
            }
            var next_id = page_array[k] + 1;
            if (next_id >= total_links) {
                next_link = ` <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>`;
            } else {
                next_link = `<li class="page-item">
                                <a class="page-link" href="javascript:void(0)" data-page_number="${next_id}">Next</a>
                             </li>`;
            }
        } else {
            if (page_array[k] == '...') {
                pagelink += `<li class="page-item disabled">
                                    <a class="page-link" href="#">...</a>
                                  </li>`;
            } else {
                pagelink += `<li class="page-item">
                                <a class="page-link" href="javascript:void(0)" data-page_number="${page_array[k]}">${page_array[k]}</a>
                              </li>`;
            }
        }
    }
    table += previous_link + pagelink + next_link;
    table += ` </ul>
            </div>`;
    $("#dynamic_content").html(table);
}

$(document).on('click', '#manage-subscription', function (e) {
    e.preventDefault();
    $.ajax({
        url: "read.php",
        type: "POST",
        data: {
            action: "manageBilling",
        },
        success: function (data) {
            // console.log(data);
            var res = JSON.parse(data);
            window.location.href = res.url;
        }
    })
});

const isVipUser = (id, type) => {
    
    $.ajax({
        url: "update.php",
        type: "POST",
        data: {
            action: "isVipUser",
            id: id,
            type:type
        },
        success: function (data) {
            var res = JSON.parse(data);
            if(res.isVipUser == 1){
                var isVipUsercheck = `<a class="dropdown-item " href="#" onclick="isVipUser(this.name, 0)" id="remove" name="${res.id}" style="color:black" title="Remove from VIP User" ><i class="fa fa-user-plus m-r-5"> </i> Remove as VIP User</a>`;
            }else{
                var isVipUsercheck = `<a class="dropdown-item " href="#" onclick="isVipUser(this.name, 1)" id="add" name="${res.id}" style="color:black" title="Add as VIP User" ><i class="fa fa-user-plus m-r-5"> </i> Add as VIP User</a>`;
            }
            $(`.vip-user${id}`).html(isVipUsercheck)
        }
    })
} 

function convertTimeZone(date, format) {
    if (date == "" || date == "false") {
        return;
    }
    // this is temporary solution for date by shyam patel
    // date =  parseFloat(date) + 19800;
    if (format == "date") {
        return moment.utc((date) * 1000).format("YYYY-MM-DD");
    } else if (format == "info") {
        return moment.utc((date) * 1000).format("DD/MM/YYYY");
    } else if (format == "info_toll") {
        return moment.utc((date) * 1000).format("DD/MM/YYYY HH:mm");
    } else if (format == "duedate") {
        return moment.utc((date) * 1000).format("dddd, MMMM D, YYYY h:mm A");
    } else {
        return moment.utc((date + 2678400) * 1000).format("DD/MM/YYYY");
    }
}

function fatchInvoice(id) {
    $(".wrap-loader").show();
    if (id == '' || id == undefined) {
        var nextID = '';
    } else {
        var nextID = id;
    }
    $.ajax({
        url: "read.php",
        type: "POST",
        data: {
            action: "fetch_invoice",
            data: {last: nextID}
        },
        success: function (response) {
            // console.log(response);
            $(".wrap-loader").hide();
            let resdata = JSON.parse(response);
            let dataLen = resdata.data.length;
            let dataArr = resdata.data;
            let table = `<table id="invoice-table" class='table table-center mb-0'>
                        <thead>
                            <tr>
                                <th>Inv #</th>
                                <th>Inv Date</th>
                                <th>Inv Amount</th>
                                <th>Payment Type</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>`;
            let row = ``;
            for (let i = 0; i < dataLen; i++) {
                let invoice_pdf = dataArr[i].invoice_pdf;
                let invoicenumber = dataArr[i].number;
                let total = dataArr[i].total / 100;
                let hou_r = moment.utc(dataArr[i].status_transitions.finalized_at * 1000).format("MM/DD/YYYY h:mm A");

                row += `<tr>
                            <td>${invoicenumber}</td>
                            <td>${hou_r}</td>
                            <td>${total}</td>
                            <td>Card</td>
                            <td><a class='btn btn-dark btn-sm'  href='${invoice_pdf}' target='_blank'>Download</a></td>
                        </tr>`;
            }
            var tableend = `</tbody>
                    </table>`;
            if (dataLen == 10) {
                let view_button = `<button class="pricing-action" onclick = "fatchInvoice('${dataArr[dataLen - 1].id}');" style="position: absolute; bottom: 0; left: 22%; margin-bottom:1em !important;">View more</button>`;
                $('#view_more').html(view_button);
            } else {
                $('#view_more').html('');
            }
            if (id == '' || id == undefined) {
                $('#payment-history').html(table + row + tableend);
                $('.loading1').css('display', 'none');
            } else {
                $("#invoice-table > tbody").append(row);
                $('.loading1').css('display', 'none');
            }
        }
    });
}

function employeereaming() {
    $.ajax({
        url: "read.php",
        type: "POST",
        data: {
            action: "reaming_employee",
        },
        success: function (response) {
            // console.log(response);
            let resdata = JSON.parse(response);
            let total = resdata.total;
            let remaining = resdata.remaining;
            let exdate = convertTimeZone(resdata.planend,'duedate');
            let new_join = resdata.new;
            let left = resdata.left;
            if (remaining <= 0) {
                let view_button = `buy modal code`;
                $('#employee-add-button').html(view_button);
            } else {
                let view_button1 = `<div class="col-auto float-right ml-auto">
                                        <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#add_employee"><i
                                                    class="fa fa-plus"></i> Add Employee</a>
                                    </div>`;
                let view_button2 = `<div class="col-auto float-right ml-auto">
                                        <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addexcelemployee"><i
                                                    class="fa fa-plus"></i> Import Employee Excel</a>
                                    </div>`;
                $('#employee-add-button').html(view_button1);
            }

            $('#employee-total-counter').html(total);
            $('#renew-date').html(exdate);
            $('#employee-remaining-counter').html(remaining);
            $('#new-joining').html(new_join);
            $('#left-total').html(left);
        }
    });
}

function emailexist(email,currentemail,status){
    if(status){
        if(email == currentemail){
            return JSON.stringify({status : "new"});
        }
    }
    return $.ajax({
        url: "read.php",
        type: "POST",
        async:false,
        data: {
            action: "emailexist",
            email: email
        },
        success: function (response) {
            let resdata = JSON.parse(response);
            if(resdata.status == 'new'){
                return "not exists";
            }else{
                swal("Email already exists in system.","","info");
                return "exists";
            }
        }
    });
}

function valcontact(val) {
    if (val == '') {
        swal('Please Enter Phone Number', "", "info");
        return false;
    } else if (val.length > 10 || val.length < 10) {
        swal('Please Enter 10 Digits Only', "", "info")
    } else if (isNaN(val)) {
        swal('Enter a number only', "", "info")
    } else {
        return true;
    }
}

function checkvalid(val, name, status) {
    if (val != '') {
        return true;
    } else {
        if (status) {
            swal(`${name}`, `This field requied `, "info");
        } else {
            swal(`Enter ${name}`, `This field requied`, "info");
        }
        return false;
    }
}

function confirmpassword(email, confirm) {
    if (email == confirm) {
        return true;
    } else {
        swal('Password not match', "", "info");
        return false;
    }
}


function valemail(val) {
    if (val == '') {
        swal('Enter your email', '', 'info');
        return false;
    } else if (val.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
        swal('Enter Valid Email', "", "info");
        return false;
    } else {
        return true;
    }
}


function countAbsentDays(presentDays){
    const workingDays = $("#working_days").val();
    const absentDays = workingDays - presentDays;
    $("#absent_days").val(absentDays <= 0 ? 0 : absentDays );
}

