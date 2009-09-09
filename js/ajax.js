Flunker.ajax={
	nb_is_waiting:0,
	image:null,
	contener:null,
	background:null
};

$(function(){
	//// Init
	Flunker.ajax.background		= $('#ajax_waiting');
	Flunker.ajax.image			= $('#ajax_waiting_img');
	Flunker.ajax.contener		= $('.ryzom-ui-body')
});

function ajaxRequest2() {
	var xhr_object = null;

	if(window.XMLHttpRequest) {// Firefox
	   xhr_object = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) {// Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	}
	else { // XMLHttpRequest not supported by browser
	   alert(Flunker.msg.err_request);
	   return;
	}
	
	addWaiter();
	
	var post_req = "";
	for (var i=0, n=Flunker.criterias.checkboxs.input.length; i<n; i++) {
		if( Flunker.criterias.checkboxs.input[i].checked == true ) {
			post_req += Flunker.criterias.checkboxs.input[i].name+"[]="+Flunker.criterias.checkboxs.input[i].value+"&";
		}
	}
	for (var i=0, n=Flunker.criterias.radio.input.length; i<n; i++) {
		if( Flunker.criterias.radio.input[i].checked == true ) {
			post_req += Flunker.criterias.radio.input[i].name+"="+Flunker.criterias.radio.input[i].value+"&";
		}
	}
	for (var i=0, n=Flunker.criterias.GHcheckboxs.input.length; i<n; i++) {
		if( Flunker.criterias.GHcheckboxs.input[i].checked == true ) {
			post_req += Flunker.criterias.GHcheckboxs.input[i].name+"[]="+Flunker.criterias.GHcheckboxs.input[i].value+"&";
		}
	}
	
	var quality = Flunker.criterias.quality.inputs;
	for (var i=0, n=quality.length; i<n; i++) {
		post_req += quality[i].name+"="+quality[i].value+"&";

	}
	
	var test = Flunker.criterias.textsearch.input;
	for (var i=0, n=test.length; i<n; i++) {
		post_req += test[i].id+"="+test[i].value+"&";

	}
	
	if( Flunker.criterias.order.ol.length ) {
		var order = Flunker.criterias.order.ol.sortable('toArray');
		for (var i=0, n=order.length; i<n; i++) {
			if( order[i] != "" ) {
				post_req += "order["+(i)+"]="+order[i]+"&";
				post_req += "sortway["+(i)+"]="+Flunker.criterias.order.sorter_list[order[i]]+"&";
			}
		}
	}
	
	var reduce = $('#reduce_hidden').get(0);
	if( reduce.checked == true ) {
		post_req += reduce.id+"="+reduce.value+"&";
	}
	
	post_req += "room="+document.getElementById("room").value;

	xhr_object.open("POST", "ajax_search.php", true);
	xhr_object.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr_object.send(post_req);

	xhr_object.onreadystatechange = function() {
	   if(xhr_object.readyState == 4) {
		//alert(xhr_object.status);
		//alert(xhr_object.responseText);
		if( Flunker.ajax.nb_is_waiting == 1) {
			document.getElementById('icon_boxes').innerHTML = xhr_object.responseText;
			$("#nb_items").text($("#ajax_nb_items").text());
		}
		removeWaiter();
	   }
	}
}

function addWaiter() {
	var pos = Flunker.ajax.contener.position();
	var width = Flunker.ajax.contener.width( );
	var height = Flunker.ajax.contener.height( );
	
	Flunker.ajax.image.css('left',((pos.left)+(width/2)-27)+'px');
	Flunker.ajax.image.css('top',(pos.top+50)+'px');
	Flunker.ajax.background.css('width',width+'px');
	Flunker.ajax.background.css('height',height+'px');

	Flunker.ajax.nb_is_waiting++;
	Flunker.ajax.background.show();
	Flunker.ajax.image.show();
}
function removeWaiter() {
	Flunker.ajax.nb_is_waiting--;
	if( Flunker.ajax.nb_is_waiting == 0) {
		Flunker.ajax.background.hide();
		Flunker.ajax.image.hide();
	}
	else {
		var pos = Flunker.ajax.contener.position();
		var width = Flunker.ajax.contener.width( );
		var height = Flunker.ajax.contener.height( );
		
		Flunker.ajax.image.css('left',((pos.left)+(width/2)-27)+'px');
		Flunker.ajax.image.css('top',(pos.top+50)+'px');
		Flunker.ajax.background.css('width',width+'px');
		Flunker.ajax.background.css('height',height+'px');
	}
}
