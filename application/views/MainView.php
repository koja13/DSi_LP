

<script src="<?php echo base_url('assets/countdownTimer/countdown/jquery.countdown.ReadMode.js')?>"></script>
<script src="<?php echo base_url('assets/countdownTimer/js/ReadModeCountdownScript.js')?>"></script>


<div id='navigationDiv'>
<img id="logo" align="right" style= "height:25px; width:65px; padding-left:10px;" src="<?php echo base_url('DSiAlogo.bmp')?>" />
<!--

<span id="lessionNumberSpan1">
	<span id="lessionNumberSpan2">
		
	</span>  of 5
</span>

-->

<!--<div id='navProgressDiv'>-->


<!-------------------------- progressDiv --------------------------->
	<!--<div id="progressOutDiv">
		<div id="progressInDiv">
		</div>
	</div>-->
<!--</div>-->

<nav> <?php /*echo anchor('usercontroller/startQuiz', 'Start test', array('id'=>'startTest') ) . " | "; */if($this->session->userdata('account_type') =="f") {echo anchor('/usercontroller/registerFBUser', 'Register') ;} /*. " | "; } echo anchor('usercontroller/logout', 'Logout', array('class'=>'focus') ); */?> </nav>
</div>

<!------------------------- mainDiv, centralni div u koji se ucitava tekst ------------------------->
<div id='mainDiv'>
<div class='lessionDiv' id='lessionDiv1'>

</div>
<script>
	// slanje informacije o akciji poktretanja sistema za ucenje
	sendUserActionsLessions(currentLessionNumber, "start_dsi", null);
</script>

    
	<?php $attributes = array('class' => 'chooseanswer');
			
		//echo form_open("UserController/login", $attributes); 
	?>
			
		<br />
			

				
		        
		               
	<?php// echo form_close(); ?>
	
	
	
	

</div>
	




		<div id="bottomDiv" class="answerDiv">
					
			<span id = "spanCloseId"class="close">&times;</span>
			
			<div id="statementDiv"> 
						
			<!--  <h4>Choose one answer:</h4>-->
							
			<!--	<p class="answerPar" id="idAnswer1"> 1. Izaberite opciju jedan </p>
				<p class="answerPar" id="idAnswer2"> 2. Opcija dva je izabrana </p>
				<p class="answerPar" id="idAnswer3"> 3. Treci izbor je najpamentiji </p>
			-->
			</div>	
				<div id="answerButtons">			
					<input type="submit" id="submitAnswerButton" value="Submit answer!" />
					<input type="submit" id="submitNoAnswerButton" value="Everything is false!" />
				</div>
				
		</div>
		
<script>

	//	var addOrRemove = true;


</script>
	
<div id="countDiv">
	<div id="countdown"> </div>
</div>
<!--  



<div id="bottomDiv">

		 <span class="close">&times;</span>
		 
		 <div id="statementDiv"> </div>
		 
</div>-->