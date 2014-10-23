$(function(){
	
	// 20 minuta, 60 sekundi u svakom minutu,
	//*1000 da bi bilo u milisekundama + 1000 da kada se pokrene odbrojava od 20 min a ne od 19:59
	
	//var ts = (new Date()).getTime() + (10*1000 + 1000);
	var ts = (new Date()).getTime() + (30*60*1000 + 1000);

	
	
	$('#countdown').countdown(
	{
	// prosledjuje se samo timestamp, dokle se odbrojava
		timestamp	: ts
	});
	 
});
