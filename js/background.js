$(function(){
	var ie =(document.all)?true:false;
	if(ie && Flunker.nationality!="ryzom" ) {
		var main_content = $("#search_content");
		main_content.css( {backgroundImage: "url(img/"+Flunker.nationality+"_gh_grey.jpg)"} );
		main_content.css( {backgroundRepeat: "no-repeat"} );
		main_content.css( {backgroundPosition: "-"+main_content.position().left+"px -"+main_content.position().top+"px"} );
	}
});
