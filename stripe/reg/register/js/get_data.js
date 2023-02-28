var plan_id = {};
var total = 0;

var delete_cookie = function(name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};


function totalIt() {
    plan_id = '';
    var input = document.getElementsByName("product");
    var total = 0;
    var no = 0;
    for (var i = 0; i < input.length; i++) {
        if (input[i].checked) {
            var addon_id = input[i].value;
            if(addon_id != ''){
                if(no == 0){
                    plan_id += addon_id;
                    no++;
                }else {
                    plan_id += ","+addon_id;
                }

            }
            var data = {plan_id: addon_id,};
            $.ajax({
                async: false,
                url: '../Master.php',
                type: 'POST',
                dataType: "html",
                data: {
                    data: data,
                    sub: 'get_price',
                    main: 'register'
                },
                success: function (data) {
                    if(data > 0){
                        total = (parseInt(total) + parseInt(data));
                    }
                }
            });
        }
    }
    // alert(total);
    document.cookie = "amount="+total;
    document.cookie = "plan_id="+plan_id;
    $("#total").val(total);
    $('#hide').show();

}

function valueChanged() {
    if ($('.coupon_question').is(":checked"))
        $('#hide').show();
    else
        $('#hide').hide();
}

