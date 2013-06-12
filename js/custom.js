//INITIALIZES FLEXSLIDER
$(document).ready(function() {
	$('.flexslider').flexslider({
		animation: "slide",
		directionNav: true,
		controlNav: false, 
		easing: "swing", 
		direction: "horizontal",
		controlsContainer: ".flex-container",
	});
});
//================= End =============//

//INITIALIZES SCROLLING NAVIGATION
$(document).ready(function(){
 	$('#sidemenu').visualNav({
    	//target the .menu class in the menu
    	link : 'a.menu'
	});
});
//================= End =============//

//INITIALIZES TOOLTIP PLUGIN
$(document).ready(function() {
	$('a.tipsy').tipsy({	
		delayIn: 100,      // delay before showing tooltip (ms)
		delayOut: 500,     // delay before hiding tooltip (ms)
		fade: true,     // fade tooltips in/out?
		fallback: '',    // fallback text to use when no tooltip text
		gravity: 'e',    // gravity
		html: true,     // is tooltip content HTML?
		live: false,     // use live event support?
		offset: 15,       // pixel offset of tooltip from element
		opacity: 0.8,    // opacity of tooltip
		title: 'title',  // attribute/callback containing tooltip text
		trigger: 'hover' // how tooltip is triggered - hover | focus | manual	
	});
	
	$('.bottom-social a.tipsy').tipsy({	
		gravity: 's',    // gravity
	});
});
//================= End =============//

//INITIALIZW SLIDERS
$(document).ready(function() {
	$("#slider2").responsiveSlides({
        auto: false,
        pager: true,
        nav: false,
    	speed: 1000,
    });
	$("#slider3").responsiveSlides({
        auto: false,
        pager: false,
        nav: true,
        speed: 500,
        namespace: "callbacks",        
	});
});
//================= End =============//

//INITIALIZES PORTFOLIO/CONTACT BUTTON SCROLLING
$(document).ready(function(){
	$(".scrolltoanchor").click(function() {
		$.scrollTo($($(this).attr("href")), {
			duration: 750
		});
		return false;
	});
});
//================= End =============//

//INITIALIZES LIGHTBOX PLUGIN
$(document).ready(function(){
	$("a[rel^='prettyPhoto']").prettyPhoto({
		theme:'light_square', 
		autoplay_slideshow: false, 
		overlay_gallery: false, 
		show_title: false,
	});
});
//================= End =============//

//INITIALIZES MOSAIC PLUGIN
jQuery(function($){				
	$('.circle').mosaic({
		opacity		:	0.8			//Opacity for overlay (0-1)
	});
});
//================= End =============//

//INITIALIZES TWITTER FEED PLUGIN
$(document).ready(function() { 
	$(".tweet").tweet({
		username: "seaofclouds",//Change with your own twitter id
		//join_text: "auto",
		//avatar_size: 32,
		count: 4,
		//auto_join_text_default: "we said,",
		//auto_join_text_ed: "we",
		//auto_join_text_ing: "we were",
		//auto_join_text_reply: "we replied to",
		//auto_join_text_url: "we were checking out",
		loading_text: "loading tweets..."
	});		
});
//================= End =============//

// FOR SMART DEVICE BUTTON
jQuery(document).ready(function($){
	//prepend menu icon 
	$('#sidemenu').prepend('<div id="menu-icon">Menu</div>');
	
	//toggle nav 
	$("#menu-icon").on("click", function(){
		$("#nav").slideToggle();
		$(this).toggleClass("active");
	});
});
//================= End =============//

// FOR SKILLS GRAPH
jQuery(document).ready(function($){
								
	function isScrolledIntoView(id)
	{
		var elem = "#" + id;
		var docViewTop = $(window).scrollTop();
		var docViewBottom = docViewTop + $(window).height();
	
		if ($(elem).length > 0){
			var elemTop = $(elem).offset().top;
			var elemBottom = elemTop + $(elem).height();
		}

		return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
		  && (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
	}

	
	
	function sliding_horizontal_graph(id, speed){
		//alert(id);
		$("#" + id + " li span").each(function(i){
			var j = i + 1; 										  
			var cur_li = $("#" + id + " li:nth-child(" + j + ") span");
			var w = cur_li.attr("title");
			cur_li.animate({width: w + "%"}, speed);
		})
	}
	
	function graph_init(id, speed){
		$(window).scroll(function(){
			if (isScrolledIntoView(id)){
				sliding_horizontal_graph(id, speed);
			}
			else{
				//$("#" + id + " li span").css("width", "0");
			}
		})
		
		if (isScrolledIntoView(id)){
			sliding_horizontal_graph(id, speed);
		}
	}
	
	graph_init("services-graph", 1000);
});

//INITIALIZES CONTACT FORM
$(document).ready(function(){
	jQuery.validator.addMethod("accept", function(value, element, param) {
		return value.match(new RegExp("." + param + "$"));
		});
	jQuery("#contact_form").validate({
		meta: "validate",
		submitHandler: function (form) {
			
			var s_name=$("#name").val();
			var s_lastname=$("#lastname").val();
			var s_email=$("#email").val();
			var s_phone=$("#phone").val();
			var s_comment=$("#comment").val();
			$.post("contact.php",{name:s_name,lastname:s_lastname,email:s_email,phone:s_phone,comment:s_comment},
			function(result){
			  $('#sucessmessage').append(result);
			});
			$('#contact_form').hide();
			return false;
		},
		/* */
		rules: {
			name: {
				required: true,
				accept: "[a-zA-Z]+" 
			},
			lastname:  {
				required: true,
				accept: "[a-zA-Z]+" 
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				phoneUS: true
			},
			comment: {
				required: true
			}
		},
		messages: {
			name: {
				required: "Please enter your name.",
				accept: "Please enter a name only containing letters."
			},
			lastname:  {
				required: "Please enter your last name.",
				accept: "Please enter a last name only containing letters."
			},
			email: {
				required: "Please enter email.",
				email: "Please enter valid email."
			},
			phone: {
				required: "Please enter a phone.",
				phoneUS: "Please enter a valid phone number."
			},
			comment: "Please enter a comment."
		},
	}); /*========================================*/
});