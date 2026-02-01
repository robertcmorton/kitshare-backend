<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
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
    <h3 class="text-themecolor m-b-0 m-t-0">Gear Features List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Gear Features List</li>
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
        <h4 class="card-title">Gear Features List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." Features"; }else{ echo $total_rows." Feature"; }?>
          )</h6>
		  <?php echo $this->session->userdata('success'); ?>
        <div class="box-header with-border">
          <h3 class="box-title">Search by:</h3>
          <div class="panel-body">
            <div class="has-feedback">
              <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>gear_features">
                <div class="form-group col-md-10 no_padding">
                  <input type="text" class="" name="gear_name" placeholder="Gear Name" value="<?php $gear_name; ?>" />
                  <input type="text" class="" name="feature_name" placeholder="Feature Name" value="<?php $feature_name; ?>" />
                  <button type="submit"><i class="fa fa-search"></i></button>
				  <div class="form-group col-md-1"> <a href="<?php echo base_url();?>gear_features/add" class="btn btn-primary pull-right add_userbtn">Add Gear Feature &nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a> </div>
                </div>
                
              </form>
              <!-- </div> -->
              <!-- </div> -->
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>gear_features/select_delete" method="post" onSubmit="return delete_confirm();"/>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
            <div class="pull-right"> <?php echo $paginator;?>
              <!-- /.btn-group -->
            </div>
            <!-- /.pull-right -->
          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="150%" style="align:center;table-hlayout:fixed;">
              <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" id="select_all"  /></th>
                  <th>#</th>
                  <th>Feature Name</th>
                  <th>Gear Name</th>
                  <th>Display Sequence ID</th>
                  <th>Status</th>
                  <th>Date Created</th>
                  <th>Updater</th>
                  <th>Date Updated</th>
                  <th class="taC">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php if($result->num_rows()>0){
				if($this->input->get('per_page')!='')
				  {
					$i = $this->input->get('per_page')+1;
				  }
				  else
				  {
					$i=1;
				  }
				foreach($result->result() as $row){ ?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->user_gear_desc_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->feature_name;?></td>
                  <td class="mailbox-subject"><?php if($gear_desc[0]->user_gear_desc_id==$row->user_gear_desc_id) echo $gear_desc[0]->gear_name ?></td>
                  <td class="mailbox-subject"><?php echo $row->feature_display_seq_id;?></td>
                  <td class="mailbox-subject"><?php if($row->is_active=='Y'){ echo "Active"; } else { echo "Inactive"; } ?></td>
                  <td class="mailbox-date"><?php echo $row->create_date; ?></td>
                  <td class="mailbox-subject"><?php if($row->update_user==''){ echo "-"; } else { $sql3="SELECT app_user_name FROM m_app_user WHERE m_app_user_id=".$row->update_user;$result3=$this->db->query($sql3);foreach($result3->result() as $row3){echo $row3->app_user_name; } } ?></td>
                  <td class="mailbox-date"><?php if($row->update_date==''){ echo "-"; } else { echo $row->update_date; } ?></td>
                  <td class="mailbox-date"><a href="<?php echo base_url();?>gear_features/edit/<?php echo $row->user_gear_feature_id; ?>/<?php echo $row->user_gear_desc_id;?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onClick="return confirm('Would You Like To Delete This?');" href="<?php echo base_url();?>gear_features/delete_record/<?php echo $row->user_gear_feature_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a> </td>
                </tr>
                <?php $i++; }   ?>
                <?php } else {?>
                <tr>
                  <td colspan="14" align="center">No record found</td>
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
            <div class="pull-right"> <?php echo $paginator;?> </div>
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
  </section>
  <!-- /.content -->
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
