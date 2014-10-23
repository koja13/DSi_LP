<div id="fb-root"></div>

<script>

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=128303734043111&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

</script>

<!------------------------- ucitavanje fbLoginScript javascript fajla, js fajl za logovanje korisnika na sistem pomocu fb naloga ------------------------->
<script src="<?php echo base_url('assets/js/fbLoginScript.js')?>"></script>

<div id="content">

<div id='navigationDiv'>
<nav><a href="#" class="focus">Log In</a> | <a href="<?php echo site_url("/usercontroller/register"); ?>">Register</a></nav>
</div>
	<div class="signup_wrap">
	
			<?php if(isset($message))
				  {
					echo "<div id='thanksDiv'>" . $message . "</div>";
				  }
			?>
			<div class="signin_form">
		
			<?php $attributes = array('class' => 'signin');
				
			echo form_open("usercontroller/login", $attributes); 
			
			?>
			
			<br />
			
				<h2>Log In</h2>
			    
		    	<input type="text" id="email_address" name="email_address" class="text-field" placeholder="E-mail" value="" />
				<input type="password" id="pass" name="pass" class="text-field" placeholder="Password" value="" />
				
				<?php if(isset($error_message))
			  		{
						echo "<div class='error'>* " . $error_message . "</div>";
			  		}
				?>
				
		        <input type="submit" class="button" value="Sign in" />
		        
		        <br /><br /><h4>-- or --</h4><br />
                
                <div class="fb-login-button" scope="public_profile, email" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" onlogin="checkLoginState();">Log in using Facebook</div>
                <div id="status"></div>


		    <?php echo form_close(); ?>
		    
		</div><!--<div class="signin_form">-->
	</div><!--<div class="signup_wrap">-->
 
</div><!--<div id="content">-->