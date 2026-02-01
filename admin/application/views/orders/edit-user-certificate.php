<style>

#username {

  border: none;

  border-radius: 0px;

  padding-left: 0px;

  border-bottom: 1px solid #d9d9d9; }

#password {

  border: none;

  border-radius: 0px;

  padding-left: 0px;

  border-bottom: 1px solid #d9d9d9; }

#cnfpwd {

  border: none;

  border-radius: 0px;

  padding-left: 0px;

  border-bottom: 1px solid #d9d9d9; }

#email {

  border: none;

  border-radius: 0px;

  padding-left: 0px;

  border-bottom: 1px solid #d9d9d9; }



img {

    border-radius: 50%;

}

  

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

        <h3 class="text-themecolor m-b-0 m-t-0">User Certificate</h3>

        <ol class="breadcrumb">

          <li class="breadcrumb-item"><a href="<?php echo base_url() ?>home">Dashboard</a></li>

		  <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users_certificate">App User Certificate List</a></li>

          <li class="breadcrumb-item active">User Certificate</li>

        </ol>

      </div>

    </div>

    <!-- ============================================================== -->

    <!-- End Bread crumb and right sidebar toggle -->

    <!-- ============================================================== -->

    <!-- ============================================================== -->

    <!-- Start Page Content -->

    <!-- ============================================================== -->

    <!-- Row -->

    <div class="row">

      <!-- Column -->

    

      <!-- Column -->

      <!-- Column -->

      <div class="col-lg-12 col-xlg-9 col-md-7">

        <div class="card">

          <!-- Nav tabs -->

          <ul class="nav nav-tabs profile-tab" role="tablist">

            <!-- <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab">Timeline</a> </li>

            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab">Profile</a> </li>-->

            <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#settings" role="tab">Details</a> </li>

          </ul>

          <!-- Tab panes -->

          <div class="tab-content">

            <div class="tab-pane active" id="settings" role="tabpanel">

              <div class="card-body">

                <form class="form-horizontal form-material" id="myFrm" action="<?php echo base_url();?>app_users_certificate/update" method="post" enctype="multipart/form-data">

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-group">

                        <label>Certificate of Insurance</label>

						            <input type="hidden" name="user_insurance_proof_id" value="<?php echo $app_users[0]->user_insurance_proof_id ; ?>">

						            <input type="text" class="form-control form-control-line" id="ks_user_certificate_currency_desc" name="ks_user_certificate_currency_desc" value="<?php echo $app_users[0]->ks_user_certificate_currency_desc ; ?>">

                        <label for="ks_user_certificate_currency_desc" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>

						            <?php echo form_error('ks_user_certificate_currency_desc','<span class="text-danger">','</span>'); ?>

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label>Expiry Date</label>

                        <input type="text" class="form-control form-control-line" id="ks_user_certificate_currency_exp"  name="ks_user_certificate_currency_exp" value="<?php echo date('d-m-Y',strtotime( $app_users[0]->ks_user_certificate_currency_exp)) ; ?>">

            						<label for="ks_user_certificate_currency_exp" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>

            						<?php echo form_error('ks_user_certificate_currency_exp','<span class="text-danger">','</span>'); ?>

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label>Insurance File</label>

                        <input type="file" class="form-control form-control-line" id="image" name="image" value="">

                        <label for="app_user_last_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>

                        <?php echo form_error('app_user_last_name','<span class="text-danger">','</span>'); ?> </div>

                    </div>
                    <div class="col-md-6">

                      <div class="form-group">

                        <label>Approve Certificate</label>

                        <select  class="form-control form-control-line" id="" name="is_approved" value="">

                         <option value="0">Select Option</option>
                         <option value="1" <?php if( $app_users[0]->is_approved=='1'){echo "selected";} ?>>Active</option>
                         <option value="0" <?php if( $app_users[0]->is_approved=='0'){echo "selected";} ?>  >Inactive</option>
                        </select>  

                        <label for="app_user_last_name" class="error" style="color:#FF0000; display:none; font-size: 13px;"></label>

                        <?php echo form_error('app_user_last_name','<span class="text-danger">','</span>'); ?> </div>

                    </div>
                    <div class="col-md-6">

                      <div class="form-group">

                        <label>File Uploaded</label>

                        <img src="<?php echo  $app_users[0]->image_url;  ?>" height="100" width="100">

                     
                    </div>

                  </div>

                  

		

                  <div class="row">

                    <div class="form-group">

                      <div class="col-sm-12">

                        <button type="submit"  class="btn btn-success">Update Insurance</button>

                      </div>

                    </div>

                  </div>

                </form>

              </div>

            </div>

          </div>

        </div>

      </div>

      <!-- Column -->

    </div>

    <!-- Row -->

    <!-- ============================================================== -->

    <!-- End PAge Content -->

    <!-- ============================================================== -->

    <!-- ============================================================== -->

    <!-- Right sidebar -->

    <!-- ============================================================== -->

    <!-- .right-sidebar -->

    

    <!-- ============================================================== -->

    <!-- End Right sidebar -->

    <!-- ============================================================== -->

  </div>

</div>

<!-- Modal -->

<div id="myModal" class="modal fade" role="dialog">

  <div class="modal-dialog modal-sm">

    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

	  <h4 class="modal-title">Upload Image</h4>

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        

      </div>

      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>app_users/upload_image" enctype="multipart/form-data">

        <div class="modal-body">

          <div class="form-group">

		  <input type="hidden" name="app_user_id" value="<?php echo $app_users[0]->app_user_id ; ?>" >

            <input type="file" name="user_profile_picture_link" id="csv" required>

            </br>

          </div>

        </div>

        <div class="modal-footer">

          <button type="submit" class="btn btn-success">Submit</button>

        </div>

      </form>

    </div>

  </div>

</div>

<!--end model -- >

<!-- ============================================================== -->

<!-- End Container fluid  -->

<!-- ============================================================== -->

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>

<script>

  	function myFunction() {

    var x = document.getElementById("password");

    if (x.type === "password") {

        x.type = "text";

    } else {

        x.type = "password";

    }

}

  </script>

<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

<script>



var j = jQuery.noConflict(); 

j(function() {



<?php if($app_users[0]->is_blocked=='Y'){ ?>

		j("#block_reason_div").show();

<?php } else { ?>

		j("#block_reason_div").hide();

<?php } ?>



  j("#myFrm").validate({

	rules: {

		app_username: {

			required: true,

			remote: {

			url: "<?php echo base_url()?>app_users/username_check_edit/<?php echo $app_users[0]->app_user_id; ?>",

			type: "post"

			}

		 },

		app_user_first_name: {

			required: true,

		},

		app_user_last_name: {

			required: true,

		},

		owner_type_id: {

			required: true,

		},

		primary_mobile_number: {

			required: true,

		},

		primary_email_address: {

			required: true,

			email: true,

		},

		additional_email_address_1:{

			email: true,

		},

		additional_email_address_2:{

			email: true,

		},

		additional_email_address_3:{

			email: true,

		},

		app_password:{

			email: true,

		},

		app_password: {

			required: true,

			minlength: 6

		},

		conf_password: {

			required: true,

			minlength: 6,

			equalTo: "#app_password",

		}

		

	},



  messages: {

		app_username: {

			required: "Please provide Username",

			remote: "Username already in use!"

		},

		app_user_first_name: {

			required: "Please provide first name",

		},

		app_user_last_name: {

			required: "Please provide last name",

		},

		owner_type_id: {

			required: "Please provide owner type",

		},

		primary_mobile_number: {

			required: "Please provide mobile number",

		},

		primary_email_address: {

			required: "Please provide email address",

			

		},

		app_password: {

			required: "Please provide a password",

			minlength: "Minimum input 6 characters"

		},

		conf_password: {

			required: "Please provide confirm password",

			minlength: "Minimum input 6 characters",

			equalTo: "Should be same as password"

		},

		

	},

	submitHandler: function(form) {

		form.submit();

	}

});

});







function Block()

{

  	var block_status = j('#is_blocked').val();

	if(block_status=='Y')

	{

		j('#block_reason_div').show();

	}else{

		j('#block_reason_div').hide();

	}

 }

 

 

function changeStates(counter){

  //alert(counter);

  var country_id = $('.ks_country_id_'+counter).val();

  //alert(country_id);

   $.ajax({

            type:'post',

            url:'<?php echo base_url(); ?>app_users/getStateList',

            data:{country_id:country_id},

            success:function(data){

               $('.ks_state_id_'+counter).html(data);

            }

        });

}

function changeSuburb(counter){

  var state_id = $('.ks_state_id_'+counter).val();

  //alert(country_id);

   $.ajax({

            type:'post',

            url:'<?php echo base_url(); ?>app_users/getCityList',

            data:{state_id:state_id},

            success:function(data){

               $('.ks_suburb_id_'+counter).html(data);

            }

        });

}

function  Changepincode(counter){

  var suburb_id = $('.ks_suburb_id_'+counter).val();

   $.ajax({

            type:'post',

            url:'<?php echo base_url(); ?>app_users/getpincode',

            data:{suburb_id:suburb_id},

            success:function(data){

               $('.postcode_'+counter).val(data);

            }

        });

  

}

function defaultaddress(counter){

var count= $('#counter').val();

//alert(count);

for (var i = 1; i <= count; i++) {

  $('.default_address_'+i).val('0');

 };



$('.default_address_'+counter).val('1');

}

function addFeatureValue(placeholder,addplaceholder){

  var counter = +$("#counter").val() + 1;

  $.ajax({

    type:"post",

    url:"<?php echo base_url(); ?>app_users/addaccountaddress",

    data:{'counter':counter},

    success:function(response){

      j('#'+placeholder+'').append(response);

    $('#counter').val(counter);

    }

  

  });



}

</script>

