window.fbAsyncInit = function() {
  FB.init({
    appId      : '128303734043111',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.0' // use version 2.0

  });
 
  };
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    
        ////////////////// logout korisnika koji je logovan na fb, takodje ga odjavi i sa facebook.com  ////
    //FB.logout(function(response) {
  // user is now logged out
//		});
	///////////////////////
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
	  
      /*FB.login(function(response)
			{
				 saveUserDataFB();
			}, {scope: 'email'});*/
	  saveUserDataFB();
			
     // testAPI(); ////////////////////////
	  
	  
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
	  
			FB.login(function(response)
			{
				 saveUserDataFB();
			}, {scope: 'email,public_profile'});
	  
	  
	  
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.'; /////////////////////////
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
	  
	  	    FB.login(function(response)
			{
				 saveUserDataFB();
			}, {scope: 'email,public_profile'});
	  
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.'; //////////////
    }
  }




    // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

    // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.
/*
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });*/

  /*
  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));*/

  
  
  
  
  
  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
 /* function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name + ' ' + response.email + '');
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }*/
  
  
  
    // Here we run a very simple test of the Graph API after login is successful. 
  // This testAPI() function is only called in those cases. 
  function saveUserDataFB() {
    
	console.log('Welcome!  Fetching your information.... ');
    
    FB.api('/me', function(response)
	{
	   console.log('Welcome!  ' + response.name);
     //  console.log('Welcome!  ' + response.username);
       console.log('Welcome!  ' + response.email);
       
		saveUserDataFromFB(response);
        
        

    });
  }

  function saveUserDataFromFB(response)
  {
	  $.ajax({
		  type: "POST",
		  url: config.site_url + "/usercontroller/getUserDataFB",
		  data: {	
					name: response.name,
					//username: response.username,
					email: response.email,
                    use_dsi: "yes",
					account_type: "f",
		  		}
		}).done(function( response )
		{
		
                 window.location = config.site_url + "/usercontroller/welcome";
			     //alert("Podaci su sacuvani!");
		});
  }
  