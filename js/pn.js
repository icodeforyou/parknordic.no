function apply_for_rent (lotid) {
	
	$.ajax({
		url: '/ajax/ajax_pn.php',
		type: 'post',
		data: {
			"ajax-action" : "load-lot",
			"lotid" : lotid
		},
		success: function(data) {
			//console.log(data);
			if(data.ok == "1") {
				
				var $modal = $('<div/>')
					.attr({
						"class" : "apply-modal modal fade in",
						"role" : "dialog",
						"style" : "width: 510px"
					});

				var $modal_header = $('<div/>')
					.attr({
						"class" : "modal-header"
					})
					.appendTo($modal);

				var	$closebtn = $('<button/>')
					.attr({
						"class" : "close",
						"type" : "button",
						"aria-hidden" : "true",
						"data-dismiss" : "modal"
					})
					.html("&times;")
					.appendTo($modal_header);

				var $header = $('<h2/>')
					.text(data.lot.Address)
					.appendTo($modal_header);

				var $modal_body = $('<div/>')
					.html("<p>Her kan du søke om langtidsparkering på: <strong>"+ data.lot.Address + "</strong></p>")
					.attr({
						"class" : "modal-body"
					})
					.css({"max-height" : "440px"})
					.appendTo($modal);



				var $form = $('<form/>')
					.attr({
						"action" : window.location,
						"method" : "post"
					})
					.css({ 
						"margin" : 0
					})
					.appendTo($modal_body);

				var $form_markup = $('<div class="row">\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="navn">Navn</label>\
						    <div class="controls">\
						      <input type="text" id="navn" name="Navn" class="required" placeholder="Navn">\
						    </div>\
						  </div>\
						</div>\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Postadresse">Postadresse</label>\
						    <div class="controls">\
						      <input type="text" id="Postadresse" name="Postadresse" class="required" placeholder="Postadresse">\
						    </div>\
						  </div>\
						</div>\
					</div>\
					<div class="row">\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Postnr">Postnr og sted</label>\
						    <div class="controls">\
						      <input type="text" id="Postnr" name="Postnr" class="required" placeholder="Postnr og sted">\
						    </div>\
						  </div>\
						</div>\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Orgnr">Orgnr / fødselsnr</label>\
						    <div class="controls">\
						      <input type="text" id="Orgnr" name="Orgnr" class="required number" placeholder="Orgnr / fødselsnr">\
						    </div>\
						  </div>\
						</div>\
					</div>\
					<div class="row">\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Epost">Epost</label>\
						    <div class="controls">\
						      <input type="text" id="Epost" name="Epost" class="required email" placeholder="Din Epost">\
						    </div>\
						  </div>\
						</div>\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Telefon">Telefon</label>\
						    <div class="controls">\
						      <input type="text" id="Telefon" name="Telefon" class="required" placeholder="Telefon">\
						    </div>\
						  </div>\
						</div>\
					</div>\
					<div class="row">\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Sted">Sted</label>\
						    <div class="controls">\
						      <input type="text" id="Sted" name="Sted" placeholder="Sted" value="' + data.lot.Address +'">\
						    </div>\
						  </div>\
						</div>\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="control-label" for="Kommentar">Kommentar</label>\
						    <div class="controls">\
						      <textarea name="Kommentar" cols="10" style="width:95%"></textarea>\
						    </div>\
						  </div>\
						</div>\
					</div>\
					<div class="row">\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="radio">\
						      <input class="required" id="Betalingstype" name="Betalingstype" value="Måned" type="radio" checked="checked"> Måned faktura\
						    </label>\
						  </div>\
						</div>\
						<div class="span3">\
						  <div class="control-group">\
						    <label class="radio">\
						      <input class="required" id="Betalingstype" name="Betalingstype" value="Kvartalsvis" type="radio"> Kvartalsvis faktura\
						    </label>\
						  </div>\
						</div>\
					</div>\
					<div class="row">\
						<div class="span6">\
						  <div class="control-group">\
						    <label class="checkbox">\
						      <input class="required" id="terms" name="terms" value="1" type="checkbox"> Ja, jeg har lest og aksepterer <a title="Klikk for å laste ned og lese våre vilkår og betingelser" href="/doc/ParkNordic_Vilkaar_revidert_140113.pdf" target="_blank">vilkårene</a> for leie av parkeringsplass\
						    </label>\
						  </div>\
						</div>\
					</div>\
					<div class="row">\
						<div class="span6">\
						  <div class="control-group">\
						    <div class="controls">\
						      <button type="submit" class="btn btn-primary pull-right">Send Inn</button>\
						    </div>\
						  </div>\
						</div>\
					</div>')
				.appendTo($form);

				var $inputs = $('<input type="hidden" name="-action" value="apply"><input type="hidden" name="uri" value="hvor-er-vi">').appendTo($form);

				$form.bind("submit", function() {
					if($form.validate().form() === true) {
						return true;
					} else {

						var height = $modal.height();
						var bodyheight = $modal_body.height();

						//if(bodyheight > height) {
							/*
							$(".apply-modal").animate({
								"height" : bodyheight + 10
							}, 600);
							*/
						
							$modal_body.animate({
								"max-height" : bodyheight + 100
							}, 600);
						//}

						return false;
					}
				})

				$modal.modal('show');

			} else {
				alert("Feil");
			}
			
		}
	});

	
	/*
	var $header = $('<h4/>')
					.text(data.header_text)
					.appendTo($modal);
	var $desc_text = $('<p/>')
						.text(data.msg)
						.appendTo($modal);

	var $control = $('<div/>')
		.attr({
			"class" : "controls",
			"id" : "login-inputs"
		})
		.appendTo($modal);

	var $form = $('<form/>')
					.attr({
						"action" : window.location,
						"method" : "post"
					})
					.css({ 
						"margin" : 0
					})
					.appendTo($control);

	var $input = $('<input type="password" name="password" class="span2 required clearfix"><input type="hidden" name="email" value="' + inputVal + '"><input type="hidden" name="-action" value="login">').appendTo($form);

	var $btn = $('<button/>')
				.attr({
					"type" : "submit",
					"class" : "btn btn-primary pull-right"
				})
				.text(data.btn_text)
				.appendTo($form);

	var $cancel_btn = $('<button/>')
				.attr({
					"type" : "submit",
					"onClick" : "killModal('login-modal'); return false;",
					"class" : "btn pull-right",
					"style" : "margin-right:5px;"
				})
				.text(data.btn_cancel_text)
				.appendTo($form);


	var $helptext = $('<span/>')
					.addClass("help-block")
					.text(data.help_text)
					.appendTo($control);

	*/

//	$modal.modal('show');
}

function killModal (classattr) {
	$("." + classattr).modal("hide");
	$("." + classattr).on('hidden', function () {
		$("." + classattr).remove();
	});
}
function editLot(address, lng, lat, description, longtime, newcity, city, lotid) {
	killModal('modal');
	
	$.ajax({
		url: '/ajax/ajax_pn.php',
		type: 'post',
		data: {
			"ajax-action" : "edit-lot",
			"city" : city,
			"address" : address,
			"lng" : lng,
			"lat" : lat,
			"description" : description,
			"longtime" : longtime,
			"lotid" : lotid,
			"newcity" : newcity
		},
		success: function(data) {
			//console.log(data);
			if(data.ok == "1") {
				window.location = "/admin/";
			} else {
				alert("Feil");
			}
			
		}
	});
}
function createNewLot (address, lng, lat, description, longtime, newcity, city) {
	
	killModal('modal');
	
	$.ajax({
		url: '/ajax/ajax_pn.php',
		type: 'post',
		data: {
			"ajax-action" : "save-lot",
			"city" : city,
			"address" : address,
			"lng" : lng,
			"lat" : lat,
			"description" : description,
			"newcity" : newcity,
			"longtime" : longtime
		},
		success: function(data) {
			//console.log(data);
			if(data.ok == "1") {
				window.location = "/admin/";
			} else {
				alert("Feil");
			}
			
		}
	});
}

function preCheckLot () {
	
	if($("#parking-lot-form").validate().form() === true) {
		
		var city = $("#city").val(),
			address = $("#address").val(),
			lng = $("#lng").val(),
			lat = $("#lat").val(),
			description = $("#description").val(),
			longtime = $("#longtime").is(":checked") == "1" ? 1 : 0;
		
			$.ajax({
				url: '/ajax/ajax_pn.php',
				type: 'post',
				data: {
					"ajax-action" : "check-city",
					"cityname" : city
				},
				success: function(data) {
					console.log(data);
					if(data.ok == "1") {

						if($("#-mode").val() == "edit") {
							editLot(address, lng, lat, description, longtime, 0, data.cityid, $("#-edit").val());
						} else {
							createNewLot(address, lng, lat, description, longtime, 0, data.cityid);
						}
								
					} else {

						//var callbackFunc = function() { createNewLot(address, lng, lat, description, longtime, 1, data.city); };

						alertModal('Denne byen (' + data.city +') er ikke allerede, vil du lage denne byen når du lagrer en ny parkeringsplass?', address, lng, lat, description, longtime, data.city );
						
					}
				}
			});

	} else {
		return false;
	}

}

function preCheckKredinor() 
{
	if($("#kredinor-lot-form").validate().form() === true) {
		$("#kredinor-lot-form").submit();
	} else {
		return false;
	}
}

function showModal(modal) {
	$("." + modal).show();
}

function hideModal(modal) {
	$("." + modal).hide();
}

function slideModal (modal) {

	if($("." + modal).find("#-mode").val() == "edit") {
		
		if(modal == "kredinor-modal") {
			$("." + modal).find("h2").text("Opprett ny Kredinor post");
		} else {
			$("." + modal).find("h2").text("Oprett ny parkeringplass");
		}
		

		$("." + modal).find("input, textarea").each(function() {
			$this = $(this);
			if ($this.is("input")) {
				$this.val('');
			} else if ($this.is("textarea")) {
				$this.val('');
			} 
			if ($this.is(":checkbox")) {
				$this.removeAttr("checked");
			}

			$("#-mode").val('new');
			
		});

		$("#Dokumenter ul").html('');

	}
	
	$("." + modal).slideToggle();
}

function delFile (lotid, fileid) {

	if(confirm("Er du sikker på at du vil fjerne filen?")) {
		$.ajax({
			url: '/ajax/ajax_pn.php',
			type: 'post',
			data: {
				"ajax-action" : "del-file",
				"fileid" : fileid,
				"lotid" : lotid
			},
			success: function(data) {
			
				if(data.ok == "1") {
					$("#fileid_" + fileid).remove();
				} else {
					alert("Det oppstod en feil, kunne vi ikke slette filen.");
				}
			}
		});
	}

	
}

function alertModal (text, address, lng, lat, description, longtime, city) {

	hideModal('new-lot-modal');

	var $modal = $('<div/>')
			.attr({
				"class" : "alert-modal modal",
				"role" : "dialog"
			});
	var $header = $('<div/>')
			.attr({
				"class" : "modal-header"
			})
			.html('<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>')
			.appendTo($modal);

	var $header_content = $('<h2/>')
					.text('Opprett ny by?')
					.appendTo($header);

	var $modal_body = $('<div/>')
				.attr("class", "modal-body")
				.appendTo($modal);
	var $desc_text = $('<p/>')
					.text(text)
					.appendTo($modal_body);

	var $control = $('<div/>')
		.attr({
			"class" : "controls",
			"id" : "new-city-inputs"
		})
		.appendTo($modal_body);

	var $form = $('<form/>')
		.attr({
			"action" : window.location,
			"method" : "post",
			"id" : "new-city-form"
		})
		.css({ 
			"margin" : 0
		})
		.appendTo($control);

	//$form.validate().form();
/*
	var $inputs = $('<div class="row">\
				      <div class="span4">\
				        <label for="city_lng">Longitude</label>\
				        <input id="city_lng" type="text" name="city_lng" class="span4 required" value="" />\
				      </div>\
				      <div class="span4">\
				        <label for="city_lat">Adresse</label>\
				        <input id="city_lat" type="text" name="city_lat" class="span4 required" value="" />\
				      </div>\
					</div>').appendTo($form);
*/
	var $modal_footer = $('<div/>')
			.attr("class", "modal-footer")
			.appendTo($modal);

	var $btn = $('<button/>')
				.attr({
					"type" : "submit",
				//	"onClick" : "createNewLot('" + address +"'," + lng + "," + lat + ",'" + description +"'," + longtime + ",1,'" + city +"'); return false;",
					"class" : "btn btn-primary pull-right"
				})
				.text("Ja! Skapa")
				.appendTo($modal_footer);

	$btn.bind("click", function() {
		console.log(address)
		createNewLot(address, lng, lat, description, longtime, 1, city);
	});

	var $cancel_btn = $('<button/>')
				.attr({
					"type" : "submit",
					"onClick" : "killModal('alert-modal'); showModal('new-lot-modal'); return false;",
					"class" : "btn pull-right",
					"style" : "margin-right:5px;"
				})
				.text("Avbryt")
				.appendTo($modal_footer);

	$modal.modal({ 
			backdrop: "static",
			keyboard : false
		});

	return false;
}

$(document).ready(function () {

	$("#bestill-plats-form-non-js").validate();

	$(".admin-city-lot").click(function(e) {
		
		e.preventDefault();

		var lotid = $(this).data("lotid");

		$.ajax({
			url: '/ajax/ajax_pn.php',
			type: 'post',
			beforeSend : function() {
				if($(".lot-modal").is(":visible")) {
					$(".lot-modal").slideUp();
				}
			},
			data: {
				"ajax-action" : "load-lot",
				"lotid" : lotid
			},
			success: function(data) {
				//console.log(data);

				$(".lot-modal").find("h2").text("Endre en parkeringsplass");

				$("#address").val(data.lot.Address);
				$("#city").val(data.lot.CityName);
				$("#lat").val(data.lot.Lat);
				$("#lng").val(data.lot.Lng);
				$("#description").val(data.lot.Description);

				if(data.lot.SupportLongtime == "1") {
					$("#longtime").attr("checked", "checked");
				} else {
					$("#longtime").removeAttr("checked");
				}

				$("#parking-lot-form").find("#-mode").val("edit");
				$("#parking-lot-form").find("#-edit").val(lotid);

				$(".lot-modal").slideDown();
			}
		});
			
	});

	$(".admin-kredinor-lot").click(function(e) {
		
		e.preventDefault();

		var lotid = $(this).data("lotid");

		
		$.ajax({
			url: '/ajax/ajax_pn.php',
			type: 'post',
			beforeSend : function() {
				if($(".lot-modal").is(":visible")) {
					$(".lot-modal").slideUp();
				}
			},
			data: {
				"ajax-action" : "load-kredinor-lot",
				"lotid" : lotid
			},
			success: function(data) {
			//	console.log(data);

				$(".kredinor-modal").find("h2").text("Endre en kredinor post");

				$("#kredinor_address").val(data.lot.Address);
				$("#kredinor_city").val(data.lot.Location);
				$("#depcode").val(data.lot.DepartmentCode);
				$("#type").val(data.lot.Type);
				$("#machines").val(data.lot.Machines);
				$("#information").val(data.lot.Information);

				$("#kredinor-lot-form").find("#-mode").val("edit");
				$("#kredinor-lot-form").find("#-edit").val(lotid);

				if(data.files != false) {
					
					var $ul = $("#Dokumenter").find("ul");
					var items = '';
					for (var i = 0; i < data.files.length; i++) {
						items += "<li id=\"fileid_" + data.files[i].FileID + "\">" + data.files[i].FileName + " <a href=\"#\" onclick=\"delFile(" + lotid + ","+ data.files[i].FileID +"); return false;\"><i style=\"font-color: red\" class=\"icon-remove\"></i></a></li>";
					};
					$ul.html(items);
					/*
					for (var i = data.files.length - 1; i >= 0; i--) {
						$ul.html("<li id=\"fileid_" + data.files[i].FileID + "\">" + data.files[i].FileName + " <a href=\"#\" onclick=\"delFile(" + lotid + ","+ data.files[i].FileID +"); return false;\"><i style=\"font-color: red\" class=\"icon-remove\"></i></a></li>");
					};
					*/

				}
				

				$(".kredinor-modal").slideDown();
			}
		});
			
	});

	
	$(".confirm-removal").click(function() {
		if(confirm("Er du sikker på at du vil fjerne denna?")) {
			return true;
		} else {
			return false;
		}
	});

	$(".create-new-lot").click(function(e) {

		e.preventDefault();

		var modal = $(this).data("modal");
		$("." + modal).slideToggle();
	});

	// Pretty file
	if ($('.prettyFile').length) {
	    $('.prettyFile').each(function() {
	        var pF          = $(this),
	            fileInput   = pF.find('input[type="file"]');
	 
	        fileInput.change(function() {
	            // When original file input changes, get its value, show it in the fake input
	            var files = fileInput[0].files,
	                info  = '';
	            if (files.length > 1) {
	                // Display number of selected files instead of filenames
	                info     = files.length + ' files selected';
	            } else {
	                // Display filename (without fake path)
	                var path = fileInput.val().split('\\');
	                info     = path[path.length - 1];
	            }
	 
	            pF.find('.input-append input').val(info);
	        });
	 
	        pF.find('.input-append').click(function(e) {
	            e.preventDefault();
	            // Make as the real input was clicked
	            fileInput.click();
	        })
	    });
	}

	if (window.location.hash) {
		$(".nav-tabs li a[href='#" + window.location.hash.substr(1) + "']").click()
	}
});
