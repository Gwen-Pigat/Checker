$(document).ready(function(){

	$(".toggle").fadeIn(100);
	$("#button").delay(2000).fadeIn(1000);
	$("#main").show(1000);
	$("#loading").delay(2000).fadeOut(100);


	$(".toggle").click(function(){
		$(".depenses").toggle(1000);
	});
});