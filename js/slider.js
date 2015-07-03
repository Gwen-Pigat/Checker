$(document).ready(function(){

	$(".toggle").delay(1000).fadeIn(1500);

	$(".toggle").click(function(){
		$(".depenses").toggle(1000);
	});
});