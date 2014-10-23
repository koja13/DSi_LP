		// GLOBALNE  PROMENLJIVE

		// promenljive subjekat, objekat i predikat
		var subject;
		var object;
		var predicate;

		// bool promenljiva koja sluzi sa proveru da li je rdf fajl izmenjen, tj da li su dodate nove veze, ukoliko jesu potrebno je izbaciti obavestenje o novoj verziji fajla
		var rdfGraphIsChanged = false;
		
		// promenljiva koja cuva ime fajla sa tekstom, inicijalno je prazan string
		var textFileName = "";
		
		// promenljiva koja cuva tip fajla sa tekstom (txt ili html), inicijalno je prazan string
		var textFileType = "";
		
		// promenljiva koja cuva ime rdf modela, inicijalno je prazan string
		var rdfGraphName = "";

		
		// ==========================  KOD DODAT ZA TEST MODUL -- pocetak ================================
		
		// postavljanje imena modela (jedan model za sve lekcije)
		rdfGraphName = "modelRes.rdf";
		
		// promenljiva koja cuva link ka rdf kontroleru, koristi se kod ajax poziva
		var rdfController = config.site_url + "/RdfController";

		// postavljanje trenutnog broja lekcije na 1, pri pokretanju glavnog pogleda sa lekcijama
		var currentLessionNumber = 1;
		
		// bool promenljiva, da li je isteklo vreme za ucenje, na false u startu
		var timeIsUp = false;
		
		// broj strana sa pitanjima
		var numberOfPages = 5;
		

		// procenti za progress div
		var progressPercents = 100/numberOfPages;
		
		// niz u kome ce se cuvati odgovori koje korisnik odabere
		var userAnswers = new Array();
		
		var qNumber = 18;

		// bool promenljive
		var resultsSent = false;
		userFinishedLearningAndQuiz = false;
		
		
		var answerIdString = "";
		
		var predicatesArray = new Array();

		var newPredicates = new Array();
		
		var truePredicate = ""; 
		// FUNKCIJE
		
		//
		// kod koji se izvrsava nakon ucitavanja strane
		//
		$(document).ready(function() {
			
			// kliktanje na start test u navigation divu
			$("#startTest").click(function() {
				// cuvanje informacije o akciji zavrsetku ucenja i startovanju testa
			    sendUserActionsLessions(currentLessionNumber, "end_dsi", null);
			});
			
			// postavljanje trenutnog broja lekcije u span u gornjem levom uglu
			$("#lessionNumberSpan2").html(currentLessionNumber);
			
			// postavljanje event hendlera za kliktanje na close (x) na divu za prikaz veza dobijenih iz rdf-a
			$(document).on('click', '.close', function(){
			        $(this).parent().hide(400);
			    });
			
			
			$("#submitAnswerButton").click(function() {
				
				if(answerIdString!="")
				{
					$("#bottomDiv").hide(400);
					sendSelectedAnswer();
					
					answerIdString = "";
				}
				else
				{
					alert("You need to select one answer!");
				}
				// cuvanje informacije o akciji zavrsetku ucenja i startovanju testa
			   // sendUserActionsLessions(currentLessionNumber, "end_dsi", null);
			});
			
			
			$("#submitNoAnswerButton").click(function() {
				
				$("#bottomDiv").hide(400);
				sendUserActionsDSiALogs(subject, null, object, "none");

				// cuvanje informacije o akciji zavrsetku ucenja i startovanju testa
			   // sendUserActionsLessions(currentLessionNumber, "end_dsi", null);
			});
			
			$("#submitStatementsButton").click(function() {

				if($("#submitStatementsButton").val()=="Delete relations!")	
				{
					//alert("Ovo treba brisati!");
					deleteRelations();
					$("#bottomDiv").hide(400);
				}
				
				if(getNewRelations())
				{
					sendNewRelationsToServer();
					$("#bottomDiv").hide(400);
					
				}
				

			});
			
			$("#spanCloseId").click(function() {
				
				sendUserActionsDSiALogs(subject, null, object, "cancel");

				// cuvanje informacije o akciji zavrsetku ucenja i startovanju testa
			   // sendUserActionsLessions(currentLessionNumber, "end_dsi", null);
			});
			
			//broj pitanja na strani
			//var qCount = 3;

			$("#progressInDiv").width(progressPercents + "%");

		});
		
		
		// ucitavanje prve lekcije na strani
		
		//getTextFromServer(1, "tekst1.html", "model.rdf");
		getTextFromServer(1, "tekst1.htm", "modelRes.rdf");

		

		
		
		function changeSubmitButtonText(text)
		{
			$("#submitStatementsButton").prop('value', text);
		}
		
		function getNewRelations()
		{
			var br = 1;
			var empty = false;
			var checkedRadioBtn = false;
			var checkedRadioButtonNumber = 0;
			
			while(br<=5 && empty==false)
			{
				if($('#idRelation' + br).val()=="")
				{
					alert("You need to submit all 5 relations!");
					empty = true;
				}
				br+=1;
			}
			
			br = 1;
			while(br<=5 && checkedRadioBtn==false && empty==false)
			{
				if($('#idRadioBtn' + br).is(":checked"))
				{
					checkedRadioBtn = true;
					checkedRadioButtonNumber = br;
				}
				br+=1;
			}
			
			
			if(empty==false && checkedRadioBtn==true)
			{
				for(var i=1;i<=5;i++)
				{
					newPredicates[i] = $('#idRelation' + i).val();
				
				}

				truePredicate = $('#idRelation' + checkedRadioButtonNumber).val();
				
				return true;
			}
			return false;
		}
		
		
		function sendNewRelationsToServer()
		{	  
			$.ajax({
				  // u pitanju je post zahtev
				  type: "POST",
				  // link ka kome se upucuje zahtev, getQuizResults predstavlja metod na serveru koji ce da odgovori na zahtev
				  url: rdfController + "/writeAllStatementAndProperties",
				  data: {	
				  // salju se odgovori na pitanja i vreme kada je zavrsen kviz
					  		s: subject,
					  		o: object,
					  		truePredicate: truePredicate,		
					  		predicates: newPredicates,
					  		rdfGraph: rdfGraphName
				  		}
				}).done(function( response ) {

					//alert(response);
				});
		}
		
		function returnPredicates()
		{	
			var predicatesArr = new Array();
			
			for(var i=1; i<=5; i++)
			{
				predicatesArr[i] = predicatesArray["idAnswer" + i];
			}
			
			return predicatesArr;
		}
		
		function deleteRelations()
		{	  
			$.ajax({
				  // u pitanju je post zahtev
				  type: "POST",
				  // link ka kome se upucuje zahtev, getQuizResults predstavlja metod na serveru koji ce da odgovori na zahtev
				  url: rdfController + "/removeStatements",
				  data: {	
				  // salju se odgovori na pitanja i vreme kada je zavrsen kviz
					  		s: subject,
					  		o: object,
					  		predicates: returnPredicates(),
					  		rdfGraph: rdfGraphName
				  		}
				}).done(function( response ) {

					//alert(response);
					
					//alert (subject + " " + object);
					//alert(predicatesArray["idAnswer1"]);
				});
		}
		
		// ========================= changeLessionNumberPrev() ========================
		//
		// funkcija koja menja vrednost broja lekcije na kojoj se trenutno nalazi korisnik, korisnik se vraca unazad
		// pozivaju je event handler za kliktanje na prev dugme u glavnom pogledu (MainView)
		//
		function changeLessionNumberPrev()
		{
			// promena vrednosti promenljive koja cuva broj trenutne lekcije
			currentLessionNumber -= 1;
			
			// upisivanje nove vrednosti u span
			$("#lessionNumberSpan2").html(currentLessionNumber);
		}
		
		// ========================= changeLessionNumberNext() ========================
		//
		// funkcija koja menja vrednost broja lekcije na kojoj se trenutno nalazi korisnik, korisnik ide napred
		// pozivaju je event handler za kliktanje na next dugme u glavnom pogledu (MainView)
		//
		function changeLessionNumberNext()
		{
			// promena vrednosti promenljive koja cuva broj trenutne lekcije
			currentLessionNumber += 1;
			
			// upisivanje nove vrednosti u span
			$("#lessionNumberSpan2").html(currentLessionNumber);
		}

		//
		// dodavanje next i prev kontrola na svaki tab
		//
		$(function() 
		{

			// kreiranje tabova na osnovu divova
			var $tabs = $('#tabs').tabs();
			
			$(".ui-tabs-panel").each(function(i)
			{
			
				var totalSize = $(".ui-tabs-panel").size() - 1;
				
				if (i != totalSize) 
				{
					next = i + 2;
					$(this).append("<a href='#' class='next-tab mover' rel='" + next + "'>Next Page &#187;</a>");

				}
				  
				if (i != 0)
				{
					prev = i;
					$(this).append("<a href='#' class='prev-tab mover' rel='" + prev + "'>&#171; Prev Page</a>");

				}
				
				if (i == totalSize) 
				{
					next = i + 2;
					$(this).append("<a href='#' class='finish-tab moverFinish' rel='" + next + "'>Finish &#187;</a>");
					//$(this).append("<span id='finishButtonSpan'> <input  id='finishButton' type='button' onclick='finishQuiz();' value='FINISH!'/></span>");
				}

			});
		
			// definisanje event handlera za klik na next dugme
			$('.next-tab').click(function() 
			{ 
				// pomocu $(this).attr("rel") se dobije broj taba na koji treba da se predje klikom na prev ili next dugme
				// zatim se taj tab selektuje
				$tabs.tabs('select', $(this).attr("rel"));
			
				// postavljanje teksta u tab koji ce biti aktivan klikom na prev ili next kontrolu
				getTextFromServer($(this).attr("rel"), "tekst"+$(this).attr("rel")+".htm", "model.rdf");
				
				// broj prethodne lekcije, treba nam da bi izbrisali sadrzaj lekcije na kojoj smo se prethodno nalazili
				var relPrev = parseInt($(this).attr("rel"))-1;
				
				// brisanje sadrzaja tabova koji nisu trenutno aktivni
				$("#lessionDiv" + relPrev).empty();
				
				// slanje informacije o akciji kliktanja na next dugme serveru
				sendUserActionsLessions(currentLessionNumber, "next", parseInt(currentLessionNumber) + 1);
				
				// izmena broja lekcije na kojoj se trenutno nalazi korisnik, +1, korisnik ide napred
				changeLessionNumberNext();
				
				//alert(relNext + " to je +1 i " + relPrev + " je -1");
				
				
				// povecavanje procenata prilikom prelaza na narednu grupu pitanja
				progressPercents += 100/numberOfPages;

				// animiranje progres diva na osnovu procenata u kom delu kviza se korisnik trenutno nalazi
				$("#progressInDiv").animate({
				    
				    width:progressPercents + "%"
				  }, "fast");
				
				if(currentLessionNumber==numberOfPages)
				{
					$("#progressInDiv").css({'border-top-right-radius': '7px', 'border-bottom-right-radius': '7px'});
				}
				return false;
			});
	       
			// definisanje event handlera za klik na prev dugme
			$('.prev-tab').click(function() 
			{ 
				// pomocu $(this).attr("rel") se dobije broj taba na koji treba da se predje klikom na prev ili next dugme
				// zatim se taj tab selektuje
				$tabs.tabs('select', $(this).attr("rel"));
					
				// postavljanje teksta u tab koji ce biti aktivan klikom na prev ili next kontrolu
				getTextFromServer($(this).attr("rel"), "tekst"+ $(this).attr("rel") + ".htm", "model.rdf");

				// broj naredne lekcije, treba nam da bi izbrisali sadrzaj lekcije na kojoj smo se prethodno nalazili
				var relNext = parseInt($(this).attr("rel")) + 1;

				// brisanje sadrzaja tabova koji nisu trenutno aktivni
				$("#lessionDiv" + relNext).empty();
				
				// slanje informacije o akciji kliktanja na prev dugme serveru
				sendUserActionsLessions(currentLessionNumber, "prev", parseInt(currentLessionNumber) - 1);
				
				// izmena broja lekcije na kojoj se trenutno nalazi korisnik, -1, korisnik ide nazad
				changeLessionNumberPrev();

				// alert(relNext + " to je +1 i " + relPrev + " je -1");
				
				// smanjivanje procenata prilikom vracanja na prethodnu grupu pitanja
				progressPercents -= 100/numberOfPages;

				// animiranje progres diva na osnovu procenata u kom delu kviza se korisnik trenutno nalazi
				$("#progressInDiv").animate({
					width:progressPercents + "%"
			    }, "fast");
				
				if(currentLessionNumber<numberOfPages)
				{
					$("#progressInDiv").css({'border-top-right-radius': '0px', 'border-bottom-right-radius': '0px'});
				}
				return false;
			});
			
			$('.finish-tab').click(function() 
			{ 
				finishQuiz();	

				return false;
			});

		});
		
        // =============================== removejscssfile(filename, filetype) ==============================
		//
		// funkcija koja uklanja js ili css fajl sa zadatim URL-om sa strane na kojoj je pozvana
		// poziva je fja finishQuiz nakon slanja rezultata kviza == MainScript.js ==
		// 
      /*  function removejscssfile(filename, filetype)
        {
             var targetelement=(filetype=="js")? "script" : (filetype=="css")? "link" : "none" //determine element type to create nodelist from
             
             var targetattr=(filetype=="js")? "src" : (filetype=="css")? "href" : "none" //determine corresponding attribute to test for
             
             var allsuspects=document.getElementsByTagName(targetelement)
             for (var i=allsuspects.length; i>=0; i--)
             { //search backwards within nodelist for matching elements to remove
                if(allsuspects[i] && allsuspects[i].getAttribute(targetattr)!=null && allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
                        allsuspects[i].parentNode.removeChild(allsuspects[i]) //remove element by calling parentNode.removeChild()
             }
        }*/

        
		
		// =============================== finishQuiz() ==============================
		//
		// funkcija koja cuva korisnikove odgovore na pitanja u niz, a zatim ih prosledjuje serveru
		// poziva je event handler za klik na dugme == finishButton, MainView ==
		// 
		function finishQuiz()
		{
			// ukoliko rezultati nisu vec poslati (moguc scenario jeste automatsko prosledjivanje rezultata nakon isteka vremena za kviz)
			if(resultsSent!=true)
			{
				for(var i=1;i<=qNumber;i++)
				{
					if($("input[name=q"+i+"]:checked").val()==null)
					{
						// ukoliko nema odgovora na to pitanje, tj ukoliko nista nije cekirano onda se u niz upisuje null
						userAnswers[i] = null;
					}
					else
					{
						// ukoliko ima odgovora na to pitanje
						var answerId =  $("input[name=q"+i+"]:checked").attr('id');
						var userAnswer = answerId.substr(-1,1);
						userAnswers[i] = userAnswer;
					}
				}
			
				// belezenje akcije u bazi podataka
				sendUserActionsLessions(null, "end_quiz", null);
				
				// slanje rezultata serveru
				sendQuizResults();
				
				// setovanje bool promenljivih
				
				// korisnik je zavrsio kviz
				userFinishedLearningAndQuiz = true;
				
				// rezultati su poslati
				resultsSent = true;

                //uklanja js fajlove vezane za tajmer nakon zavrsetka ucenja/kviza
               // removejscssfile(config.base_url + "assets/countdownTimer/countdown/jquery.countdown.ReadMode.js", "js");
              //  removejscssfile(config.base_url + "assets/countdownTimer/js/ReadModeCountdownScript.js", "js")  
			}
		}
		

		// =============================== sendQuizResults() ==============================
		//
		// Ajax fja koja salje serveru odogovore na pitanja
		// poziva je fja finishQuiz()
		// 	
		function sendQuizResults()
		{	  
			$.ajax({
				  // u pitanju je post zahtev
				  type: "POST",
				  // link ka kome se upucuje zahtev, getQuizResults predstavlja metod na serveru koji ce da odgovori na zahtev
				  url: config.site_url + "/usercontroller/saveQuizResults",
				  data: {	
				  // salju se odgovori na pitanja i vreme kada je zavrsen kviz
					  		userAnswers: userAnswers,
							currentDateTime: getCurrentTime()
				  		}
				}).done(function( response ) {

					if(response == "Success")
					{
						// brise se sadrzaj mainDiv-a
						$("#mainDiv").empty();
						$("#mainDiv").css({'border': '0px', 'text-align': 'center'});
						//$("#mainDiv").css({'vertical-align': '50%'});

						// brise se tajmer, tj div u kome se nalazi
						$("#countDiv").remove();
						
						$("#bottomDiv").remove();
						$("#lessionNumberSpan1").remove();
						$("#navProgressDiv").remove();
						
						// korisniku se ispisuje da su rezultati sacuvani
						//$("#mainDiv").html("Rezultati su saÃ„ï¿½uvani! Hvala Ã…Â¡to ste uÃ„ï¿½estovali.");
							
						$('#mainDiv').prepend('<img id="hvalaImg" style= "display: block, margin-left: auto,  margin-right: auto" src="' + config.base_url + 'hvala.jpg" />');

					}
				});
		}
		
		
		// ========================= sendUserActions(subject, object) ========================
		//
		// Ajax funkcija koja serveru salje informaciju o prevlacenju reci na rec
		// poziva se u okviru handleDropEvent( event, ui ) koji predstavlja handler za drop event reci
		// ova funkcija serveru salje broj lekcije na kojoj je obavljena akcija, prevucenu rec i onu na koju je spustena,
		// kao i trenutno vreme ove akcije
		// ulazni parametri su: subject - subjekat, podignuta rec
		//						object  - objekat, rec na koju je podignuta rec spustena
		//
		function sendUserActions(subject, object)
		{
			$.ajax({
				  type: "POST",
				  url: config.site_url + "/usercontroller/getUserActions",
				  data: {	
							  currentLessionNumber: currentLessionNumber,
							  subject: subject,
							  object:object,
							  currentDateTime: getCurrentTime()
				  		}
				}).done(function( response ) {
					
					//alert(response);
				});
		}	
		
		// ========================= sendUserActionsLessions(currentLessionNumber, action, next_prev_lession_number) ========================
		//
		// Ajax funkcija koja serveru salje informaciju o akciji koju je korisnik obavio
		// pozivaju je fje: finishQuiz() == QuizScript.js ==, event handler za kliktanje na prev i next dugmice u MainView
		// automatski se poziva kada se ucita strana na kojoj se ucitava MainScript.js (document.ready na pocetku skripte)
		// poziva se u okviru pogleda: MainView, QuizView, Welcome
		// ulazni parametri su: currentLessionNumber 		- broj lekcije na kojoj se korisnik trenutno nalazi
		//						action  					- akcija koju je korisnik obavio
		//						next_prev_lession_number  	- broj lekcije na koju ce korisnik biti preusmeren izvrsenom akcijom
		//
		function sendUserActionsLessions(currentLessionNumber, action, next_prev_lession_number)
		{
			$.ajax({
				  type: "POST",
				  url: config.site_url + "/usercontroller/getUserActionsLessions",
				  data: {	
							  currentLessionNumber: currentLessionNumber,
							  action: action,
							  next_prev_lession_number: next_prev_lession_number,
							  currentDateTime: getCurrentTime()
				  		}
				}).done(function( response ) {
					
					//alert(response);
				});
		}	
		
		
		function sendUserActionsDSiALogs(sub, obj, pre, action)
		{
			$.ajax({
				  type: "POST",
				  url: config.site_url + "/usercontroller/getUserActionsDSiALogs",
				  data: {	
			  		          s: sub,
			  		          o: obj,					  		  
			  		          p: pre,
							  action: action,
							  currentDateTime: getCurrentTime()
				  		}
				}).done(function( response ) {
					
					//alert(response);
				});
		}	
		
		
		// ========================= getCurrentTime() ========================
		//
		// fja koja vraca trenutno vreme u obliku stringa
		// poziva se u okviru fja sendUserActions(subject, object) i
		// sendUserActionsLessions(currentLessionNumber, action, next_prev_lession_number)
		// kako bi se dobilo vreme izvrsene akcije
		//
		function getCurrentTime()
		{
			var currentTime = new Date();
			var month = currentTime.getMonth() + 1;
			var day = currentTime.getDate();
			var year = currentTime.getFullYear();
			var hours = currentTime.getHours();
			var minuts = currentTime.getMinutes();
			var seconds = currentTime.getSeconds();
			
			var currentTimeString = year + "-" + month + "-" + day +" " + hours + ":" + minuts + ":" + seconds;
			//alert(month + "/" + day + "/" + year + " " + hours + ":" + minuts + ":" + seconds);
			return currentTimeString;
		}
		
	// ==========================  KOD DODAT ZA TEST MODUL -- kraj ================================
		
		
	//
	// FUNKCIJA ZA DRAG & DROP
	//
		
	// ========================= makeDraggableDroppable() ========================
	//
	// funkcija koja recima daje drag & drop funkcionalnost
	// pozivaju je funkcije spanEditMode() i spanReadMode(), nakon stavljanja reci u spanove daje im se drag&drop funkcionalnost
	// ova funkcija takodje kreira event handlere za hover, za pocetak prevlacenja i za kraj prevlacenja reci
	//
	function makeDraggableDroppable()
	{
		  // podesavanje promene kursora kad stane iznad reci koja moze da se prenese
		  $(".dragdrop").hover(function() {
			
			$(this).css('cursor','move');
			
			}, function() {
			
			$(this).css('cursor','auto');
			
			});
		  
		  // drag-drop deo 
	      $(".dragdrop").draggable( 
	              {
	                  containment: '#content',
	                  cursor: 'move',
	                  snap: '#content',
					  revert: true,
					  start: HandleDragStart,
					  stop: HandleDragStop
	       		 } );
	
	      $(".dragdrop").droppable( 
	              {
	  	    		drop: handleDropEvent
	  	  		 } );
				 
		

	      // handler za pocetak prevlacenja
	      function HandleDragStart( event, ui )
	      {
	    	  // potrebno je dobiti sve reci sa kojima je u vezi podignuta rec
	    	  
	    	  // rec koju smo podigli
	    	  var s = $(this).html();

	    	  subject = s;
	    	  
	    	  // slanje subjekta serveru ajax funkcijom
	    	  if(config.mode=="edit")
	    	  {
	    		 sendSubject();
	    	  }
	    	 
		  }

	 	  // handler za kraj prevlacenja
	      function HandleDragStop( event, ui )
	      {
	    	  $(".dragdrop").css("background-color", "transparent");
		  }

	      // handler za spustanje reci
	  	  function handleDropEvent( event, ui )
	  	  {
		  	var s = ui.draggable.html(); // pokupi rec koja je stigla, to ce biti recenicni subjekat
		  	var o = $(this).html(); // pokupi rec na koju je spusteno (to je this), to je recenicni objekat

		  	// alert("Subjekat = " + s + " i Objekat = " + o);
			
		  	// ispisivanje postojecih veza
			writeToBottomDiv(s,o);
			sendUserActions(s, o);
		
	  	  }
	}
	
	
	//
	// FUNKCIJE ZA INTERAKCIJU SA SERVEROM
	//
	
	// ========================= writeToBottomDivRight(s,o) ========================
	//
	// funkcija koja dodaje formu za upis nove veze u donji desni div
	// poziva je event handler za spustanje reci na rec handleDropEvent( event, ui )
	// ulazni parametri su: s - subjekat, podignuta rec
	//						o - objekat, rec na koju je podignuta rec spustena
	// 
	function writeToBottomDivRight(s,o)
	{
		subject=s;
		object=o;

		$("#bottomDivRight").html("<form name='form'> "
									+ s + " <input type='text' id='predicateId' name='predicate' /> " + o + " <br />" +
											"<input type='button' onclick='sendSubjectObjectPredicate(this.parentNode); ' value='Sacuvaj' />" +
								 "</form>");
	}

	// ========================= writeToBottomDivLeft(s,o) ========================
	//
	// funkcija koja salje subjekat i objekat serveru, i rezultat od servera upise u donji div levo
	// poziva je event handler za spustanje reci na rec handleDropEvent( event, ui )
	// ulazni parametri su: s - subjekat, podignuta rec
	//						o - objekat, rec na koju je podignuta rec spustena
	// 
	function writeToBottomDivLeft(s,o)
	{
		subject=s;
		object=o;

		// slanje subjekta i objekta serveru ajax funkcijom, i upisivanja rezultata koje vrati u donji div levo
		sendSubjectObject();
	}

	// ========================= writeToBottomDiv(s,o) ========================
	//
	// funkcija koja salje subjekat i objekat serveru, i rezultat od servera upise u donji div
	// poziva je event handler za spustanje reci na rec handleDropEvent( event, ui ), ukoliko je u pitanju Read mode
	// ulazni parametri su: s - subjekat, podignuta rec
	//						o - objekat, rec na koju je podignuta rec spustena
	// 
	function writeToBottomDiv(s,o)
	{
		subject=s;
		object=o;

		// slanje subjekta i objekta serveru
		sendSubjectObject();
	}

	//
	// AJAX FUNKCIJE
	//
	
	// ========================= sendSubject() ========================
	//
	// Ajax fja za slanje subjekta serveru, kako bi dobili sve objekte za koje je vezan
	// poziva je event handler za pocetak prenosenja reci handleDragStart( event, ui )
	// 
	function sendSubject()
	{
		$.ajax({
			// post zahtev je u pitanju
			  type: "POST",
			  // link ka kome se upucuje zahtev, getObjects predstavlja metod na serveru koji ce da odgovori na zahtev
			  url: rdfController + "/getObjects",
			  // podaci koji se salju, nazivi subjekta, rdf grafa
			  data: {	
				  		s: subject,
						rdfGraph: rdfGraphName
			  		}
			}).done(function( response ) {

				// obrada odgovora na zahtev, postavljanje pozadinske boje svim objektima koje dobijemo kao odgovor
			  $(response).css("background-color", "yellow");
				
			});
	}

	
	// ========================= sendSubjectObject() ========================
	//
	// Ajax fja za slanje subjekta i objekta serveru, kako bi dobili postojece veze izmedju njih
	// pozivaju je funkcije writeToBottomDivLeft(s,o) i writeToBottomDiv(s,o)
	// 
	function sendSubjectObject()
	{
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getPredicates",
			  data: {
				  		s: subject,
						o: object ,
						mode: config.mode,
						rdfGraph: rdfGraphName
			  		}
		
			}).done(function( response ) {
				
				if(config.mode == "read")
				{
					// ukoliko je u pitanju mod za citanje, testiranje
					$("#bottomDiv").show();
					
					// u donji div se upisu sve veze koje vrati server
					$("#statementDiv").html(response);
				}
				else if(config.mode == "edit")
				{				
					// ukoliko je u pitanju mod za kreiranje veza izmedju pojmova (laznih i pravih)
					if(response == "no_relations")
					{
						// u pitanju je edit mode, a u rdf-u ne postoji nijedna sacuvana veza
						changeSubmitButtonText("Submit relations!");
						$("#bottomDiv").show();
						showEnterNewRelationsDiv(subject, object);
					}
					else
					{
						// u pitanju je edit mode, a u rdf-u su ve' sacuvane veze (lazne i prave) izmedju tih pojmova
						$("#bottomDiv").show();
						
						// u donji div se upisu sve veze koje vrati server
						$("#statementDiv").html(response);
						
					}
				}
				
			});
	}
	
	function showEnterNewRelationsDiv(subject, object)
	{
		var htmlInput = "<p class='answerParText'>Please enter new relations, and choose which one of them is the correct one:<p><br />";
		for(var i=1; i<=5; i++)
		{
			htmlInput += "<p class='answerPar' id='idRelationP" + i + "'> "+ i +". " + subject + " " 
			+ " <input id='idRelation"+ i +"' type='text' size='35'  name='relation' value=''> " + " " + object
			+ "<input id='idRadioBtn"+ i +"' type='radio' name='trueFalse' class='trueFalseRadioBtn' value=''> "+ "</p>";
		}
		
		$("#statementDiv").html(htmlInput);
		setClickEventHandlerTrueFalseRadioBtn();
	}
	
	function savePredicatesFromServer(idAnswer, predicate)
	{
		predicatesArray[idAnswer] = predicate;
		//alert(idAnswer);
		//alert(subject);
		//alert(predicatesArray[idAnswer]);
		//alert(object);
		
	}
	

	
	function sendSelectedAnswer()
	{
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getAnswerFromClient",
			  data: {
				  		s: subject,
				  		o: object,
				  		p: predicatesArray[answerIdString],
						currentDateTime: getCurrentTime(),
						rdfGraph: rdfGraphName
			  		}
		
			}).done(function( response ) {
				
			//	alert(response);
				/*
				$("#bottomDiv").show();
				// u donji div se upisu sve veze koje vrati server
				$("#statementDiv").html(response);*/

			});
	}
	
	

	// ========================= getTextFromServer(tFileName) ========================
	//
	// Ajax fja za citanje teksta iz fajla na serveru (i spanovanje tog teksta)
	// poziva je funkcija uploadTextFile, takodje se poziva prilikom ucitavanja stranice ukoliko je tekst ucitan na server
	// ulazni parametar je: tFileName - naziv fajla sa tekstom na serveru
	//
	function getTextFromServer(lessionNumber, tFileName, rdfGraphName)
	{
		$.ajax({
			  type: "POST",
			  url: rdfController + "/getText",
			  data: { 	
				  		textFile: tFileName
			  		}
		
			}).done(function( response ) {
				
                setTimeout(function(){$("#lessionDiv" + lessionNumber).html(response); spanReadMode("modelRes.rdf");}, 500);

				// spanovanje teksta
				//if(config.use_dsi=="yes")
				//{
				    //spanEditMode();
				//}
			});
	}

	// ========================= spanReadMode() ========================
	//
	// Ajax funkcija za citanje subjekata i objekata sa servera, pa zatim spanovanje teksta u Read modu
	// poziva je funkcija span()
	// koristi funkciju findAndReplaceDOMText, definisanu u eksternoj js biblioteci
	// funkcija findAndReplaceDOMText pronalazi reci u tekstu i stavlja ih u html span elemente kojima se daje klasa dragdrop, 
	// regular expresion kojim se biraju reci u tekstu dobija se putem ajax zahteva serveru, ajax zahtevom traze se svi subjekti i objekti koje taj rdf fajl sadrzi
	// mainDiv predstavlja id diva koji u kome se traze reci
	//
	function spanReadMode(rdfGraphName)
	{
			$.ajax({
			  type: "POST",
			  url: rdfController + "/getSubjectsObjects",
			  data: { 	
				  		rdfGraph: rdfGraphName
			  		}
		
			}).done(function( response ) {

				var str = "(?=\\W|\\s|\\b|^)(" + response + ")(?=\\W|$)|\\w+";
				
				//var str = "(?=\\W|\\s|\\b|^)(" + response + ")(?=\\W|$)|[a-zA-Z\\p{šćčđž}]|\\w+";
				
				
				
				//var str = "(?=\\W|\\s|\\b|^)(" + response + ")(?=\\W|$)|(?=\\W|\\s|\\b|^)(\\w+)(?=\\W|$)";
	            //var str = "(?=\\W|\\s|\\b|^)(" + response + ")(?=\\W|$)";

	            // var str = "(?:^|\\b)(" + response + ")(?=\\b|$)";
				//	var regex = new RegExp(response, 'gi');
	                var regex = new RegExp(str, 'gi');
				
			//	var regex = new RegExp(response, 'gi');
               
               for(var i=1;i<=7;i++)
				{ 
    				findAndReplaceDOMText(
    					regex,
    					$("#lessionDiv" + i).get(0),
    					function(fill, matchIndex) {
    					var el = document.createElement('span');
    					el.setAttribute("class", "dragdrop");
    					//el.setAttribute("style", "color:grey");
    					el.innerHTML = fill;
    					return el;
    					}
    				);
                   		// recima u tekstu se daje drag & drop funkcionalnost
                        makeDraggableDroppable();
                }
                

				
				
			});
	}

	
	// ========================= spanEditMode() ========================
	//
	// Funkcija za spanovanje teksta u Edit modu
	// poziva je funkcija span()
	// koristi funkciju findAndReplaceDOMText, definisanu u eksternoj js biblioteci
	// funkcija findAndReplaceDOMText pronalazi reci u tekstu i stavlja ih u html span elemente kojima se daje klasa dragdrop, 
	// \w+/g predsatvlja regular expresion kojim se biraju sve reci u tekstu, mainDiv predstavlja id diva koji u kome se traze reci
	//
	function spanEditMode()
	{
		findAndReplaceDOMText(
			/\w+/g,
			mainDiv,
			function(fill, matchIndex) {
			var el = document.createElement('span');
			el.setAttribute("class", "dragdrop");
			el.innerHTML = fill;
			return el;
			}
		);

		// recima u tekstu se daje drag & drop funkcionalnost
		makeDraggableDroppable();
	}
	
	
	function setClickEventHandlerStatementsDiv()
	{

		$(".answerPar").hover(function() {
			
			$(this).css('cursor','pointer');
			
			}, function() {
			
			$(this).css('cursor','auto');
			
		});
		
		$(".answerPar").click(function()
		{
				$(".answerPar").css('background', '#fff');
				$(".answerPar").css('color', '#000');
				$(".answerPar span:first-child").css('color', 'green');

				$(this).css('background', '#4889C2');
				$(this).css('color', '#fff');
				$("#" + this.id + " span:first-child").css('color', '#fff');

			//alert( "Ovo je ID kliknutog odgovora " + this.id + " "  );
				answerIdString = this.id;
		});
	}
/*	
	function setClickEventHandlerTrueFalseRadioBtn()
	{
		
		$("input[name='trueFalse']").click(function() {
			
		    this.value = "true";
		    this.html("tacno");
		    alert(this.value);
		});
		
	}
	
	function RadionButtonSelectedValueSet(name, SelectedValue) {
	    $('input[name="' + name+ '"]').val([SelectedValue]);
	}
	*/
	/*
	function getAllLessionsFromServer()
	{
		getTextFromServer(1, "tekst1.html", "model.rdf");
		getTextFromServer(2, "tekst2.html", "model.rdf");
		getTextFromServer(3, "tekst3.html", "model.rdf");
		getTextFromServer(4, "tekst4.html", "model.rdf");
		getTextFromServer(5, "tekst5.html", "model.rdf");
		getTextFromServer(6, "tekst6.html", "model.rdf");
		getTextFromServer(7, "tekst7.html", "model.rdf");
		getTextFromServer(8, "tekst8.html", "model.rdf");
		getTextFromServer(9, "tekst9.html", "model.rdf");
		getTextFromServer(10, "tekst10.html", "model.rdf");
	}
	*/
	//getAllLessionsFromServer();