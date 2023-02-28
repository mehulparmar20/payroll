flag = 1;

function validate_form() {
        //page 1 Variable
        var fname = $("#f_name").val();
        var lname = $("#l_name").val();
        var company_name = $("#c_name").val();
        var phone_number = $("#phone").val();
        var email = $("#email").val();
        // var business_type = $("#business_type").val();
        // var total_user = $("#total_user").val();
        //page 2 Variable
        // var username = $("#user_name").val();
        var password = $("#password").val();
        // var c_password = $("#password2").val();
        // var country = $("#country").val();
        // var state = $("#state").val();
        // var town = $("#town").val();
        // var address = $("#address").val();
        // var zip = $("#zip").val();
        // var mc_num = $("#mc_num").val();
        // var ff_num = $("#ff_num").val();
         if ($('#agree').is(':checked')) {
            var terms = 'Yes';
         } else {
            var terms = 'No';
         }

        //page 3 Variable
      if(flag == 1) {
         if(fname == '') {
            swal({title:"Please Enter First-Name"})
            .then((value) =>{
               $("#f_name").focus();
            });
            return false;
         }
         if(lname == '') {
            swal({title:"Please Enter Last-Name"})
            .then((value) =>{
               $("#l_name").focus();
            });
            return false;
         }
         if(company_name == '') {
            swal({title:"Please Enter Your Company-Name"})
            .then((value) =>{
               $("#c_name").focus();
            });
            return false;               
         }
         if(phone_number == '') {
            swal({title:"Please Enter Contact Number"})
            .then((value) =>{
               $("#phone").focus();
            });
            return false; 
         }
         if(phone_number.length > 10 || phone_number.length < 10) {
            swal({title:"Please Enter 10 Digit Contact Number"})
            .then((value) =>{
               $("#phone").focus();
            });
            return false;
         }
         if(email == '') {
            swal({title:"Please Enter Your Email"})
            .then((value) =>{
               $("#email").focus();
            });
            return false;
         }

         // if(business_type == '') {
         //    swal({title:"Please Select Your Business Type"})
         //    .then((value) =>{
         //       $("#business_type").focus();
         //    });
         //    return false;
         // }
         // if(total_user == '') {
         //    swal({title:"Please Enter Number Of User"})
         //    .then((value) =>{
         //       $("#total_user").focus();
         //    });
         //    return false;
         // }
         if(terms != 'Yes') {
            swal({title:"Please Read And Accept Terms and Conditions"})
            .then((value) =>{
               $("#terms").focus();
            });
            return false;
         }

         // if(email != '') {
         //    var data = {
         //       email:email,
         //    };
         //    $.ajax({
         //       url: '../Master.php',
         //       type: 'POST',
         //       dataType: "html",
         //       data: {
         //          data:data,
         //          sub:'email_exists',
         //          main:'register'
         //       },
         //       success: function (data) {
         //          var data = data.trim();
         //          if(data == 'user'){
         //             swal({title:"Email already used"})
         //                 .then((value) =>{
         //                    $("#email").focus();
         //                 });

         //             return false;
         //          }else{
         //             return true;
         //          }
         //       }
         //    });
         // }

         flag +=1;
         return true;
      }     
      // if(flag == 2) {
      //    if(username == '') {
      //       swal({title:"Please Enter Username"})
      //        .then((value) =>{
      //           $("#user_name").focus();
      //        });
      //       return false;
      //    }
      //    if(password == '') {
      //       swal({title:"Please Enter Password"})
      //           .then((value) =>{
      //              $("#password1").focus();
      //           });
      //       return false;
      //    }
      //    if(c_password == '') {
      //       swal({title:"Please Enter Confirm Password"})
      //           .then((value) =>{
      //              $("#password2").focus();
      //           });
      //       return false;
      //    }
      //    if(password != c_password) {
      //       swal({title:"Password Not Match"})
      //           .then((value) =>{
      //              $("#password2").focus();
      //           });
      //       return false;
      //    }

      //    if(country == '') {
      //       swal({title:"Please Select Your Country"})
      //       .then((value) =>{
      //          $("#country").focus();
      //       });
      //       return false;
      //    }

      //    if(state == '') {
      //       swal({title:"Please Enter Your State"})
      //       .then((value) =>{
      //          $("#state").focus();
      //       });
      //       return false;
      //    }

      //    if(town == '') {
      //       swal({title:"Please Enter Your Town/City"})
      //       .then((value) =>{
      //          $("#town").focus();
      //       });
      //       return false;
      //    }


      //    if(address == '') {
      //       swal({title:"Please Enter Your Address"})
      //       .then((value) =>{
      //          $("#address").focus();
      //       });
      //       return false;
      //    }

      //    if(zip == '') {
      //       swal({title:"Please Enter Your Zip(Postal) Code"})
      //       .then((value) =>{
      //          $("#zip").focus();
      //       });
      //       return false;
      //    }

      //    if(mc_num == '') {
      //       swal({title:"Please Enter Your MC Number"})
      //       .then((value) =>{
      //          $("#mc_num").focus();
      //       });
      //       return false;
      //    }

      //    if(ff_num == '') {
      //       swal({title:"Please Enter Your FF Number"})
      //       .then((value) =>{
      //          $("#ff_num").focus();
      //       });
      //       return false;
      //    }

      //    flag +=1;
      //    return true;
      // }
      // if(flag == 3) {
      //    $("#next").hide();
      //    $("#finish").show();
      //    return true;
      // }
        
}
function flag_prev() {
   flag -=1;
}


function val_emailexist(email) {

}

function validateEmpty(val,id,fildname) {
   if(val.length <= 0) {
     swal({title:`${fildname} cannot be empty.`})
            .then((value) =>{
               $("#"+ id).focus();
            });
            return false;
   }
   return true;
}


function validatePhone() {
   var phone_no = $("#phone").val();
   if(phone_no.length > 10 || phone_no.length < 10) {
      swal({title:"Please Enter 10 Digit Contact Number"})
      .then((value) =>{
         $("#phone").focus();
      });
      return false;
   }
   return true;
}

function validateEmail() {
   var checkEmail = $("#email").val();
   if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(checkEmail)) {
      return true;
   } else {
      swal({title:"Enter a valid Email"}).then((value) => {
         $("#email").focus();
         return false;
     });
   }
  
}
