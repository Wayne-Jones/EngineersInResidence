//AJAX Call to a PHP File to query a database and return results
var ajax_url = 'php/ajax_handler.php';
var ajax_overlay = 'php/ajax_overlay.php';


$(document).ready(function() {
	//OnLoad, Load Residents and Overlay HTML Code
	loadResidents();
	loadOverlay();
	//$("#peopleThumbs .peoplesection a[rel]").overlay();
	//Ajax script to bind submit function to sending an ajax request to the backend without refreshing the webpage
	$("#eng-search").bind('submit',function() {
	var value = $('#searchResidents').val();
	$.post(ajax_url,{value:value}, function(data){
		$("#peopleThumbs").html(data);
		});
		return false;
	});
	
	$('a.viewOverlay').live('click', function(){
		
		var engID = $(this).attr('rel');
		$('#Bio'+engID).fadeIn("fast",function(){
			$(this).addClass("loaded");
		});
	});
	$('a.viewFacultyOverlay').live('click', function(){
		
		var facID = $(this).attr('rel');
		$('#BioFac'+facID).fadeIn("fast",function(){
			$(this).addClass("loaded");
		});
	});
	
	$('.closeButton').live('click', function(){
		$(this).parent().parent().parent().removeClass("loaded").fadeOut('fast');
	});
	/*$('.simpleOverlay').live('click', function(){
		if ($(this).hasClass("loaded")){
			$(this).removeClass("loaded").fadeOut('fast');
		} 
		else {
		}
	});	*/
	
}); //Ready
function loadResidents(){
	var value = $('#searchResidents').val();
	$.post(ajax_url,{value:value}, function(data){
		$("#peopleThumbs").html(data);
		});
}
function loadOverlay(){
	$.post(ajax_overlay, function(data){
		$("#overlayContainer").html(data);
	});
}