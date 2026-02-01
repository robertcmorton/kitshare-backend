<section class="dashboard_body fp">
<div class="md-content">
<h3>Reset Password</h3>
<div>
<div class="main">
       <div id="forgotpass">
         <div class="main">
		    <div id="errormsg">
               <div class=''>
			   <div id="mismatch"></div>
                <div ng-show="password_reset_form.user_password.$touched">
		            <span class="val_error" ng-show="password_reset_form.user_password.$error.required">password required.</span>
					<span class="val_error" ng-show="password_reset_form.user_password.$error.minlength">Password must be 6 charecter!.</span>
		        </div>
				<div ng-show="password_reset_form.user_con_password.$touched">
		            <span class="val_error" ng-show="password_reset_form.user_con_password.$error.required">confirm password required.</span>
		            <span class="val_error" ng-show="password_reset_form.user_con_password.$error.compareTo">Password not match.</span>
					<span class="val_error" ng-show="password_reset_form.user_con_password.$error.minlength">confirm must be 6 charecter!.</span>
		        </div>
               </div>
            </div>
            <form name="password_reset_form" novalidate>
               <div class="col-sm-12 col-md-12">
                  <div id="error_msg" class="val_error">
                     <span class="val_error" ng-show="forgot_password_form.forgot_email.$error.required">Please Enter your email address.</span>
                     <span class="val_error" ng-show="forgot_password_form.forgot_email.$error.email">Invalid Email.</span>
                  </div>
                  <div id="errormsglogin"></div>
               </div>
               <div class="form-group">
                  <input ng-minlength="6" name="user_password" id="user_password" type="password" class="form-control form_design inputtextBox" placeholder="New Password" ng-model="user_password" required/>
               </div>
			    <div class="form-group">
                  <input ng-minlength="6" name="user_con_password" id="user_con_password" type="password" class="form-control form_design inputtextBox" placeholder="Confirm Password" ng-model="user_con_password"  compare-to="user_password" required/>
               </div>
         </div>
         <button class="md-close1" type="submit" ng-click="resetpass()" value="Submit" ng-disabled="password_reset_form.$invalid">Submit</button>
         
         </form>
      </div>
</div>
</div>
</div>
</div>