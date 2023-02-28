(function()
{

    var _fbq = window._fbq || (window._fbq = []);

    if (!_fbq.loaded) {

    var fbds = document.createElement('script');

    fbds.async = true;

    fbds.src = '//connect.facebook.net/en_US/fbds.js';

    var s = document.getElementsByTagName('script')[0];

    s.parentNode.insertBefore(fbds, s);

    _fbq.loaded = true;

    }

    _fbq.push(['addPixelId', '1437801909809268']);

    })();

    window._fbq = window._fbq || [];

    window._fbq.push(['track', 'PixelInitialized', {}]);


(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
        (i[r].q = i[r].q || []).push(arguments)
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-46619217-1', 'auto');
ga('send', 'pageview');


(function () {

    var _fbq = window._fbq || (window._fbq = []);

    if (!_fbq.loaded) {

        var fbds = document.createElement('script');

        fbds.async = true;

        fbds.src = '//connect.facebook.net/en_US/fbds.js';

        var s = document.getElementsByTagName('script')[0];

        s.parentNode.insertBefore(fbds, s);

        _fbq.loaded = true;

    }

})();

window._fbq = window._fbq || [];

window._fbq.push(['track', '6019212947186', {'value': '0.01', 'currency': 'INR'}]);



        /* Keyup functions */
        //Candiate Name change based on the first Name Input
        $('#txtCandidateName').keyup(function () {
            $('.pCandidateName1').text($(this).val());
            $('.pCandidateName2').text($(this).val());
        });

        $('#txtCompanyName').keyup(function () {
            $('.pCompanyName').text($(this).val());
        });

        $('#txtSupervisorName').keyup(function () {
            $('.pSupervisorName').text($(this).val());
        });

        $('#txtSupervisorDesignation').keyup(function () {
            $('.pSupervisorDesignation').text($(this).val());
        });
        $('#txtTempEditor').keyup(function (e) {
            var data = $('#btnNext').attr('data-inputCount');
            $(".textInput[data-inputCount='" + data + "']").val($(this).val());
            var v = $('#txtTempEditor').val();
            var value = (v.length + 1) * 8.0 + "px";
            $(".textInput[data-inputCount='" + data + "']").css('width', value);

            if (data == '1')
            {
                $('.pCandidateName1').text($(".k[data-selectCount='1']").val() + $(this).val() + ",");
                $('.pCandidateName2').text($(this).val() + ".");
            }

            if (data == '5')
            {

                var pincode = $('#txtPinCode').val();
                if (pincode.length == 6) {
                    $.ajax({
                        type: "POST",
                        data: {"pincode": pincode},
                        success: function (response) {
                            var json = JSON.parse(response);
                            var slc = json[0].state_name;
                            slc = slc.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                                return letter.toUpperCase();
                            });
                            $('#selState').val(slc);
                            $('.k[data-selectcount="2"]').val(slc);
                            var v = json[0].state_name;
                            var va = (v.length + 1) * 8.0 + "px";
                            $('.k[data-selectcount="2"]').css('width', va);
                        }
                    });
                }

            }

            if (data == '6') {
                $('.pCompanyName').text($(this).val());
            }
            if (data == '10') {
                $('.pSupervisorName').text($(this).val());
            }
            if (data == '11') {
                $('.pSupervisorDesignation').text($(this).val());
            }

        });


        $(document).ready(function () {
            $(".txtDateCurrent").datepicker({format: "d MM, yyyy", todayHighlight: true, autoclose: 'true'});
            $(".txtDateCurrent").on("change", function () {
                var selected = $(this).val();
                var bottom_date = selected;
                var res = selected.split(" ");
                var day = res[0];
                var mon = res[1];
                var yr = res[2];
                //var suffix
                if (day == 1 || day == 21 || day == 31) {
                    var suffix = "st";
                } else if (day == 2 || day == 22) {
                    var suffix = "nd";
                } else if (day == 3 || day == 23) {
                    var suffix = "rd";
                } else {
                    var suffix = "th";
                }

                var date = res[0] + suffix;

                var final = date + ' ' + mon + ' ' + yr;
                $(this).val(final);
            });
        });

        /* Plugin Initialization */
        $('.dateTimePicker').datepicker({
            format: 'dd-mm-yyyy',
            startDate: 'd',
            autoclose: true,
            todayHighlight: true,
            daysOfWeekDisabled: '0'
        });

        //$('.timepicker').timepicker();

        $('#txtWorkTimeStart').click(function () {
            $('#txtWorkTimeStart').timepicker({
                defaultTime: 'current',
                minuteStep: 1,
                disableFocus: true,
                template: 'dropdown'
            });
            this.style.width = ((this.value.length) * 8.0) + 'px';
        });
        $('#txtWorkTimeEnd').click(function () {
            $('#txtWorkTimeEnd').timepicker({
                defaultTime: 'current',
                minuteStep: 1,
                disableFocus: true,
                template: 'dropdown'
            });
            this.style.width = ((this.value.length) * 8.0) + 'px';
        });

        $('#txtStartingTime').click(function () {
            $('#txtStartingTime').timepicker({
                defaultTime: 'current',
                minuteStep: 1,
                disableFocus: true,
                template: 'dropdown'
            });
            this.style.width = ((this.value.length) * 8.0) + 'px';
        });

        /* Click Functions */

        //click to show textEditor
        $('.textInput').click(function () {

            var placeHolder = $(this).attr('placeholder'); //get place holder and change it with textEditor
            $('#txtTempEditor').attr('placeholder', placeHolder);

            $('#txtTempEditor').attr('size', $(this).attr('placeholder').length);// Resize the txtTempEditor based on placeholder text
            var width = ($('#txtTempEditor').attr('placeholder').length + 1) * 8.0 + "px";
            $('#txtTempEditor').css('width', width);

            //Clear text value
            $('#txtTempEditor').val($(this).val());

            var data = $(this).attr('data-inputCount'); //get data-inputCount and change it with textEditor
            if (data != "13") {
                $('#btnNext').attr('data-inputCount', data);

                var p = $(this).position(); //positioning the div based on the mouse pointer
                $(".textEditor").css({
                    top: p.top - 12,
                    left: p.left
                }).show();
                $('#txtTempEditor').focus();
            } else {
                $('#btnNext').attr('data-inputCount', data);

                $('#btnNext').hide();
                //$('#btnFinish').show();

                var p = $(this).position(); //positioning the div based on the mouse pointer
                $(".textEditor").css({
                    top: p.top,
                    left: p.left
                }).show();
                $('#txtTempEditor').focus();
            }

        });

        $('#btnNext').click(function () {
            var data = $(this).attr('data-inputCount');

            //Check whether last input or not
            if (data != '12') {
                var value = parseInt(data) + 1;
                $(".textInput[data-inputCount='" + value + "']").click();
                var p = $(".textInput[data-inputCount='" + value + "']").position();
                $(".textEditor").css({
                    top: p.top,
                    left: p.left
                }).show();
                $('#txtTempEditor').val("").focus();

                $('#txtTempEditor').val($(this).val());

                //Change text size as per placeholder text
                var placeHolder = $(".textInput[data-inputCount='" + value + "']").attr('placeholder');
                $('#txtTempEditor').attr('placeholder', placeHolder);
                var width = ($('#txtTempEditor').attr('placeholder').length + 1) * 8.0 + "px";
                $('#txtTempEditor').css('width', width);
            } else {
                $('#btnNext').hide();
                //                    $('#btnFinish').show();
                var value = parseInt(data) + 1;
                $(".textInput[data-inputCount='" + value + "']").click();
                var p = $(".textInput[data-inputCount='" + value + "']").position();
                $(".textEditor").css({
                    top: p.top,
                    left: p.left
                }).show();
                $('#txtTempEditor').val("").focus();

                var placeHolder = $(".textInput[data-inputCount='" + value + "']").attr('placeholder');
                $('#txtTempEditor').attr('placeholder', placeHolder);
                var width = ($('#txtTempEditor').attr('placeholder').length + 1) * 8.0 + "px";
                $('#txtTempEditor').css('width', width);
            }

        });



        $('.btnContinue').click(function () {

            $('#myDocument').modal('show');

        });



        $("#modal_form").submit(function () {

            $('#submiter_name').val($('#dload_name').val());
            $('#submiter_mobile').val($('#dload_phone').val());
            $('#submiter_email').val($('#dload_email').val());

            swal({
                title: "Success!",
                text: "The document has been sent to your email!",
                type: "success",
                timer: 500,
                showConfirmButton: false
            },
                    function () {
                        $("#form-one").submit();
                        $('#myDocument').modal('hide');

                    });
            return false;

        });



        $('form').on('click', 'input[data-select="true"]', function () {
            var data = $(this).attr('data-selectCount');
            var p = $(this).position(); //positioning the div based on the mouse pointer
            $(".dropDown" + data).css({
                top: p.top - 20,
                left: p.left
            }).show();
        });

        $('form').on('click', '.dropDown ul li', function () {
            var data = $(this).parent().closest('.dropDown').attr('data-selectCount');

            if (data == '1') {

                $(".k[data-selectCount='" + data + "']").val($(this).text());

                $('.dropDown').hide();
                var v = $(".k[data-selectCount='" + data + "']").val();
                var va = (v.length) * 7.5 + "px";
                $(".k[data-selectCount='" + data + "']").css('width', va);

                //change salutation for dear line
                $('.pCandidateName').text($(this).text() + $("input[data-inputCount='1']").val());
            } /* else if (data == '3') {
             $(".k[data-selectCount='" + data + "']").val($(this).text());
             
             $('.dropDown').hide();
             var v = $(".k[data-selectCount='" + data + "']").val();
             var va = (v.length) * 7.5 + "px";
             $(".k[data-selectCount='" + data + "']").css('width', va);
             
             //change salutation for dear line
             $('.pSupervisorName').text($(this).text() + $("input[data-inputCount='12']").val());
             } */
            else {
                //var data = $(this).parent().closest('.dropDown').attr('data-selectCount');
                $(".k[data-selectCount='" + data + "']").val($(this).text());

                $('.dropDown').hide();
                var v = $(".k[data-selectCount='" + data + "']").val();
                var va = (v.length) * 8.0 + "px";
                $(".k[data-selectCount='" + data + "']").css('width', va);
            }
        });

        /* Other Events */

        $(document).ready(function () {

            $("input[placeholder]").each(function () {
                if ($(this).val() == '') {
                    $(this).attr('size', $(this).attr('placeholder').length);
                    var width = ($(this).attr('placeholder').length + 1) * 8.0 + "px";
                    $(this).css('width', width);
                }
            });

            //                var width = ($('#largeInputBox').attr('placeholder').length + 1) * 14 + "px";
            //                $('#largeInputBox').css('width', width);


        });

        //Auto size the textbox size along with the input value

        //OnKeypress event
        $("input[type='text']").each(function () {
            $(this).attr('onkeyup', "this.style.width = ((this.value.length + 1) * 8.0) + 'px';");
        });

        //On dateChange event
        $(document).ready(function () {
            $('.dateTimePicker').datepicker().on('changeDate', function () {
                this.style.width = ((this.value.length + 1) * 8.0) + 'px';
            });
            $('.txtDateCurrent').datepicker().on('changeDate', function () {
                this.style.width = ((this.value.length + 1) * 8.0) + 'px';
            });
        });

        //Hide the textEditor when foucusOut
        $(document).mouseup(function (e)
        {
            var container = $(".textEditor");

            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $('#btnNext').show();
                $('#btnFinish').hide();
                $('.textEditor').attr('data-inputCount', '');
                $('.textEditor').attr('placeholder', '');
                $('.textEditor').val('');
                $('.textEditor').hide();

                $("input[placeholder]").each(function () {
                    if ($(this).val() == '') {
                        $(this).attr('size', $(this).attr('placeholder').length);

                        if (!'input[data-select="Email ID"]') {
                            var width = ($(this).attr('placeholder').length + 1) * 8.0 + "px";
                            $(this).css('width', width);
                        }
                    }
                });
                if ($('#largeInputBox').val() == '') {
                    var width = ($('#largeInputBox').attr('placeholder').length + 1) * 14 + "px";
                    $('#largeInputBox').css('width', width);
                }
            }
        });
        

        var data = 0;

        $('select').each(function () {
            data++;
            $(this).hide();
            $(this).before("<input type='text' class='k btnTrigger' placeholder='' data-select='true' data-selectCount='" + data + "'>");
            var divClass = "dropDown" + data;
            $(this).after("<div class='dropDown " + divClass + "' style='display: none' data-selectCount='" + data + "'>");
            $("." + divClass).append("<ul class='select" + data + "'></ul>");
            $(this).children('option').each(function ()
            {
                var selectClass = ".select" + data;
                $(selectClass).append("<li>" + $(this).text() + "</li>");
            });
        });

        $('.k').each(function () {
            var d = $(this);
            var data = $(this).siblings('.dropDown').children('ul').children('li:first').text();
            //$(this).closest(' ul li:eq(0)').text();
            $(this).attr('placeholder', data);
        });

        $(document).mouseup(function (e)
        {
            var container = $(".dropDown");

            if (!container.is(e.target) // if the target of the click isn't the container...
                    && container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                $(".dropDown").hide();
            }
        });
        
$(document).ready(function (){

        $(".k[data-selectCount='1']").attr('name','salutation');
        $(".k[data-selectCount='2']").attr('name','state');
        $(".k[data-selectCount='3']").attr('name','salutation1');
        $(".k[data-selectCount='4']").attr('name','work_days');
        $(".k[data-selectCount='5']").attr('name','work_days1');
        $(".k[data-selectCount='6']").attr('name','salutation2');


        });
        
         function test_skill(){

        var junkVal=document.getElementById('txtSalaryMonth').value;

        junkVal=Math.floor(junkVal);
        var obStr=new String(junkVal);
        numReversed=obStr.split("");
        actnumber=numReversed.reverse();

        if(Number(junkVal) >=0){
        //do nothing
        }
        else{
        alert('wrong Number cannot be converted');
        return false;
        }
        if(Number(junkVal)==0){
        document.getElementById('container').innerHTML=obStr+''+'Rupees Zero Only';
        return false;
        }
        if(actnumber.length>9){
        alert('Oops!!!! the Number is too big to covertes');
        return false;
        }

        var iWords=['Zero', " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine"];
        var ePlace=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
        var tensPlace=['dummy', ' Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];

        var iWordsLength=numReversed.length;
        var totalWords="";
        var inWords=new Array();
        var finalWord="";
        j=0;
        for(i=0; i<iWordsLength; i++){
        switch(i)
        {
        case 0:
        if(actnumber[i]==0 || actnumber[i+1]==1 ) {
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        //inWords[j]=inWords[j]+' Only';
        inWords[j]=inWords[j];
        break;
        case 1:
        tens_complication();
        break;
        case 2:
        if(actnumber[i]==0) {
        inWords[j]='';
        }
        else if(actnumber[i-1]!=0 && actnumber[i-2]!=0) {
        inWords[j]=iWords[actnumber[i]]+' Hundred and';
        }
        else {
        inWords[j]=iWords[actnumber[i]]+' Hundred';
        }
        break;
        case 3:
        if(actnumber[i]==0 || actnumber[i+1]==1) {
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        if(actnumber[i+1] != 0 || actnumber[i] > 0){
        inWords[j]=inWords[j]+" Thousand";
        }
        break;
        case 4:
        tens_complication();
        break;
        case 5:
        if(actnumber[i]==0 || actnumber[i+1]==1) {
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        if(actnumber[i+1] != 0 || actnumber[i] > 0){
        inWords[j]=inWords[j]+" Lakh";
        }
        break;
        case 6:
        tens_complication();
        break;
        case 7:
        if(actnumber[i]==0 || actnumber[i+1]==1 ){
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        inWords[j]=inWords[j]+" Crore";
        break;
        case 8:
        tens_complication();
        break;
        default:
        break;
        }
        j++;
        }

        function tens_complication() {
        if(actnumber[i]==0) {
        inWords[j]='';
        }
        else if(actnumber[i]==1) {
        inWords[j]=ePlace[actnumber[i-1]];
        }
        else {
        inWords[j]=tensPlace[actnumber[i]];
        }
        }
        inWords.reverse();
        for(i=0; i<inWords.length; i++) {
        finalWord+=inWords[i];
        }
        //document.getElementById('container').innerHTML=obStr+'  '+finalWord;
        document.getElementById('sal_month1').value=finalWord;
        }

        function te() {

        var junkVal=document.getElementById('txtSalaryAnnual').value;

        junkVal=Math.floor(junkVal);
        var obStr=new String(junkVal);
        numReversed=obStr.split("");
        actnumber=numReversed.reverse();

        if(Number(junkVal) >=0){
        //do nothing
        }
        else{
        alert('wrong Number cannot be converted');
        return false;
        }
        if(Number(junkVal)==0){
        document.getElementById('container').innerHTML=obStr+''+'Rupees Zero Only';
        return false;
        }
        if(actnumber.length>9){
        alert('Oops!!!! the Number is too big to covertes');
        return false;
        }

        var iWords=['Zero', " One", " Two", " Three", " Four", " Five", " Six", " Seven", " Eight", " Nine"];
        var ePlace=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
        var tensPlace=['dummy', ' Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];

        var iWordsLength=numReversed.length;
        var totalWords="";
        var inWords=new Array();
        var finalWord="";
        j=0;
        for(i=0; i<iWordsLength; i++){
        switch(i)
        {
        case 0:
        if(actnumber[i]==0 || actnumber[i+1]==1 ) {
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        //inWords[j]=inWords[j]+' Only';
        inWords[j]=inWords[j];
        break;
        case 1:
        tens_complication();
        break;
        case 2:
        if(actnumber[i]==0) {
        inWords[j]='';
        }
        else if(actnumber[i-1]!=0 && actnumber[i-2]!=0) {
        inWords[j]=iWords[actnumber[i]]+' Hundred and';
        }
        else {
        inWords[j]=iWords[actnumber[i]]+' Hundred';
        }
        break;
        case 3:
        if(actnumber[i]==0 || actnumber[i+1]==1) {
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        if(actnumber[i+1] != 0 || actnumber[i] > 0){
        inWords[j]=inWords[j]+" Thousand";
        }
        break;
        case 4:
        tens_complication();
        break;
        case 5:
        if(actnumber[i]==0 || actnumber[i+1]==1) {
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        if(actnumber[i+1] != 0 || actnumber[i] > 0){
        inWords[j]=inWords[j]+" Lakh";
        }
        break;
        case 6:
        tens_complication();
        break;
        case 7:
        if(actnumber[i]==0 || actnumber[i+1]==1 ){
        inWords[j]='';
        }
        else {
        inWords[j]=iWords[actnumber[i]];
        }
        inWords[j]=inWords[j]+" Crore";
        break;
        case 8:
        tens_complication();
        break;
        default:
        break;
        }
        j++;
        }

        function tens_complication() {
        if(actnumber[i]==0) {
        inWords[j]='';
        }
        else if(actnumber[i]==1) {
        inWords[j]=ePlace[actnumber[i-1]];
        }
        else {
        inWords[j]=tensPlace[actnumber[i]];
        }
        }
        inWords.reverse();
        for(i=0; i<inWords.length; i++) {
        finalWord+=inWords[i];
        }
        //document.getElementById('container').innerHTML=obStr+'  '+finalWord;
        document.getElementById('sal_yr1').value=finalWord;
        }