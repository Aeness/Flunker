Flunker.tooltip={
	on:false
};

$(function(){
	// item tooltip
	var result = $("#icon_boxes>li>div.icon_div>img");
	result.live("mouseover", function(e){
		$(this).attr("title", ""); 
		var tipX = e.pageX + 12;
		var tipY = e.pageY + 12;
		$("body").append("<div id='flunkerTooltip' class='icon_box form_div ryzom-ui ryzom-ui-header' style='position: absolute; z-index: 100; display: none; width: 216px;'>" + $(this).parent().parent().find("div.form_div").html() + "</div>");
		if($.browser.msie) var tipWidth = $("#flunkerTooltip").outerWidth(true)
		else var tipWidth = $("#flunkerTooltip").width()
		$("#flunkerTooltip").width(tipWidth);
		$("#flunkerTooltip").css("left", tipX).css("top", tipY).fadeIn("def");
	});
			
	result.live("mouseout", function(){
		$("#flunkerTooltip").remove();
    });
			
	result.live("mousemove", function(e){
		var tipX = e.pageX + 12;
		var tipY = e.pageY + 12;
		var tipWidth = $("#flunkerTooltip").outerWidth(true);
		var tipHeight = $("#flunkerTooltip").outerHeight(true);
		if(tipX + tipWidth > $(window).scrollLeft() + $(window).width()) tipX = e.pageX - tipWidth;
		if($(window).height()+$(window).scrollTop() < tipY + tipHeight) tipY = e.pageY - tipHeight;
		$("#flunkerTooltip").css("left", tipX).css("top", tipY);
	});
	// breadcrumb tooltip
	var label = $("#label_room");
	label.hover(function(e){
		Flunker.tooltip.on=true;
		$(this).attr("title", "");
		var pos = label.offset();
		$("#arian").css("left", pos.left).css("top", pos.top+label.height()).fadeIn("def");
	}, function(){
		Flunker.tooltip.on=false;
		window.setTimeout(delete_breadcrumb_tooltip, 180);

	});
	$("#arian>a").hover(function(e){
		Flunker.tooltip.on=true;
	}, function(){
		Flunker.tooltip.on=false;
		window.setTimeout(delete_breadcrumb_tooltip, 180);
	});
	
});

function delete_breadcrumb_tooltip() {
	if( Flunker.tooltip.on == false ) {
		$("#arian").fadeOut("fast");
	}
}