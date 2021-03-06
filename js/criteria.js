Flunker.criterias={
	checkboxs:{
		selectallnone:null,
		input:null,
		changedimg:null
	},
	radio:{
		input:null,
		changedimg:null,
		lastclick: new Array()
	},
	quality:{
		inputs:null
	},
	textsearch:{
		input:null
	},
	order:{
		ol:null,
		deleted:false,
		color:null,
		sorter_list:  new Array()
	}
};

$(function(){
	//// Init Flunker
	var checkboxes_li						= $('#search_form>ul.checkboxes_group>li.li_checkbox');
	Flunker.criterias.checkboxs.input			= $(">input",checkboxes_li);
	Flunker.criterias.checkboxs.changedimg		= $(">div>div",checkboxes_li);
	Flunker.criterias.checkboxs.selectallnone	= $('#search_form>ul.checkboxes_group>li.select_all_none>img');
	var radios_li							= $('#search_form>ul.radios_group>li');
	Flunker.criterias.radio.input				= $(">input",radios_li);
	Flunker.criterias.radio.changedimg			= $(">div>div",radios_li);
	Flunker.criterias.quality.inputs		= $('#quality>span>input');
	Flunker.criterias.textsearch.input		= $('#text_search');
	Flunker.criterias.order.ol			= $("#order_boxes");

	//// Init asc/desc order
	var sorters = $(">li",Flunker.criterias.order.ol);
	for (var i=0, n=sorters.length; i<n; i++) {
		Flunker.criterias.order.sorter_list[sorters[i].id] = "normal";
	}
	sorters = $("#order_boxes_waited>li");
	for (var i=0, n=sorters.length; i<n; i++) {
		Flunker.criterias.order.sorter_list[sorters[i].id] = "normal";
	}
	
	$(">li>span>img",Flunker.criterias.order.ol).click(changeOrder);
	$("#order_boxes_waited>li>span>img").click(changeOrder);
	
	//// Init ordering "drag and drop"
	Flunker.criterias.order.ol.sortable({
		stop: function(event, ui) {
			var list_li = $(">li",Flunker.criterias.order.ol);
			for (var i=0, n=list_li.length-1; i<n; i++) {
				list_li[i].style.display = "";
			}
			ajaxRequest2();
		},
		beforeStop: function(event, ui) {
			if(ui.helper) {
				if( Flunker.criterias.order.deleted == true ){
					Flunker.criterias.order.deleted = false;
					var list_li = $(">li",Flunker.criterias.order.ol);
					var newElem = $(list_li[list_li.length-1]).clone(true);
					newElem.droppable();
					newElem.droppable('option', 'accept', '#order_boxes_waited>li');
					newElem.droppable('option', 'hoverClass', 'drophover');
					newElem.bind('drop', dropSorter);
					ui.item.replaceWith( newElem );

				}
			}
		},
		over: function(event, ui) {
			Flunker.criterias.order.deleted = false;
			ui.helper.css("background-color",Flunker.criterias.order.color);
			ui.helper.css("border", "1px solid black");
		}, 
		out: function(event, ui) {
			if(ui.helper) {
				Flunker.criterias.order.deleted = true;
				Flunker.criterias.order.color = ui.helper.css("background-color");
				ui.helper.css("background-color","#6D7365");
				ui.helper.css("border", "0px solid black");
			}
		}
	});
	$("#order_boxes_waited>li").draggable({
		helper: 'clone',
		revert: 'invalid'
	});
	$(">li",Flunker.criterias.order.ol).droppable({
		drop: dropSorter,
		accept: '#order_boxes_waited>li',
		hoverClass: 'drophover'
	});

	//// Load data and Diplay the page
	ajaxRequest2();
	displayButton();
	
	//// Event
	$('form').submit(
		function(){
			return false;
		}
	);

	// change boton when it is clicked
	Flunker.criterias.checkboxs.changedimg.click(
		function(){
			if( $(this).css("background-image").match("_on") ) {
				$(this).css("background-image",$(this).css("background-image").replace("_on", "_off"));
				$(this).parent().siblings("input").get(0).click();
			}
			else if( $(this).css("background-image").match("_off") ) {
				$(this).css("background-image",$(this).css("background-image").replace("_off", "_on"));
				$(this).parent().siblings("input").get(0).click();
			}
			ajaxRequest2();
		}
	);
	
	// change all button when it is clicked
	Flunker.criterias.checkboxs.selectallnone.click(
		function(){
			
			if( $(this).attr("src").match("all") ) {
				$(this).parent().siblings("li").find("input:not(:checked)").click();
				$(this).parent().siblings("li").find("div>div").css("background-image", $(this).parent().siblings("li").find("div>div").css("background-image").replace("_off", "_on"));
			}
			else if( $(this).attr("src").match("none") ) {
				$(this).parent().siblings("li").find("input:checked").click();
				$(this).parent().siblings("li").find("div>div").css("background-image", $(this).parent().siblings("li").find("div>div").css("background-image").replace("_on", "_off"));
			}
			ajaxRequest2();
		}
	);
	// change boton when it is clicked
	Flunker.criterias.radio.changedimg.click(
		function(){
			var name = $(this).parent().siblings("input").attr("name")
		
			if( $(this).css("background-image").match("_on") ) {
				$(this).css("background-image",$(this).css("background-image").replace("_on", "_off"));
				$(this).parent().siblings("input").get(0).click();
				
				Flunker.criterias.radio.lastclick[name]=null;
			}
			else if( $(this).css("background-image").match("_off") ) {
				$(this).css("background-image",$(this).css("background-image").replace("_off", "_on"));
			
				if( Flunker.criterias.radio.lastclick[name] ) {
					Flunker.criterias.radio.lastclick[name].parent().siblings("input").get(0).checked = false;
					Flunker.criterias.radio.lastclick[name].css("background-image", Flunker.criterias.radio.lastclick[name].css("background-image").replace("_on", "_off"));
				}
				Flunker.criterias.radio.lastclick[name] = $(this);
				Flunker.criterias.radio.lastclick[name].parent().siblings("input").get(0).click(); 
			}
			ajaxRequest2();
		}
	);

	// expand/collapse
	$('#reduce_img').click(
		function(){
			
			if( $(this).attr("src") == "img/reduce_on.png" ) {
				$(this).attr("src", "img/reduce_off.png");
				$('#reduce_hidden').get(0).checked = true;
			}
			else {
				$(this).attr("src", "img/reduce_on.png");
				$('#reduce_hidden').get(0).checked = false;
			}
			
			ajaxRequest2();
		}
	);
	
	// quality
	Flunker.criterias.quality.inputs.change(
		function(){
			ajaxRequest2();
		}
	);
	
	// search text
	Flunker.criterias.textsearch.input.change(
		function(){
			ajaxRequest2();
		}
	);
});

function dropSorter(event, ui) {
	var newElem = ui.draggable.clone(false);
	var newOrder = Flunker.criterias.order.sorter_list[newElem.attr("id")];
	newElem.find('span>img').bind('click',changeOrder);
	newElem.droppable();
	newElem.droppable('option', 'accept', '#order_boxes_waited>li');
	newElem.droppable('option', 'hoverClass', 'drophover');
	newElem.bind('drop', dropSorter);
	
	newElem.attr("id",newElem.attr("id").replace("waiting_", ""));
	Flunker.criterias.order.sorter_list[newElem.attr("id")]=newOrder;
	
	$(event.target).replaceWith(newElem);
	
	ajaxRequest2();
};

// display button corectly (usefull with some F5)
function displayButton(){
	for (var i=0, n=Flunker.criterias.checkboxs.input.length; i<n; i++) {
		if( Flunker.criterias.checkboxs.input[i].checked == true) {
			var monImg = $(Flunker.criterias.checkboxs.changedimg[i]);
			monImg.css("background-image",monImg.css("background-image").replace("_off", "_on"));
		}
		else {
			var monImg = $(Flunker.criterias.checkboxs.changedimg[i]);
			monImg.css("background-image",monImg.css("background-image").replace("_on", "_off"));
		}
	}
}

function changeOrder(eventObject) {
	// change the image
	var src = $(this).attr("src");
	if( src.match("down_") ) {
		src = src.replace("down_", "up_");
	}
	else if( src.match("up_") ) {
		src = src.replace("up_", "down_");
	}
	if( src.match("_red") ) {
		$(this).attr("src", src.replace("_red", "_green"));
	}
	else if( src.match("_green") ) {
		$(this).attr("src", src.replace("_green", "_red"));
	}
	// change the order
	var ind = $(this).parent().parent().attr("id");
	var exchange = new Array();
	exchange["normal"] = "reverse";
	exchange["reverse"] = "normal";
	Flunker.criterias.order.sorter_list[ind] = exchange[Flunker.criterias.order.sorter_list[ind]];
		
	if( ! $(this).parent().parent('[id^=waiting_]').length ) {
		ajaxRequest2();
	}
}

