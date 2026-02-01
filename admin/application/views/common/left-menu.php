<?php  //echo $this->uri->segment(1); die; ?>
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  
  <div class="scroll-sidebar">
    <!-- User profile -->
    <div class="user-profile" style="background: url(<?php echo base_url();?>assets/images/background/user-info.jpg) no-repeat;">
      <!-- User profile image -->
      <div class="profile-img"> <img src="<?php echo base_url();?>assets/images/users/profile.png" alt="user" /> </div>
      <!-- User profile text-->
      <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><?php echo $this->session->userdata('ADMIN_USERNAME'); ?></a>
        <div class="dropdown-menu animated flipInY"> <a href="#" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
          <div class="dropdown-divider"></div>
          <a href="<?php echo base_url(); ?>logout" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a> </div>
      </div>
    </div>
    <!-- End User profile text-->
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
      <ul id="sidebarnav">
        <!--<li class="nav-small-cap">PERSONAL</li>-->
        <li class="active"> <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>home" ><i class="mdi mdi-gauge"></i>Dashboard</a> </li>
        <li class="nav-devider"></li>
        <li <?php if($this->uri->segment(1)=='users' or $this->uri->segment(1)=='app_role' or $this->uri->segment(1)=='menu_privilege' or $this->uri->segment(1)=='user_role' or $this->uri->segment(1)=='app_module' or  $this->uri->segment(1)=='app_module' or $this->uri->segment(1)=='role_module' or $this->uri->segment(1)=='app_users')  echo 'class="active"'; ?>> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="<?php if($this->uri->segment(1)=='users' or $this->uri->segment(1)=='app_role' or $this->uri->segment(1)=='menu_privilege' or $this->uri->segment(1)=='user_role' or $this->uri->segment(1)=='app_module' or  $this->uri->segment(1)=='app_module' or $this->uri->segment(1)=='role_module' or $this->uri->segment(1)=='app_users')  echo 'true'; else echo 'false' ; ?>"><span class="hide-menu">Manage Roles & Privileges </span></a>
		
          <ul aria-expanded="<?php if($this->uri->segment(1)=='users' or $this->uri->segment(1)=='app_role' or $this->uri->segment(1)=='menu_privilege' or $this->uri->segment(1)=='user_role' or $this->uri->segment(1)=='app_module' or  $this->uri->segment(1)=='app_module' or $this->uri->segment(1)=='role_module' or $this->uri->segment(1)=='app_users')  echo 'true'; else echo 'false' ; ?>" class="collapse <?php if($this->uri->segment(1)=='users' or $this->uri->segment(1)=='app_role' or $this->uri->segment(1)=='menu_privilege' or $this->uri->segment(1)=='user_role' or $this->uri->segment(1)=='app_module' or  $this->uri->segment(1)=='app_module' or $this->uri->segment(1)=='role_module' or $this->uri->segment(1)=='app_users') echo 'in'; ?>">
            <li <?php if($this->uri->segment(1)=='users') {?>class="active" <?php }?> ><a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='users') echo 'active'; ?>" href="<?php echo base_url();?>users"  >User Management</a> </li>
            <li <?php if($this->uri->segment(1)=='app_role') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='app_role') echo 'active'; ?>" href="<?php echo base_url();?>app_role" >Role Management</a> </li>
            <li <?php if($this->uri->segment(1)=='menu_privilege') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='menu_privilege') echo 'active'; ?>" href="<?php echo base_url();?>menu_privilege" >Role Privilege Management</a> </li>
            <li <?php if($this->uri->segment(1)=='user_role') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='user_role') echo 'active'; ?>" href="<?php echo base_url();?>user_role" >User Role Management</a> </li>
            <li <?php if($this->uri->segment(1)=='app_module') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='app_module') echo 'active'; ?>" href="<?php echo base_url();?>app_module" >Module Management</a> </li>
            <li <?php if($this->uri->segment(1)=='role_module') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='role_module') echo 'active'; ?>" href="<?php echo base_url();?>role_module" >Role Module Management</a> </li>
            <li <?php if($this->uri->segment(1)=='app_users') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='app_users') echo 'active'; ?>" href="<?php echo base_url();?>app_users" >App User Management</a> </li>
			<li <?php if($this->uri->segment(1)=='app_users_certficate') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='app_users_certficate') echo 'active'; ?>" href="<?php echo base_url();?>app_users_certificate" >App User Certificate</a> </li>
          </ul>
		  </li>
		 
		 <li <?php if($this->uri->segment(1)=='pages') { ?> class="active" <?php } ?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>pages" >Manage Page Content</a> </li>
		  
		  <li <?php if($this->uri->segment(1)=='Faq')   echo 'class="active"'; ?>> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="<?php if($this->uri->segment(1)=='faq')  echo 'true'; else echo 'false' ; ?>"><span class="hide-menu">Manage FAQ </span></a>
    
          <ul aria-expanded="<?php if($this->uri->segment(1)=='users' or $this->uri->segment(1)=='app_role' or $this->uri->segment(1)=='menu_privilege' or $this->uri->segment(1)=='user_role' or $this->uri->segment(1)=='app_module' or  $this->uri->segment(1)=='app_module' or $this->uri->segment(1)=='role_module' or $this->uri->segment(1)=='app_users')  echo 'true'; else echo 'false' ; ?>" class="collapse <?php if($this->uri->segment(1)=='users' or $this->uri->segment(1)=='app_role' or $this->uri->segment(1)=='menu_privilege' or $this->uri->segment(1)=='user_role' or $this->uri->segment(1)=='app_module' or  $this->uri->segment(1)=='app_module' or $this->uri->segment(1)=='role_module' or $this->uri->segment(1)=='app_users') echo 'in'; ?>">
            <li <?php if( $this->uri->segment(2)=='add') {?>class="active" <?php }?> ><a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(2)=='add') echo 'active'; ?>" href="<?php echo base_url();?>Faq"  >Faq Categories</a> </li>
            <li <?php if($this->uri->segment(2)=='Faqlist') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(2)=='Faqlist') echo 'active'; ?>" href="<?php echo base_url();?>Faq/Faqlist" >FAQ</a> </li>
            
          </ul>
      </li> 
		  
        <li <?php if($this->uri->segment(1)=='country' or $this->uri->segment(1)=='state' or $this->uri->segment(1)=='suburb')  echo 'class="active"'; ?>> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="<?php if($this->uri->segment(1)=='country' or $this->uri->segment(1)=='state' or $this->uri->segment(1)=='suburb')  echo 'true'; else echo 'false' ; ?>"><span class="hide-menu">Manage Locations </span></a>
          <ul aria-expanded="<?php if($this->uri->segment(1)=='country' or $this->uri->segment(1)=='state' or $this->uri->segment(1)=='suburb')  echo 'true'; else echo 'false' ; ?>" class="collapse">
            <li <?php if($this->uri->segment(1)=='country') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='country') echo 'active'; ?>" href="<?php echo base_url();?>country" >Country Management</a> </li>
            <li <?php if($this->uri->segment(1)=='state') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='state') echo 'active'; ?>" href="<?php echo base_url();?>state" >State Management</a> </li>
            <li <?php if($this->uri->segment(1)=='suburb') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='suburb') echo 'active'; ?>" href="<?php echo base_url();?>suburb" >Suburb Management</a> </li>
          </ul>
        <li <?php if($this->uri->segment(1)=='owner_type') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>owner_type" >Owner Types Management</a> </li>
		<li <?php if($this->uri->segment(1)=='profession_type') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>profession_type" >Profession Type Management</a> </li>
        <li <?php if($this->uri->segment(1)=='manufacturers' or $this->uri->segment(1)=='models' or $this->uri->segment(1)=='gear_categories' or $this->uri->segment(1)=='gear_desc' or $this->uri->segment(1)=='gear_features' or  $this->uri->segment(1)=='feature_master'  or  $this->uri->segment(1)=='Insurance' )  echo 'class="active"'; ?>> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="<?php if($this->uri->segment(1)=='manufacturers' or $this->uri->segment(1)=='models' or $this->uri->segment(1)=='gear_categories' or $this->uri->segment(1)=='gear_desc' or $this->uri->segment(1)=='gear_features' or  $this->uri->segment(1)=='feature_master')  echo 'true'; else 'false' ; ?>"><span class="hide-menu">Manage Gears </span></a>
          <ul aria-expanded="false" class="collapse">
            <li <?php if($this->uri->segment(1)=='manufacturers') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='manufacturers') { echo 'active' ; }?>" href="<?php echo base_url();?>manufacturers" >Manufacturer Management</a> </li>
            <li <?php if($this->uri->segment(1)=='models') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='models') { echo 'active' ; }?>" href="<?php echo base_url();?>models" >Model Management</a> </li>
            <li <?php if($this->uri->segment(1)=='gear_categories') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='gear_categories') { echo 'active'; }?>" href="<?php echo base_url();?>gear_categories" >Gear Category Management</a> </li>
            <li <?php if($this->uri->segment(1)=='gear_desc') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='gear_desc') { echo 'active' ; }?>" href="<?php echo base_url();?>gear_desc" >Gear Description Management</a> </li>
            <!--<li <?php //if($this->uri->segment(1)=='gear_features') {?>class="active" <?php //}?> > <a class="has-arrow waves-effect waves-dark <?php //if($this->uri->segment(1)=='gear_features') { echo 'active' ; }?>" href="<?php //echo base_url();?>gear_features" >Gear Feature Management</a> </li>-->
            <li <?php if($this->uri->segment(1)=='feature_master') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='feature_master') { echo 'active' ; }?>" href="<?php echo base_url();?>feature_master" >Category Feature Management</a> </li>
            <li <?php if($this->uri->segment(1)=='Insurance') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='Insurance') { echo 'active' ; }?>" href="<?php echo base_url();?>Insurance" >Insurance</a> </li>
             <li <?php if($this->uri->segment(1)=='Orders') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='Orders') { echo 'active' ; }?>" href="<?php echo base_url();?>Orders" >Orders</a> </li>
             <li <?php if($this->uri->segment(1)=='Payments') {?>class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark <?php if($this->uri->segment(1)=='Payments') { echo 'active' ; }?>" href="<?php echo base_url();?>Payments" >Payments</a> </li>
            <!--<li <?php //if($this->uri->segment(1)=='gear_percentage') {?>class="active" <?php //}?> > <a class="has-arrow waves-effect waves-dark <?php //if($this->uri->segment(1)=='gear_percentage') { echo 'active' ; }?>" href="<?php //echo base_url();?>	gear_percentage" >Gear  Percentage Rate Management</a> </li>-->
			
			<!-- <li <?php if($this->uri->segment(1)=='cancellation_policy') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>cancellation_policy" >Cancellation Policy</a> </li> -->
			
          </ul>
		  </li>
		  
		  <li <?php if($this->uri->segment(1)=='cust_gear_reviews' or $this->uri->segment(1)=='cust_gear_star_rating' or $this->uri->segment(1)=='user_bank_details')  echo 'class="active"'; ?>> <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="<?php if($this->uri->segment(1)=='cust_gear_reviews' or $this->uri->segment(1)=='cust_gear_star_rating' or $this->uri->segment(1)=='user_bank_details')  echo 'true'; else 'false' ; ?>"><span class="hide-menu">Customer/ User Category </span></a>
          <ul aria-expanded="false" class="collapse">
            <li <?php if($this->uri->segment(1)=='cust_gear_reviews') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>cust_gear_reviews" >Customer Gear Reviews</a> </li>
            <!-- <li <?php if($this->uri->segment(1)=='cust_gear_star_rating') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>cust_gear_star_rating" >Customer Gear Star Rating</a> </li> -->
      <li <?php if($this->uri->segment(1)=='user_bank_details') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>user_bank_details" >User Bank Details Management</a> </li>
			<li <?php if($this->uri->segment(1)=='banks') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>banks" >Bank  Management</a> </li>
			
			
          </ul>
		  </li>
		  
        <!-- <li <?php if($this->uri->segment(1)=='payment_mode_master') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>payment_mode_master" >Payment Modes Management</a> </li> -->
		
    <li <?php if($this->uri->segment(1)=='settings') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>settings" >Manage Settings</a> </li>
    <li <?php if($this->uri->segment(1)=='Logdetails') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>Logdetails" >Manage Log Details</a> </li>
		<li <?php if($this->uri->segment(1)=='sitemap') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>sitemap" >Generate SiteMap</a> </li>
    <li <?php if($this->uri->segment(1)=='Apilogdetails') {?> class="active" <?php }?> > <a class="has-arrow waves-effect waves-dark" href="<?php echo base_url();?>Apilogdetails" >API Logs</a> </li>
      </ul>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
  <!-- Bottom points-->  
</aside>
