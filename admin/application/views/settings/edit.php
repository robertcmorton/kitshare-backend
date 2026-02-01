<style>
	#gs_country_name {
	  border: none;
	  border-radius: 0px;
	  padding-left: 0px;
	  border-bottom: 1px solid #d9d9d9; }
	#is_active {
	  border: none;
	  border-radius: 0px;
	  padding-left: 0px;
	  border-bottom: 1px solid #d9d9d9; }
</style>
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
<!-- ============================================================== -->
<!-- Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<div class="row page-titles">
  <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Modify Settings</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Modify Settings</li>
    </ol>
  </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Start Page Content -->
<!-- ============================================================== -->
<div class="row">
<div class="col-12">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Modify</h4>
	  <?php echo $this->session->flashdata('success'); ?>
      <form class="form-material m-t-40" action="<?php echo base_url();?>settings/update_settings" method="post" enctype="multipart/form-data">
        <div class="row">
		  <div class="col-sm-3">
            <div class="form-group mbr">
              <img src="<?php echo FRONT_URL;?>site_img/<?php echo $settings[0]->setting_logo;?>" width="100px" dynsrc="100px" />
			 </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Site Logo:</label>
              <input type="hidden" value="<?php echo $settings[0]->settings_id; ?>" name="settings_id" id="settings_id" />
              <input type="file" name="setting_logo" id="setting_logo" class="form-control" value="<?php //echo $result[0]->gs_country_name; ?>" />
              <label for="setting_logo" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('setting_logo','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="clr"></div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Site Phone:</label>
              <input type="text" name="setting_phone" id="setting_phone" class="form-control" value="<?php echo $settings[0]->setting_phone; ?>" />
              <label for="setting_phone" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('setting_phone','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="clr"></div>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Settings Email:</label>
              <input type="text" name="setting_email" id="setting_email" class="form-control" value="<?php echo $settings[0]->setting_email; ?>" />
              <label for="setting_email" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('setting_email','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6" style="display:none">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Settings Twitter:</label>
              <input type="url" name="setting_twitter" id="setting_twitter" class="form-control" value="<?php echo $settings[0]->setting_twitter; ?>" />
              <label for="setting_twitter" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('setting_twitter','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6" >
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Settings LinkedIn:</label>
              <input type="url" name="setting_linked_in" id="setting_linked_in" class="form-control" value="<?php echo $settings[0]->setting_linked_in; ?>" />
              <label for="setting_linked_in" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('setting_linked_in','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="clr"></div>
        
		<div class="row">
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">GST%:</label>
              <input type="text" name="gst_percent" id="gst_percent" class="form-control" value="<?php echo $settings[0]->gst_percent; ?>" />
              <label for="gst_percent" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('gst_percent','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Conversion rate from USD to AUD:</label>
              <input type="text" name="usd_to_aud" id="usd_to_aud" class="form-control" value="<?php echo $settings[0]->usd_to_aud; ?>" />
              <label for="usd_to_aud" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('usd_to_aud','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Client ID (Australian Post Digital ID ):</label>
              <input type="text" name="digitalId_clientid" id="digitalId_clientid" class="form-control" value="<?php echo $settings[0]->digitalId_clientid; ?>" />
              <label for="digitalId_clientid" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('digitalId_clientid','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Secret ID (Australian Post Digital ID ):</label>
              <input type="text" name="digitalId_secretid" id="digitalId_secretid" class="form-control" value="<?php echo $settings[0]->digitalId_secretid; ?>" />
              <label for="digitalId_secretid" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('digitalId_secretid','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">API  URL (Australian Post Digital ID ) Frontend:</label>
              <input type="text" name="digitalId_url" id="digitalId_url" class="form-control" value="<?php echo $settings[0]->digitalId_url; ?>" />
              <label for="digitalId_url" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('digitalId_url','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">API  URL (Australian Post Digital ID ) Backend:</label>
              <input type="text" name="digitalId_url_backend" id="digitalId_url_backend" class="form-control" value="<?php echo $settings[0]->digitalId_url_backend; ?>" />
              <label for="digitalId_url_backend" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('digitalId_url_backend','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">API  URL (UserInfo for Digital ID ) Backend:</label>
              <input type="text" name="digitalId_url_userinfo" id="digitalId_url_userinfo" class="form-control" value="<?php echo $settings[0]->digitalId_url_userinfo; ?>" />
              <label for="digitalId_url_userinfo" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('digitalId_url_userinfo','<span class="text-danger">','</span>'); ?> </div>
          </div>
		  <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Digital Id API Host:</label>
              <input type="text" name="api_host" id="api_host" class="form-control" value="<?php echo $settings[0]->api_host; ?>" />
              <label for="api_host" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('api_host','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Facebook Link:</label>
              <input type="text" name="fb_link" id="fb_link" class="form-control" value="<?php echo $settings[0]->fb_link; ?>" />
              <label for="fb_link" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('fb_link','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Twitter Link:</label>
              <input type="text" name="twitter_link" id="twitter_link" class="form-control" value="<?php echo $settings[0]->twitter_link; ?>" />
              <label for="twitter_link" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('twitter_link','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Instagram Link</label>
              <input type="text" name="instagram_link" id="instagram_link" class="form-control" value="<?php echo $settings[0]->instagram_link; ?>" />
              <label for="instagram_link" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('instagram_link','<span class="text-danger">','</span>'); ?> </div>
          </div>
         
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Braintree URL :</label>
              <input type="text" name="braintree_url" id="braintree_url" class="form-control" value="<?php echo $settings[0]->braintree_url; ?>" />
              <label for="braintree_url" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('braintree_url','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Braintree Merchant ID  :</label>
              <input type="text" name="braintree_merchant_id" id="braintree_merchant_id" class="form-control" value="<?php echo $settings[0]->braintree_merchant_id; ?>" />
              <label for="braintree_merchant_id" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('braintree_merchant_id','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Braintree Public Key :</label>
              <input type="text" name="braintree_public_key" id="braintree_public_key" class="form-control" value="<?php echo $settings[0]->braintree_public_key; ?>" />
              <label for="braintree_public_key" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('braintree_public_key','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mbr">
              <label for="exampleInputEmail1">Braintree Private Key :</label>
              <input type="text" name="braintree_private_key" id="braintree_private_key" class="form-control" value="<?php echo $settings[0]->braintree_private_key; ?>" />
              <label for="braintree_private_key" class="error" style="color:#FF0000; display:none;"></label>
              <?php echo form_error('braintree_private_key','<span class="text-danger">','</span>'); ?> </div>
          </div>
          <div class="clr"></div>
        </div>
        <div class="box-footer">
          <button type="submit" class="btn btn-success">Edit</button>
        </div>
        <div class="clr"></div>
      </form>
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
