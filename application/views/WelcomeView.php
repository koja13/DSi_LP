<div id="content">
<div id='navigationDiv'>
<nav><?php //echo anchor('usercontroller/logout', 'Logout', array('class'=>'focus') ); ?></nav>
</div>

<script>
sendUserActionsLessions(null, "logged_in", null);
</script>

		<?php $attributes = array('class' => 'welcome');
				
			
		echo form_open("usercontroller/start", $attributes); ?>
		
	<img id="logo" align="right" style= "height:100px; width:250px; padding-left: 5px;" src="<?php echo base_url('DSiAlogo.bmp')?>" />
        
			<h3> <br /> Welcome <?php echo $this->session->userdata('user_name'); ?>! <br /><br />Test your knowledge using DSi tool. Connect as many terms as you can for the specified time.</h3>

			<input type="submit" class="button" value="Start!" />
			
		<?php echo form_close(); ?>
		
</div><!--<div id="content">-->