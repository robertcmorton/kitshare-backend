<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
<link href="css/colors/blue.css" id="theme" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/dropify/dist/css/dropify.min.css">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="fix-header card-no-border">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
  <svg class="circular" viewBox="25 25 50 50">
    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
  </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">
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
    <h3 class="text-themecolor m-b-0 m-t-0">Gear Image List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_desc">Gear Description List</a></li>
      <li class="breadcrumb-item active">Gear Image List</li>
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
<div class="col-md-12">
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Gear Images</h4>
	  <?php echo $this->session->userdata('success'); ?>
      <div class="box-header with-border">
        <div class="panel-body">
          <div class="has-feedback">
            <?php $error=' '; ?>
            <form name="bulk_action_form" action="<?php echo base_url();?>gear_desc/select_delete_image" method="post" onSubmit="return delete_confirm();"/>
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <input type="hidden" name="user_gear_desc_id" value="<?php echo $result[0]->user_gear_desc_id; ?>"  />
                <div class="row">
                  <div class="form-group col-md-2">
                    <input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete"/>
                  </div>
                  <div class="form-group col-md-8"> <a href="#" class="btn btn-primary pull-right add_userbtn" data-toggle="modal" data-target="#myModal">New Image Upload&nbsp;<i class="fa fa-upload" aria-hidden="true"></i></a></div>
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive m-t-40">
                <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="150%" style="align:center;table-hlayout:fixed;">
                  <thead>
                    <tr>
                      <th><input type="checkbox" onClick="toggle(this);" id="select_all" name="select_all"/></th>
                      <th>#</th>
                      <th>Image</th>
                      <th>Sequence ID</th>
                      <th>Status</th>
                      <!--<th>Creator</th>-->
                      <th>Date Created</th>
                      <th class="TaC">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php   $sqlim="SELECT * FROM ks_user_gear_images WHERE user_gear_desc_id=".$result[0]->user_gear_desc_id." ORDER BY user_gear_image_id desc"; 
							$resultim=$this->db->query($sqlim);
							if($resultim->num_rows()>0){
							$i=1;
						    foreach($resultim->result() as $rowim){ ?>
                    <tr>
                      <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $rowim->user_gear_image_id;?>"></td>
                      <td class="mailbox-name"><?php echo $i;?></td>
                      <td class="mailbox-subject"><img class="img-thumbnail" height="100px" width="100px" src="<?php echo base_url().GEAR_IMAGE."/".$rowim->gear_display_image;?>" /></td>
                      <td class="mailbox-subject"><?php if($rowim->gear_display_seq_id==''){ echo "-"; } else { echo $rowim->gear_display_seq_id; }?></td>
                      <td class="mailbox-subject"><?php if($rowim->is_active=='Y'){ echo "Active"; } else { echo "Inactive"; } ?></td>
                      <!--<td class="mailbox-subject"><?php //$sql2="SELECT app_user_name FROM m_app_user WHERE m_app_user_id=".$rowim->create_user;$result2=$this->db->query($sql2);foreach($result2->result() as $row2){echo $row2->app_user_name; } ?></td>-->
                      <td class="mailbox-date"><?php echo date('M d, Y', strtotime($rowim->create_date)) ;  ?></td>
                      
                      <td class="mailbox-date"><a onClick="return confirm('Would You Like To Delete This?');" href="<?php echo base_url();?>gear_desc/delete_record_image/?id=<?php echo $rowim->user_gear_image_id; ?>&id1=<?php echo $result[0]->user_gear_desc_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a> </td>
                    </tr>
                    <?php $i++; }   ?>
                    <?php } else {?>
                    <tr>
                      <td colspan="14" align="center">No image uploaded yet</td>
                    </tr>
                    <?php  }?>
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            </form>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <!-- /.btn-group -->
                <!-- /.btn-group -->
              </div>
              <!-- /.pull-right -->
            </div>
          </div>
        </div>
        <!-- /. box -->
      </div>
      <!-- /.col -->
      <!-- /.row -->
      <!-- /.col -->
      <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload New Image</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo $error;?> <?php echo form_open_multipart('gear_desc/do_upload/');?>
      <form class="form-inline" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <input type="file" name="userfile" size="20"  accept='image/*' required/>
            <input type="hidden" name="user_gear_desc_id" value="<?php echo $result[0]->user_gear_desc_id; ?>"  />
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="Upload" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo base_url();?>assist/js/jquery.min.js"></script>
<script type="text/javascript">

function delete_confirm(){

	var result = confirm("Are you sure to delete ?");

	if(result){

		return true;

	}else{

		return false;

	}

}



$(document).ready(function(){

    $('#select_all').on('click',function(){

        if(this.checked){

            $('.checkbox').each(function(){

                this.checked = true;

            });

        }else{

             $('.checkbox').each(function(){

                this.checked = false;

            });

        }

    });

	

	$('.checkbox').on('click',function(){

		if($('.checkbox:checked').length == $('.checkbox').length){

			$('#select_all').prop('checked',true);

		}else{

			$('#select_all').prop('checked',false);

		}

	});

});
$('#select-all').click(function(event) {
        var $that = $(this);
        $(':checkbox').each(function() {
            this.checked = $that.is(':checked');
        });
    });

</script>
<script src="<?php echo base_url();?>assets/plugins/dropify/dist/js/dropify.min.js"></script>
<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
    </script>
