$(document).ready(function(){
	$(".resources h5 .glyphicon-plus").click(function(){
		$(this).parent().parent().find("div.thumbnail").slideDown();
		$(this).parent().addClass("add");
	});
	$(".resources h5 .glyphicon-minus").click(function(){
		$(this).parent().parent().find("div.thumbnail").slideUp();
		$(this).parent().removeClass("add");
	});
	$('[data-toggle="confirmation"]').confirmation();
});