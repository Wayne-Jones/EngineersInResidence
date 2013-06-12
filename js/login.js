// Login Form

$(function() {
    var button = $('#loginButton');
    var box = $('#loginBox');
    var form = $('#loginForm');
    button.click(function(login) {
		if(box.hasClass('active')){
			box.hide("slow");
			box.removeClass('active');
		}
        else{
			box.addClass('active');
			box.show("fast");
		}
    });
    form.mouseup(function() { 
        return false;
    });
    /*$(this).mouseup(function(login) {
        if(!($(login.target).parent('#loginButton').length > 0)) {
			button.removeClass('active');
            box.hide();
        }
    });*/
});
