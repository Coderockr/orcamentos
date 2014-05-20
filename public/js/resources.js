function populate(type, data){
 	$('#'+ type+' .glyphicon-plus').click();
	$('#'+ type+' form #id').val(data.id);
	$('#'+ type+' form #name').val(data.name);
	$('#'+ type+' form #name').val(data.name);
	$('#'+ type+' form #cost').val(data.cost);
	$('#'+ type+' form #type option:contains(' + data.type.name + ')').attr( "selected", "selected" );
	if ( data.equipmentLife != null ){
		$('#'+ type+' form #equipmentLife').val(data.equipmentLife);
	}
}

var columns = [];
var resources = [];

$(document).ready(function(){
	$.ajax({
		type: "GET",
		url: "/resource/get",
		dataType: "json",
		success: function( dados )
		{
			var column = 0;
			for ( type in dados ) {
				columns.push(type);
				for ( var row = 0 ; row < dados[type].length; row ++ ) {
					if (window.resources[column] === undefined) {
						window.resources[column] = [];
					}

					var data = new Object(dados[type][row]);

					window.resources[column].push(data);

					var template;
					var thumbnail = $('<span class="thumbnail" rel="'+ data.id + '">');
					var caption = $('<div class="caption">');
					var anchor = $('<a data-href="/resource/delete/'+ data.id +'" data-toggle="confirmation" class="glyphicon glyphicon-remove edit" title="Apagar recurso">');
					var edit = $('<a href="#" title="Editar projeto" class="edit" attr-col="'+column+'" attr-row="'+row+'"><i class="glyphicon glyphicon-pencil"></i>editar</a>');
					
					anchor.on('click', anchor.confirmation());
					
					$(edit).on('click', function(){ 

						populate(
							columns[parseInt($(this).attr('attr-col'))], 
							resources[parseInt($(this).attr('attr-col'))][parseInt($(this).attr('attr-row'))]
						); 
						return false; 
					});
					var cost = data.cost.toString();
					cost = cost.replace(".", ",");

					template = thumbnail.append(
						caption.append(
							$('<h4>').append(data.name),
							$('<p>').append("R$ "+ cost +"<br>" + data.type.name)
						),
						edit,
						anchor
					);
					$("#" + type).append(template);
				}
				column++;
			}

		},
		error: function( data )
		{
			console.log(data);
		}
	});

	$(document).on( 'submit', ".company .resources .form", function(){
		var dados = $( this ).serialize();
		var form = this;
		$.ajax({
			type: "POST",
			url: "/resource/create",
			data: dados,
			dataType: "json",
			success: function( data )
			{

				column = $(form).parent().parent().attr('id');
				n = parseInt(columns.indexOf(column));
				var row = 0;

				if ( window.resources[n]) {
					row = window.resources[n].length;
				}

				if ( $('.thumbnail[rel='+data.id+']').length > 0 ) {
					$('.thumbnail[rel='+data.id+'] h4').html(data.name);
					$('.thumbnail[rel='+data.id+'] p').html("R$ "+ data.cost +"<br>" + data.type.name);
				} else {
					var template;
					var thumbnail = $('<span class="thumbnail" rel="'+ data.id + '">');
					var caption = $('<div class="caption">');
					var anchor = $('<a data-href="/resource/delete/'+ data.id +'" data-toggle="confirmation" class="glyphicon glyphicon-remove edit" title="Apagar recurso">');
					anchor.on('click', anchor.confirmation());
					var edit = $('<a href="#" title="Editar projeto" class="edit" attr-col="'+n+'" attr-row="'+row+'"><i class="glyphicon glyphicon-pencil"></i>editar</a>');
					
					if (!window.resources[n]){
						window.resources[n] = [];
					}
				
					window.resources[n].push(data);
					
					$(edit).on('click', function(){ 
						populate(
							columns[parseInt($(this).attr('attr-col'))], 
							resources[parseInt($(this).attr('attr-col'))][parseInt($(this).attr('attr-row'))]
						); 
						return false; 
					});

					var cost = data.cost.toString();
					cost = cost.replace(".", ",");
					template = thumbnail.append(
						caption.append(
							$('<h4>').append(data.name),
							$('<p>').append("R$ "+ cost +"<br>" + data.type.name)
						),
						edit,
						anchor
					);
					
					$(form).parent().parent().append(template);
				}			
				$(form)[0].reset();
				$(".resources h5 .glyphicon-minus").click();

			},
			error: function( data )
			{
				console.log(data);
			}
		});
		return false;
	});	
});