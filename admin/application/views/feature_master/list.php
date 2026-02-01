<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body class="fix-header card-no-border">
<div class="preloader">
  <svg class="circular" viewBox="25 25 50 50">
    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
  </svg>
</div>
<div id="main-wrapper">
<div class="page-wrapper">
<div class="container-fluid">
<div class="row page-titles">
  <div class="col-md-5 col-8 align-self-center">
    <h3 class="text-themecolor m-b-0 m-t-0">Features List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Features List</li>
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
        <h4 class="card-title">Features List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." Features"; }else{ echo $total_rows." Feature"; }?>
          )</h6>
		  <?php echo $this->session->userdata('success'); ?>
        <div class="box-header with-border">
          <h3 class="box-title">Search by:</h3>
          <div class="panel-body">
            <div class="has-feedback">
              <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>feature_master">
                <div class="form-group col-md-4 no_padding">
                  <input type="text" style="width:250px !important;" name="feature_name" placeholder="Feature Name" value="<?php echo $feature_name; ?>" >
                  <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                <div class="form-group col-md-2"> 
				<a href="<?php //echo base_url();?>feature_master/add" class="btn btn-primary pull-right add_userbtn">Add Feature &nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a> </div>
				<div class="pull-right col-md-6">
				<a href="#" class="btn btn-primary add_userbtn" data-toggle="modal" data-target="#myModal">Upload Data&nbsp;<i class="fa fa-upload" aria-hidden="true"></i> </a>
				<!--<a href="#" class="btn btn-primary add_userbtn" data-toggle="modal" data-target="#myModalModify">Modify Data&nbsp;<i class="fa fa-upload" aria-hidden="true"></i> </a>-->
				<a href="<?php echo base_url();?>feature_master/downloadallcsv" class="btn btn-primary add_userbtn">Download&nbsp;<i class="fa fa-download" aria-hidden="true"></i> </a>
			</div>
              </form>
              <!-- </div> -->
              <!-- </div> -->
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>feature_master/select_delete" method="post" onSubmit="return delete_confirm();"/>
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
			<div class="row pull-right">
				<div class="col-md-12">
					<strong>Per page view: </strong>
		   
					<select name="limit" id="limit">
						<option value="25" <?php if($limit==25) echo "selected=selected" ; ?>>25</option>
						<option value="50" <?php if($limit==50) echo "selected=selected" ; ?>>50</option>
						<option value="100" <?php if($limit==100) echo "selected=selected" ; ?>>100</option>
					</select>
				  </div>
				</div>
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
                  <th>Gear Category</th>
                  <th>Feature Details</th>
                  <th>Status</th>
				  <th>Creator</th>
                  <th>Create Date</th>
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
				
				
				foreach($result->result() as $row){ 
				
					//Feature lists are fetched
					$query_features = $this->model->get_feature_list($row->feature_master_id);
					$res_features = $query_features->result_array();
					$str = "";
					if(count($res_features)>0){
						
						foreach($res_features as $row_features){
							
							$str.=$row_features['feature_values'].",";
						}
						
						if(strstr($str,","))
							$str = substr($str,0,-1);
						
					}else
						$str = "No features found.";
					
				
				?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->feature_master_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->feature_name;?></td>
                  <td class="mailbox-subject"><?php foreach($gear_category as $u){if($u->gear_category_id==$row->gear_category_id) echo $u->gear_category_name; }?></td>
                  <td class="mailbox-subject"><?php echo $str;?></td>
                  <td class="mailbox-subject"><?php echo $row->is_active;?></td>
				  <td class="mailbox-subject"><?php echo $row->app_user_name;  ?></td>
                  <td class="mailbox-date"><?php echo $row->create_date; ?></td>                  
                  <td class="mailbox-date"><a href="<?php echo base_url();?>feature_master/edit/<?php echo $row->feature_master_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a onClick="return confirm('Would You Like To Delete This?');" href="<?php echo base_url();?>feature_master/delete_record/<?php echo $row->feature_master_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a> </td>
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
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload CSV to Add Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>feature_master/import" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <input type="file" name="file" id="csv">
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
<div id="myModalModify" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload CSV to Modify Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>feature_master/import_to_modify" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <input type="file" name="file" id="csv">
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
<script type="text/javascript" src="<?php echo base_url();?>assist/js/jquery.min.js"></script>
<script type="text/javascript">

function delete_confirm(){

	var result = confirm("Are you sure to delete?");

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
$(function() {
    $("#limit").change(function() {
        var limit = $('option:selected', this).val();
		window.location.href = "<?php echo base_url() ?>feature_master/?limit="+limit;
		
		
    });
});
</script>
