var ids = "";
var page_number = 1;
var ajax_url = 'php/ajax_handler.php';

$(document).ready(function() {
//clear search field for People section
	$('#searchResidents').focus(function(){
		$(this).removeClass('blink');
		var content = $(this).val();
		var placeholder = $(this).attr("placeholder");
		if(content==placeholder){
			$(this).val("")
		}
	});
	$('#searchResidents').blur(function(){
		var content = $(this).val();
		var nothing = "" ;
		if(content == nothing){
			$(this).addClass('blink').val(placeholder);
		}
	});
	//load overlay
	$('a.viewBioContainer').live('click', function(){
		var myIndex = $(this).parent().index();
		$('#peopleBios').find('span:eq('+myIndex+')').fadeIn("fast",function(){
			$(this).addClass("fullBioLoaded");
		});
	});
	//unload overlay when xButton is clicked
	$('.xButton').live('click', function(){
		$(this).parent().parent().removeClass("fullBioLoaded").fadeOut('fast');
	});
	//unload overlay when background is clicked
	$('.fullBio').click(function(){
		if ($(this).hasClass('fullBioLoaded')){
			$(this).removeClass("fullBioLoaded").fadeOut('fast');
		} else {
	//nothing
	}
	});	
	
	$('.xButton2').live('click', function(){
		$(this).parent().parent().fadeOut('fast');
	});
	
	$('#frmSearch').submit(function(){
		searchProfiles($('#searchResidents').val());
		return false;
	});
	
	$('.hoverable').click(function() {
		$('.hoverable').each(function() {
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');
		$('#searchResidents').val($(this).text());
		searchProfiles($(this).text());
	});	
	
	$('.viewMore').click(function(){
		viewMore();
		return false;
	});
	searchProfiles('');
});//ready
function searchProfiles(search_term) {
	ids = "";
	page_number = 1;
	$.post(ajax_url
		,{
			"action":"search",
			"search_term":search_term,
			"page_number":page_number
		}
		,function(data){
			$(".viewBioContainer", data).each(function() {
				ids += $(this).attr('id') + ",";
			});			
			$('#peopleThumbs').html(data);
			getBios();
		}
		,"html");
}

function viewMore() {
	ids = "";
	page_number++;	
	$.post(ajax_url
		,{
			"action":"viewmore"
			,
			"page_number":page_number
		}
		,function(data){
			$(".viewBioContainer", data).each(function() {
				ids += $(this).attr('id') + ",";
			});						
			$('#peopleThumbs').html(data);
			getBios();
		}
		,"html");
}

function getBios() {
	$.post(ajax_url
		,{
			"action":"getbio",
			"ids":ids
		}
		,function(data){
			$('#peopleBios').html(data);
		}
		,"html");
}