$(document).on("change","#total-card", function(){
    const quntity = this.value;
    const price = 40 * quntity;
    $("#cards-quintity").html(quntity);
    $("#cards-total").html(price);
});