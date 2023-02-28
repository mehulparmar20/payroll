let price = "";
let plan_id = "";
$(document).ready(function () {
  if (location.pathname.substring(1) == 'wallet.php') {
    $.ajax({
      type: "POST",
      url: "Master.php",
      data: {
        main: "payment",
        sub: "plans"
      },
      success: function (response) {
        const res = JSON.parse(response);
        const data = res['plan'];
        const plans = Object.keys(data);
        price = res['price'];
        var isPlanSelected = checkPlanId();
        console.log(isPlanSelected);
        if (isPlanSelected['counter'] > 0) {
          updatePrice(isPlanSelected['plan']);
        }
        for (let i = 0; i < plans.length; i++) {
          var html = "";
          var priceArray = updatePrice(plans[i]);
          var type = priceArray['type'];
          var data_parent = priceArray['data_parent'];
          const features = Object.keys(data[type]);
          for (let j = 0; j < features.length; j++) {
            html += `<div class="price-features">
                              <div class="card-header" id="headingOne${i}${j}">
                                  <h2 class="mb-0">
                                      <button class="d-flex align-items-center btn btn-link ${j == 0 ? "" : "collapsed"}" onclick = "toggleFeature(this)" data-toggle="collapse"
                                          data-target="#collapseOne${i}${j}" aria-expanded="true" aria-controls="collapseOne">
                                          <span class="fa-stack fa-sm">
                                              <i class="fas fa-circle fa-stack-2x"></i>
                                              <i class="fas ${j == 0 ? "fa-minus" : "fa-plus"} fa-stack-1x fa-inverse"></i>
                                          </span>
                                          ${features[j]}
                                      </button>
                                  </h2>
                              </div>
                              <div id="collapseOne${i}${j}" class="collapse ${j == 0 ? "show" : ""}" aria-labelledby="headingOne${i}${j}"
                                  data-parent="#${data_parent}">
                                  <div class="card-body">
                                      <ul class="mylist">`;
            var list = data[type][features[j]];
            var keylist = Object.keys(list);
            for (let k = 0; k < keylist.length; k++) {
              html += `<li style="margin-left: -42px;" ><i class="mdi mdi-check-decagram text-success mr-3" style="font-size:18px;vertical-align:middle"></i>${keylist[k]}<sub>${typeof (list[keylist[k]]) == 'object' ? "" : list[keylist[k]] == "" ? "" : " (" + list[keylist[k]] + ")"}</sub></li>`;
              if (typeof (list[keylist[k]]) == 'object') {
                html += `<ul>`;
                var sublist = list[keylist[k]];
                var sublistkeys = Object.keys(list[keylist[k]]);
                for (let t = 0; t < sublistkeys.length; t++) {
                  html += `<li style=" margin-left: -33px;" >${sublistkeys[t]} </li>`;
                }
                html += `</ul>`;
              }
            }
            html += `</ul>
                                  </div>
                              </div>
                          </div>`;
          }
          if (type == "Carrier") {
            $('#carrier-plan').html(html);
          } else if (type == "Trucker") {
            $('#trucker-plan').html(html);
          } else if (type == "Broker") {
            $('#broker-plan').html(html);
          }
        }


      }
    })
  }
  if (location.pathname.substring(1) == 'addon.php') {
    $.ajax({
      type: "POST",
      url: "Master.php",
      data: {
        main: "payment",
        sub: "addons"
      },
      success: function (response) {
        const data = JSON.parse(response);
        var carddata = '';
        for (let i = 0; i < data.length; i++) {
          var res = data[i].name.replace(" ", "_");
          carddata += `<div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 ">
   	                    <div class="info-stats2">
   	                        <div class="col-md-3">
   	                            <div class="info-icon " style="background: linear-gradient(135deg, #0401dae8, #f00a0aba);">
   	                                <i class="mdi mdi-account-badge-horizontal-outline"></i>
   	                            </div>
  	                        </div>

  	                        <div class="sale-num col-md-9">
  	                            <h5>${data[i].name}</h5>
  	                            <p>${data[i].plandiscription}
  	                            </p>
  	                            <div class="ks-cboxtags">
  	                                <li>
  	                                    <input type="checkbox" name="" id="${res}">
  	                                    <label class="top-up" for="${res}"> $${data[i].discountprice}</label>
  	                                </li>
  	                            </div>
  	                        </div>
  	                    </div>
  	                </div>`;
        }
        $("#addons-cards").html(carddata);
      }
    })
  }
})
$(".chb").change(function () {
  $(".chb").prop('checked', false);
  $(this).prop('checked', true);
  let total = 0;
  if ($(this).prop('id') == "brokerpro") {
    total = price['Broker']['discount_price'].split(".");
    plan_id = price['Broker']['plan'];
  } else if ($(this).prop('id') == "carrierpro") {
    total = price['Carrier']['discount_price'].split(".");
    plan_id = price['Carrier']['plan'];
  } else if ($(this).prop('id') == "truckerpro") {
    total = price['Trucker']['discount_price'].split(".");
    plan_id = price['Trucker']['plan'];
  }

  $('#plan-total').html(`<h4>Total : $${total[0]} <sub>.${total[1]}</sub></h4>`);
});
function toggleFeature(e) {
  $(e)
    .find("i:last-child")
    .toggleClass("fa-minus fa-plus");
}


function openNav() {
  document.getElementById("mySidenav").style.width = "30%";
}

function openright() {
  document.getElementById("sideright").style.width = "30%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

function closeright() {
  document.getElementById("sideright").style.width = "0";
}

// Handle any errors returned from Checkout
var handleResult = function (result) {
  if (result.error) {
    var displayError = document.getElementById("error-message");
    displayError.textContent = result.error.message;
  }
};



fetch("stripe/config.php")
  .then(function (result) {
    return result.json();
  })
  .then(function (json) {
    var publishableKey = json.publishableKey;
    var stripe = Stripe(publishableKey);
    document
      .getElementById("checkout-button")
      .addEventListener("click", function (evt) {

        if (plan_id == "") {
          let counter = checkPlanId();
          if (counter['counter'] == 0) {
            swal("Select atleast one plan.");
            return;
          }

        }
        var data = {
          priceId: plan_id
        }
        $.ajax({
          url: "Master.php",
          type: "POST",
          data: {
            main: "payment",
            sub: "create-checkout",
            data: data
          },
          success: function (data) {
            stripe
              .redirectToCheckout({
                sessionId: data.trim()
              })
          }
        })
      });
  });
function checkPlanId() {
  let counter = 0;
  let plan = "";
  var planArray = document.getElementsByName('select_plan');
  for (let i = 0; i < planArray.length; i++) {
    if (planArray[i].checked) {
      counter++;
      var id = planArray[i].getAttribute('id');
      if (id == "brokerpro") {
        plan = "Broker";
        plan_id = price['Broker']['plan'];
      } else if (id == "carrierpro") {
        plan = "Carrier";
        plan_id = price['Carrier']['plan'];
      } else if (id == "trukerpro") {
        plan = "Trucker";
        plan_id = price['Trucker']['plan'];
      }
    }
  }
  return { counter: counter, plan: plan };
}
function updatePrice(plan) {
  let type = "";
  let data_parent = "";
  if (plan == "Carrier") {
    type = "Carrier";
    var sub = price['Carrier'].discount_price.split('.');
    $("#carrier-price").html(`<s style="font-size:24px">$${price['Carrier']['price']}</s><span class="pricing-currency">$</span>${sub[0]}<sub style="font-size:30%">.${sub[1]}</sub>
                 <span class="pricing-period">/ Month</span>`);
    data_parent = "carrier-plan";
  } else if (plan == "Trucker") {
    type = "Trucker";
    var sub = price['Trucker'].discount_price.split('.');
    $("#trucker-price").html(`<s style="font-size:24px">$${price['Trucker']['price']}</s><span class="pricing-currency">$</span>${sub[0]}<sub style="font-size:30%">.${sub[1]}</sub>
                      <span class="pricing-period">/ Month</span>`);
    data_parent = "trucker-plan";
  } else if (plan == "Broker") {
    type = "Broker";
    var sub = price['Broker'].discount_price.split('.');
    $("#broker-price").html(`<s style="font-size:24px">$${price['Broker']['price']}</s><span class="pricing-currency">$</span>${sub[0]}<sub style="font-size:30%">.${sub[1]}</sub>
                      <span class="pricing-period">/ Month</span>`);
    data_parent = "broker-plan";
  }
  return { type: type, data_parent: data_parent };
}


