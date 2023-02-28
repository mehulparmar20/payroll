            
            // Registration Velidation start
                function c_name() {
                    
                    var text;                    
                    // company name validation
                    var company_name = document.getElementById('company_name').value;
                    if(company_name === ''){
                        text = 'Please Fill This Field *';
                    }else{
                        if(company_name.search(/^[a-z A-Z]*$/))
                        {
                            text = 'Please Insert Only Alphabet *';
                        }else{
                            text = '';
                        }
                    }
                    document.getElementById("c_name").innerHTML = text;
                }
                function f_name() {
                    // first_name validatio
                    var f_name = document.getElementById('first_name').value;
                    if(f_name === '')
                    {
                        text = 'Please Fill This Field *';
                    }else{
                        if(f_name.search(/^[a-zA-Z]*$/))
                        {
                            text = 'Please Insert Only Alphabet *';
                        }else{
                            text = '';
                        }       
                    }   
                    document.getElementById("f_name").innerHTML = text;
                }
                function l_name() {
                    // last_name validatio
                    var l_name = document.getElementById('last_name').value;
                    if(l_name === '')
                    {
                        text = 'Please Fill This Field *';
                        flag = false;
                    }else{
                        if(l_name.search(/^[a-zA-Z]*$/))
                        {
                            text = 'Please Insert Only Alphabet *';
                        }else{
                            text = '';
                        }
                    }
                    document.getElementById("l_name").innerHTML = text;
                }
                function c_num() {
                    // contact_number 
                    var contact = document.getElementById("contact_number").value;
                    if(contact === '')
                    {
                        text = 'Please Fill This Field *';
                    }else{
                        if (contact.search(/^\d{10}$/)) {
                            text = "Please Enter The Valid Number *";
                        }else{
                            text = "";
                        }
                    }
                    document.getElementById("c_num").innerHTML = text;
                }
                function c_add() {
                   // Company Address
                    var c_address = document.getElementById('company_address').value;
                    if(c_address === '')
                    {
                       text = 'Please Fill This Field *';
                    }else{
                        text = '';
                    }
                    document.getElementById("c_address").innerHTML = text;
                }
                function email() {
                   // email 
                   var email = document.getElementById('company_email').value;
                    if(email === '')
                    {
                       text = 'Please Fill This Field *'
                    }else{
                        if (email.search(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)) {
                            text = "Your Email Is not valid *";
                        }else{
                            text = "";
                        }
                    }
                    document.getElementById("c_email").innerHTML = text;
                }
                function u_name() {
                    // user_name
                    var username = document.getElementById('username').value;
                    if(username === '')
                    {
                       text = 'Please Fill This Field *'
                    }else{
                        text = '';
                    }
                    document.getElementById("u_name").innerHTML = text;
                }
                function pass() {
                    // password
                    var password = document.getElementById('password').value;
                    if(password === '')
                    {
                       text = 'Please Fill This Field *'
                    }else{
                        text = '';
                    }
                    document.getElementById("p_word").innerHTML = text;
                }
                function c_pass() {
                    // password
                    var password = document.getElementById('c_password').value;
                    if(password === '')
                    {
                       text = 'Please Fill This Field *'
                    }else{
                        text = '';
                    }
                    document.getElementById("cp_word").innerHTML = text;
                }
                function noemp() {
                    // No_of_employee
                    var no_of_employee = document.getElementById('no_of_employee').value;
                    if(no_of_employee === '')
                    {
                       text = 'Please Fill This Field *'
                    }else{
                        text = '';
                    }
                    document.getElementById("n_o_employee").innerHTML = text;
                  }
        // Registration Velidation End
                 
        // Validation For Payment
                
                  function card_holdername() {
                    // last_name validatio
                    var card_holdername = document.getElementById('card_holdername').value;
                    if(card_holdername === '')
                    {
                        text = 'Please Fill This Field *';
                        flag = false;
                    }else{
                        if(card_holdername.search(/^[a-z A-Z]*$/))
                        {
                            text = 'Please Insert Only Alphabet *';
                        }else{
                            text = '';
                        }
                    }
                    document.getElementById("c_holdername").innerHTML = text;
                }
                function card_number() {
                    // contact_number 
                    var card_number = document.getElementById("card_number").value;
                    if(card_number === '')
                    {
                        text = 'Please Fill This Field *';
                    }else{
                        if (card_number.search(/^\d{10}$/)) {
                            text = "Please Enter The Valid Number *";
                        }else{
                            text = "";
                        }
                    }
                    document.getElementById("c_number").innerHTML = text;
                }
                function card_cvv() {
                    // contact_number 
                    var card_cvv = document.getElementById("card_cvv").value;
                    if(card_cvv === '')
                    {
                        text = 'Please Fill This Field *';
                    }else{
                        if (card_cvv.search(/^\d{10}$/)) {
                            text = "Please Enter The Valid Number *";
                        }else{
                            text = "";
                        }
                    }
                    document.getElementById("c_cvv").innerHTML = text;
                }
                function card_exdt() {
                    // password
                    var card_exdt = document.getElementById('card_exdt').value;
                    if(card_exdt === '')
                    {
                       text = 'Please Fill This Field *'
                    }else{
                        text = '';
                    }
                    document.getElementById("c_exdt").innerHTML = text;
                }
                  
                  
                  
                  