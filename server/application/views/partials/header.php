<div class="md-modal md-effect-4" id="modal-44">
   <div class="md-content" id="forgotpass">
      <h3>KitShare</h3>
      <div style="padding:0;">
         <div class="main" id="ssuccess">
		    <div id="success-alert" style="background-color:#fff;border-color:#fff;color:gray;"> </div>
		 </div>
   </div>
</div>
</div>

<div class="md-modal md-effect-4" id="modal-4">
   <div class="md-content">
      <h3>Welcome to KitShare</h3>
	  <a data-modal="modal-5" class=" md-trigger" href="#" ng-click="myFunction()"><p>Already a member ? <span>Sign in</p> </a>
      
      <div>
         <div class="main">
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <a href="javascript:;" ng-click="fbLogin()" class="btn btn-block btn-social btn-facebook">
                  <i class="fa fa-facebook"></i> Sign in with Facebook
                  </a>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <a class="btn btn-block btn-social btn-google-plus" >
                  <i class="fa fa-google-plus"></i> Sign in with Google
                  </a>
                  <p class="text20">KitShare will never post without your permission.</p>
               </div>
            </div>
            <div class="login-or">
               <hr class="hr-or">
               <span class="span-or">or</span>
            </div>
            <div id="errormsg_chk">
            </div>
            <div id="errormsg">
               <div class=''>
                  <div ng-show="form_registration.$submitted||form_registration.user_name.$touched" class='salert alert-warning'>
                     <span class="val_error" ng-show="form_registration.user_name.$error.required">Firstname is left blank!</span>
                  </div>
                  <div ng-show="form_registration.$submitted||form_registration.user_last_name.$touched" class='salert alert-warning'>
                     <span class="val_error" ng-show="form_registration.user_last_name.$error.required">Lastname is left blank!</span>
                  </div>
                  <div ng-show="form_registration.$submitted||form_registration.user_email.$touched" class='salert alert-warning'>
                     <span class="val_error" ng-show="form_registration.user_email.$error.required">Email required.</span>
                     <span class="val_error" ng-show="form_registration.user_email.$error.email">Invalid Email.</span>
                  </div>
                  <div ng-show="form_registration.$submitted||form_registration.user_password.$touched" class='salert alert-warning'>
                     <span class="val_error" ng-show="form_registration.user_password.$error.minlength">Password must be 6 charecter!.</span>
                     <span class="val_error" ng-show="form_registration.user_password.$error.required">Password required.</span>
                  </div>
               </div>
            </div>
            <form name="form_registration"  novalidate ng-submit="registration()">
               <div class="col-sm-6 col-md-6">
                  <div class="form-group">
                     <input type="text" class="form-control form_design" id="URfirstname" placeholder="First Name" name="user_name" ng-model="user_name" required/>
                  </div>
               </div>
               <div class="col-sm-6 col-md-6">
                  <div class="form-group">
                     <input type="text" class="form-control form_design" id="URlastname" placeholder="Last Name" name="user_last_name" ng-model="user_last_name" required/>
                  </div>
               </div>
               <div class="col-sm-6 col-md-6">
                  <div class="form-group">
                     <input type="email" class="form-control form_design" id="URemail" placeholder="Email Address" name="user_email" ng-model="user_email" required/>
                  </div>
               </div>
               <div class="col-sm-6 col-md-6">
                  <div class="form-group">
                     <input type="password" class="form-control form_design" id="URpassword" placeholder="Password" name="user_password" ng-model="user_password" ng-minlength="6" required/>
                  </div>
               </div>
               <div class="col-sm-12 col-md-12">
                  <div class="form-group">
                     <div class="checkbox checkbox12">
                        <div class="checkbox">
                           <input id="chk" type="checkbox" ng-init='chkselct=false' name="chkselct" ng-model="chkselct"/>
                           <label for="chk">By signing up as a member, I agree to KitShare</label>
                        </div>
                     </div>
                  </div>
               </div>
               <button class="md-close1" ng-disabled="form_registration.$invalid">Join Now</button>
               <button class="md-close">Close me!</button>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="md-modal md-effect-4" id="modal-5">
   <div class="md-content">
      <h3>Sign into KitShare</h3>
      <div>
         <div class="main">
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <!-- <fb:login-button 
                     scope="public_profile,email"
                     onlogin="checkLoginState();">
                     </fb:login-button>
                     -->
                  <a class="btn btn-block btn-social btn-facebook" ng-click="fbLogin()" href="javascript:;">
                  <i class="fa fa-facebook"></i> Sign in with Facebook
                  </a>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <!--<div id="google_signin_button"></div>-->
                  <a href="javascript:;" class="btn btn-block btn-social btn-google-plus" ng-click="gLogin()">
                  <i class="fa fa-google-plus"></i> Sign in with Google
                  </a>
               </div>
            </div>
            <div class="login-or">
               <hr class="hr-or">
               <span class="span-or">or</span>
            </div>
            <form name="form_login_modal" novalidate>
               <div class="col-sm-12 col-md-12">
                  <div id="error_msg" class="val_error">
                     <div ng-show="form_login_modal.username.$touched" class='salert alert-warning'>
                        <span class="val_error" ng-show="form_login_modal.username.$error.required">Please Enter your email address.</span>
                        <span class="val_error" ng-show="form_login_modal.username.$error.email">Invalid Email.</span>
                     </div>
                     <div ng-show="form_login_modal.password.$touched" class='salert alert-warning'>
                        <span class="val_error" ng-show="form_login_modal.password.$error.required">Please Enter your password.</span>
                     </div>
                     <div id="errormsglogin"></div>
                  </div>
                  <div class="form-group">
                     <input name="username" type="email" placeholder="Email" class="form-control form_design" ng-model="username" required/>
                  </div>
               </div>
               <div class="col-sm-12 col-md-12">
                  <div class="form-group">
                     <input name="password" type="password" placeholder="Password" class="form-control form_design" ng-model="password" required/>
                  </div>
               </div>
               <div class="col-sm-12 col-md-12">
                  <div class="form-group" style="text-align: right;">
                     <a class=" md-trigger" data-modal="modal-6" href="#" ng-click="myFunction()">
                     <i class="fa fa-lock"></i>
                     Forgot password?
                     </a>
                  </div>
               </div>
               <button type="button" class="md-close1" value="Login" ng-click="login()" ng-keyup="$event.keyCode == 13 && login()" ng-disabled="form_login_modal.$invalid">Sign in</button>
               <button class="md-close">Close me!</button>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="md-modal md-effect-4" id="modal-6">
   <div class="md-content">
      <h3>KitShare</h3>
      <div>
         <div class="main">
		    
            <form name="forgot_password_form" novalidate>
               <div class="col-sm-12 col-md-12">
			   <div id="forgotpass" class="val_error">
			    <div id="error_msg_fp" class="val_error">
                    <div ng-show="forgot_password_form.forgot_email.$touched">
                        <span class="val_error" ng-show="forgot_password_form.forgot_email.$error.required">Please Enter your email address.</span>
                        <span class="val_error" ng-show="forgot_password_form.forgot_email.$error.email">Invalid Email.</span>
                    </div>
                 </div> 
				 </div>
               </div>
               <div class="form-group">
                  <input name="forgot_email" type="email" class="inputtextBox form-control form_design" placeholder="Email address" ng-model="forgot_email" required/>
               </div>
         </div>
         <button type="button" class="md-close1" ng-click="forgot_password()" value="Submit" ng-disabled="forgot_password_form.forgot_email.$invalid">Submit</button>
         <button class="md-close">Close me!</button>
         </form>
		 </div>
   </div>
</div>
</div>
<div class="md-overlay"></div>
<!-- the overlay element -->







<section class="slider-nav-section top_nav navbar-fixed-top">
   <div class="navbar navbar-inverse navbar-static-top nav-bar-menu hidden-lg hidden-md hidden-sm">
      <div class="navbar-header">
         <a class="navbar-brand" href="#">
            <div class="logo animated bounceInDown"> <img src="assets/images/logo.png" class="img-responsive"> </div>
         </a>
      </div>
      <nav class="navbar navbar-fixed-left navbar-minimal animate" role="navigation">
         <div class="navbar-toggler animate">
            <span class="menu-icon"></span>
         </div>
         <ul class=" navbar-menu animate">
            <li>
               <form class="example" action="/action_page.php" style="max-width: 100%; margin-top: 7px;">
                  <input type="text" placeholder="Search.." name="search2">
                  <input type="text" placeholder="Pick a location....." name="search2">
                  <button type="submit" style="1px solid #dcdad9"><i class="fa fa-search"></i></button>
               </form>
            </li>
            <li><a href="#/">Home</a></li>
            <li ><a href="#app/gear">Search Gear</a></li>
            <li ><a href="#app/faq">FAQ</a></li>
            <li><a href="#app/blog">Blog</a></li>
            <li><a id="modal_Sginin" href="#" class=" md-trigger" data-modal="modal-5"><i class="fa fa-sign-in"></i>Login </a></li>
            <li><a id="modal_Sginup" href="#" class=" md-trigger" data-modal="modal-4"><i class="fa fa-sign-in"></i>Sginup</a></li>
            <li><a href="#" class=""><span><i class="fa fa-cart-plus addtocart"></i></span></a></li>
         </ul>
      </nav>
   </div>
   <div class="navbar navbar-inverse navbar-static-top nav-bar-menu  hidden-xs">
      <div class="navbar-header">
         <a class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> 
         <a class="navbar-brand" href="#">
            <div class="logo animated bounceInDown"> <img src="assets/images/logo.png" class="img-responsive"> </div>
         </a>
      </div>
      <div class="navbar-collapse collapse">
         <div class="col-sm-5 col-md-5 col-lg-6">
            <form class="example" action="/action_page.php" style="max-width: 100%; margin-top: 7px;">
               <input type="text" placeholder="Search.." name="search2">
               <input type="text" placeholder="Pick a location....." name="search2" id="us3-address"/>
               <button type="submit" style="border-left: 1px solid #dcdad9;"><i class="fa fa-search"></i></button>
            </form>
         </div>
         <ul  class="nav navbar-nav navigation">
            <li ng-show="!isAuthenticate"><a ng-click="myFunction()" href="#" class=" md-trigger" data-modal="modal-5">Sign In </a></li>
            <li ng-show="!isAuthenticate"><a ng-click="myFunction()" href="#" class=" md-trigger" data-modal="modal-4">Join Now</a></li>
            <li ng-show="!isAuthenticate"><a href="#" class=""><span><i class="fa fa-cart-plus addtocart"></i></span></a></li>
            <li>
               <nav class="navbar navbar-fixed-left navbar-minimal animate" role="navigation">
                  <div class="navbar-toggler animate">
                     <span class="menu-icon"></span>
                  </div>
                  <ul class=" navbar-menu animate">
                     <li><a href="#">Home</a></li>
                     <li ><a href="#/gear">Search Gear</a></li>
                     <li ><a href="#/faq">FAQ</a></li>
                     <li><a href="#/blog">Blog</a></li>
                  </ul>
               </nav>
            </li>
         </ul>
         <ul ng-show="isAuthenticate"class="nav navbar-nav navbar-right navbar-nav20" ng-init="init_userinfo()">
            <li><a href="#gearlist" class="hvr-bounce-to-bottom">List your Gear</a></li>
            <li><a href="#" class="hvr-bounce-to-bottom"><i class="fa fa-cart-plus addtocart"></i></a></li>
            <li class="dropdown">
			
               <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			   <script>
			       if($('#limage').attr('src') == '') {
	                $("#limage").attr("src","assets/images/profile.png");
                   } 
			   </script>
               <span class="profile_img">
			   <img id="limage" src="{{user_profile_picture_link}}"></span>{{app_user_first_name}}<span class="caret"></span></a>
               <script>$(".dropdown").hover(            
                  function() {
                  	$('.dropdown-menu', this).not('.in .dropdown-menu').stop( true, true ).slideDown("fast");
                  	$(this).toggleClass('open');        
                  },
                  function() {
                  	$('.dropdown-menu', this).not('.in .dropdown-menu').stop( true, true ).slideUp("fast");
                  	$(this).toggleClass('open');       
                  }
                  );
               </script>
               <ul  class="dropdown-menu dropdown-menu1">
                  <li><a href="#dashboard" class="hvr-bounce-to-bottom"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                  <li><a href="#Profile" class="hvr-bounce-to-bottom"><i class="fa fa-user"></i>Profile</a></li>
                  <li><a href="#" class="hvr-bounce-to-bottom"><i class="fa fa-heart" aria-hidden="true"></i>
                     Favorites</a>
                  </li>
                  <li><a href="#Editprofile" class="hvr-bounce-to-bottom"><i class="fa fa-cogs" aria-hidden="true"></i>
                     Settings</a> 
                  </li>
                  <li role="separator" class="divider"></li>
                  <li><a href="javascript:;" ng-click="logout()" class="hvr-bounce-to-bottom"><i class="fa fa-sign-out" aria-hidden="true"></i>
                     Sign Out</a>
                  </li>
               </ul>
            </li>
         </ul>
      </div>
   </div>
</section>
