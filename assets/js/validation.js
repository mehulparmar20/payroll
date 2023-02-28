
//HR Validation
function select_employees() {
    var text;
    // select employees validation
    var select_employees = document.getElementById('select_staff').value;
    if (select_employees === '') {
        text = 'Please Select This Field *';
    } else {
        if (select_employees.search(/^[a-z A-Z]*$/))
        {
            text = 'Please Insert Only Alphabet *';
        } else {
            text = '';
        }
    }
    document.getElementById("select_employees").innerHTML = text;
}
function basic_salary() {
    // basic salary

    var basic_salary = document.getElementById('basic').value;
    if (basic_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (basic_salary.search(/^\d{4,7}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("basic_salary").innerHTML = text;
}

function da_salary() {
    // DA

    var da_salary = document.getElementById('da').value;
    if (da_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (da_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("da_salary").innerHTML = text;
}
function hra_salary() {
    // HRA

    var hra_salary = document.getElementById('hra').value;
    if (hra_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (hra_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("hra_salary").innerHTML = text;
}
function conn_salary() {
    // Conveyance

    var conn_salary = document.getElementById('conveyance').value;
    if (conn_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (conn_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("conn_salary").innerHTML = text;
}
function allow_salary() {
    // Allows

    var allow_salary = document.getElementById('allow').value;
    if (allow_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (allow_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("allow_salary").innerHTML = text;
}
function mallow_salary() {
    // Medical Allow

    var mallow_salary = document.getElementById('m_allow').value;
    if (mallow_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (mallow_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("mallow_salary").innerHTML = text;
}
function others_salary() {
    // Other in Earning

    var others_salary = document.getElementById('others').value;
    if (others_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (others_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("others_salary").innerHTML = text;
}
function tds_salary() {
    // TDS

    var tds_salary = document.getElementById('tds').value;
    if (tds_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (tds_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("tds_salary").innerHTML = text;
}
function esi_salary() {
    // ESI

    var esi_salary = document.getElementById('esi').value;
    if (esi_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (esi_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("esi_salary").innerHTML = text;
}
function pf_salary() {
    //PF

    var pf_salary = document.getElementById('pf').value;
    if (pf_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (pf_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("pf_salary").innerHTML = text;
}
function leave_salary() {
    //Leave

    var leave_salary = document.getElementById('e_leave').value;
    if (leave_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (leave_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("leave_salary").innerHTML = text;
}
function tax_salary() {
    // Proftax

    var tax_salary = document.getElementById('proftax').value;
    if (tax_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (tax_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("tax_salary").innerHTML = text;
}
function lw_salary() {
    // lw

    var lw_salary = document.getElementById('l_wel').value;
    if (lw_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (lw_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("lw_salary").innerHTML = text;
}
function other1_salary() {
    //other in deduction 

    var other1_salary = document.getElementById('other1').value;
    if (other1_salary == '')
    {
        text = 'Please Fill This Field *';
    } else {
        if (other1_salary.search(/^\d{1,2}$/))
        {
            text = 'Please Insert Valied Number *';
        } else {
            text = '';
        }
    }
    document.getElementById("other1_salary").innerHTML = text;
}










                