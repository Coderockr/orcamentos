$(document).ready(function(){
	
	$(".resources h5 .glyphicon-plus").click(function(){
		$(this).parent().parent().find("div.thumbnail").slideDown();
		$(this).parent().addClass("add");
		return false;
	});
	
	$(".resources h5 .glyphicon-minus").click(function(){
		$(this).parent().parent().find("div.thumbnail").slideUp();
		$(this).parent().removeClass("add");
		$(this).parent().parent().find('form')[0].reset();
		$(this).parent().parent().find('#id')[0].value ="";
		return false;
	});

	$('[data-toggle="confirmation"]').confirmation();

	$('#telephone').mask("(99) 9999-99999");

	$(document).on( 'click', ".quote .resources .btn-success", function(){
		var container = $(this).parent().parent().parent().parent().parent();
		var amount = container.find('#amount');
		var resource = container.find('#resource');
		
		if ($(amount).val() > 0){

			var template;
			var thumbnail = $('<span class="thumbnail">');
			var caption = $('<div class="caption">');
			var anchor = $('<a class="glyphicon glyphicon-remove edit" title="Apagar recurso">');
			var hidden = $('<input type="hidden" name="quoteResource['+$.trim($(resource).val())+']" value="'+$(amount).val()+'">');

			template = thumbnail.append(
				hidden,		
				caption.append(
					$('<h4>').append($(resource).find('option:selected').text()),
					$('<p>').append($(amount).val() + " h")
				),
				anchor
			);
			container.append(template);
		}
		$('input[type=text]').val("");
		$(".resources h5 .glyphicon-minus").click();
		
		return false;
	});	

	$(document).on( 'click', ".quote .resources .glyphicon-remove", function(){
		$(this).parent().remove();
	});

	$(document).on( 'click', "#share .input-group-btn .btn", function(){
		var email = $("#share #email").val();
		var list = $("#share .list-group");
		if ( email.length > 0 ) {
			list.append(
				$('<li class="list-group-item">').append(
					$('<input type="hidden" name="email[]" value="'+ email +'"/>'),
					$('<a href="#" class="glyphicon glyphicon-remove pull-right" title="Apagar email"></a>'),
					email
				)
			);
			$("#share #email").val("");
		}
		return false;
	});	

	$(document).on( 'submit', "#share form", function(){
		var dados = $( this ).serialize();
		var form = this;
		$.ajax({
			type: "POST",
			url: "/share/create",
			data: dados,
			dataType: "json",
			success: function( data )
			{
				$("#share input[type=hidden]").remove();
				$("#share .glyphicon-remove").remove();
				$('#share').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
			},
			error: function( data )
			{
				console.log(data);
			}
		});
		return false;
	});	

	$(document).on( 'click', "#share .glyphicon-remove", function(){
		$(this).parent().remove();
	});

	$(document).on( 'submit', "#shareNote", function(){
		var dados = $( this ).serialize();
		var form = this;
		$.ajax({
			type: "POST",
			url: "/share/comment",
			data: dados,
			dataType: "json",
			success: function( data )
			{
				var comments = $('.comments');
				var d = new Date();
				var day = d.getDate();
				if ( day < 10 ) {
					day = "0"+day;
				} 
				var month = (d.getMonth()+1);
				if ( month < 10 ){
					month = "0" + month;
				}
				var year = d.getFullYear();
				var hour = d.getHours();
				if ( hour < 10 ){
					hour = "0" + hour;
				}
				var minute = d.getMinutes();
				if ( minute < 10 ){
					minute = "0" + minute;
				}
				var second = d.getSeconds();
				if ( second < 10 ){
					second = "0" + second;
				}
				var comment = $('<div class="media">');
				comment.append(
					$('<div class="media-body">').append(
						$('<h4 class="media-heading">').append(
							data.email + " -  <small class='text-info'> criado em "+ day +"/" + month + '/' + year + " "+ hour + ":" + minute +":" + second + "</small>"
						),
						data.comment
					)
				)
				comments.prepend(comment);
				$("#note").val('');
			},
			error: function( data )
			{
				console.log(data);
			}
		});
		return false;
	});	
	
	$(document).on( 'submit', "#privateNote", function(){
		var dados = $( this ).serialize();
		var form = this;
		$.ajax({
			type: "POST",
			url: "/project/comment",
			data: dados,
			dataType: "json",
			success: function( data )
			{
				var comments = $('.comments');
				var d = new Date();
				var day = d.getDate();
				if ( day < 10 ) {
					day = "0"+day;
				} 
				var month = (d.getMonth()+1);
				if ( month < 10 ){
					month = "0" + month;
				}
				var year = d.getFullYear();
				var hour = d.getHours();
				if ( hour < 10 ){
					hour = "0" + hour;
				}
				var minute = d.getMinutes();
				if ( minute < 10 ){
					minute = "0" + minute;
				}
				var second = d.getSeconds();
				if ( second < 10 ){
					second = "0" + second;
				}
				var comment = $('<div class="media">');
				comment.append(
					$('<div class="media-body">').append(
						$('<h4 class="media-heading">').append(
							data.name + ' - ' + data.email + " -  <small class='text-info'> criado em "+ day +"/" + month + '/' + year + " "+ hour + ":" + minute +":" + second + "</small>"
						),
						data.note
					)
				)
				comments.prepend(comment);
				$("#note").val('');
			},
			error: function( data )
			{
				console.log(data);
			}
		});
		return false;
	});	
});