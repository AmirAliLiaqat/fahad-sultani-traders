
/************ stop user to view sorce code with keyboard key ***************/
document.onkeydown = function(e) {
    if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
        return false;
    }
}

$("#open_more").click(function() {
    $("#show_more").css("display", "block");
});

/************ hideing alert message box ***************/
setTimeout(function() {
    $('.alert').fadeOut('fast');
}, 3000); // <-- time in milliseconds

var i = 1;
/************ repeater phone numer field for customer ***************/
$("#add_more_customer_number").click(function() {
    $("#add_more_customer_phone_number").append(`<input type='text' name='c_phone[]' class='form-control mb-1' placeholder='Customer Phone'>`);
});

/************ repeater phone numer field for shopkeeper ***************/
$("#add_more_number").click(function() {
    $("#add_more_phone_number").append(`<input type='text' name='s_phone[]' class='form-control mb-1' placeholder='Shopkeeper Phone'>`);
});

/************ repeater account detail field for shopkeeper ***************/
$("#add_more_detail").click(function() {
    $("#add_more_account_detail").append(`<input type='text' name='account_details[]' class='form-control mb-1' placeholder='Account Details'>`);
});