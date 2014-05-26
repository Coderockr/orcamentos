$(document).ready(function(){
	
	nicEditors.allTextAreas();

	$('form').h5Validate();
	
	var nicedit = $(".nicEdit-main");
	
	if( nicedit.length == 0 || nicedit.html().length == 0 ){
		$(".nicEdit-main").text('');
	} 

	$(".resources h5 .glyphicon-plus").click(function(){
		$(this).parent().parent().find("div.thumbnail").slideDown();
		$(this).parent().addClass("add");
		return false;
	});
	
	$(document).on('click',".resources h5 .glyphicon-minus",function(){
		$(this).parent().parent().find("div.thumbnail").slideUp();
		$(this).parent().removeClass("add");
		var form = $(this).parent().parent().find('form');
		$(form)[0].reset();
		$(form).find('#id').val('');
		return false;
	});

	$('[data-toggle="confirmation"]').confirmation();

	$('#telephone').mask("(99) 9999-99999");
	
	$("#dueDate").datepicker({
	    dateFormat: 'dd/mm/yy',
	    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
	    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
	    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
	    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
	    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
	    nextText: 'Próximo',
	    prevText: 'Anterior'
	});

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
		container.find('.glyphicon-minus').click();
		container.find('input[type=text]').val("");
		
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

	$(document).on( 'submit', "#new_project", function(){
		var notes = $(" #new_project .nicEdit-main ").html();
		if ( notes.length == 0 ){
			$(" #new_project .nicEdit-main ").parent().addClass('ui-state-error');
			return false;
		}
	});	

	$(document).on( 'submit', "#share form", function(){
		var dados = $( this ).serialize();
		var form = this;
		$('#share button[type=submit]').button('loading');
		$.ajax({
			type: "POST",
			url: "/share/create",
			data: dados,
			dataType: "json",
			success: function( data )
			{
				$('#share button[type=submit]').button('reset');
				for ( i in data ) {
					$('.list-group-item:contains("'+ data[i]['email']+'") a').attr('rel', data[i]['id']);
					$('.list-group-item:contains("'+ data[i]['email']+'") ').append($("<div class='hidden-info margin5'>").append(
						"<a href='#' class='pull-right btn btn-primary share-status small'><i class='marginr glyphicon glyphicon-envelope'></i>Enviar via email</a>",
						"<span class='share-status'>" + data[i]['shortUrl'] + "</span>"
					));
					$('.list-group-item:contains("'+ data[i]['email']+'") input[type=hidden]').remove();
				}
				$("#share .list-group-item input[type=hidden]").parent().remove();
				// $('#share').modal('hide');
				// $('body').removeClass('modal-open');
				// $('.modal-backdrop').remove();
				$('.hidden-info').slideDown();
			},
			error: function( data )
			{
				console.log(data);
			}
		});
		return false;
	});	
	
	$(document).on( 'click', "#share a.share-status", function(){
		var id = $(this).parent().parent().find('a.glyphicon-remove').attr('rel');
		$(this).parent().find('a.share-status').remove();
		$.ajax({
			type: "POST",
			url: "/share/resend",
			data: { shareId : id },
			dataType: "json",
			success: function( email )
			{
				$('.list-group-item:contains("'+ email+'") div').append("<span class='pull-right share-status'>Aguardando envio</span>");
			},
			error: function( email )
			{
				console.log(email.responseText);
			}
		});
	});	

	$(document).on( 'click', "#share .glyphicon-remove", function(){
		if ($(this).parent().find('input[type=hidden]').length == 0) {
			var shareId = $(this).attr('rel');
			$(this).hide();
			$.ajax({
				type: "GET",
				url: "/share/delete/" + shareId,
				dataType: "json",
				success: function( data )
				{	
					$('[rel='+shareId+']').parent().remove();
				},
				error: function( data )
				{
					console.log(data.responseText);
				}
			});
		} else {
			$(this).parent().remove();
		}
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
						$('<a href="/share/removeComment/'+ data.id + '"  class="glyphicon glyphicon-remove pull-right" title="Apagar nota"></a>'),
						$('<h4 class="media-heading">').append(
							data.email + " -  <small class='text-info'> criado em "+ day +"/" + month + '/' + year + " "+ hour + ":" + minute +":" + second + "</small>"
						),
						data.comment
					)
				)
				comments.prepend(comment);
				$(".nicEdit-main").text('');
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
						$('<a href="/project/removeComment/'+ data.id + '"  class="glyphicon glyphicon-remove pull-right" title="Apagar nota"></a>'),
						$('<h4 class="media-heading">').append(
							data.name + ' - ' + data.email + " -  <small class='text-info'> criado em "+ day +"/" + month + '/' + year + " "+ hour + ":" + minute +":" + second + "</small>"
						),
						data.note
					)
				)
				comments.prepend(comment);
				$(".nicEdit-main").text('');
			},
			error: function( data )
			{
				console.log(data);
			}
		});
		return false;
	});	


});