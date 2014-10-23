<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html;charset=windows-1250">
		<link href="<?php echo base_url('dsi4.ico')?>" rel="icon" type="image/x-icon" />
		
		<!-------------------------------------- css fajlovi  -------------------------------------->	
	    <link rel="stylesheet" href="<?php echo base_url('assets/countdownTimer/css/styles.css')?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets/countdownTimer/countdown/jquery.countdown.css')?>" />
        
        <link rel="stylesheet" href="<?php echo base_url('assets/css/tabs.css')?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css');?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/AssesmentStyle.css');?>" />
        
                <link rel="stylesheet" href="<?php echo base_url('assets/css/QuizStyle.css')?>" />

<script type="text/javascript" >

 var config = {
     base_url: "<?php echo base_url(); ?>",
     site_url: "<?php echo site_url(); ?>",
     use_dsi: "<?php echo $this->session->userdata('use_dsi'); ?>",
     mode: "<?php echo $mode; ?>",
 };

</script>
 
	<!--    
	<style type="text/css">
	
	.bodyClass {
		background-image:url('<?php //echo base_url();?>background.jpg');
	}
	</style>
	 --> 
       
	<!------------------------------------- jQuery biblioteke  ------------------------------------->
	<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-1.7.2.js');?>"></script>	
	<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery-ui.min.js');?>"></script>

	<!--------------------------------- js skripta za spanovanje  ---------------------------------->
	<script type="text/javascript" src="<?php echo base_url('/assets/js/findAndReplaceDOMText.js');?>"></script>
	
	<!------------------------------------- glavna js scripta  ------------------------------------->
	<script type="text/javascript" src="<?php echo base_url('/assets/js/MainScript.js');?>"></script>


	
	
<title><?php echo (isset($title)) ? $title : "DSi1.5" ?> </title>

</head>
<body class="bodyClass">

	<div id="wrapper">