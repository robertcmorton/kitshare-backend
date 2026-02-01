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
    <h3 class="text-themecolor m-b-0 m-t-0">InsuranceType List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item active">Insurance Type List</li>
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
        <h4 class="card-title">Insurance Type List</h4>
        <h6 class="card-subtitle">(
          <?php if($total_rows>1){ echo $total_rows." Insurance"; }else{ echo $total_rows." Insurance"; }?>
          )</h6>
        <div class="box-header with-border">
          <!--  <h3 class="box-title"><a href="<?php echo base_url();?>Insurance/InsuranceSummmary" class="btn btn-info" style="color:white ;float:right" ></a></h3> -->
          <div class="panel-body">
              <?php if($this->session->flashdata('success')!=''){ ?>

                                <?php echo $this->session->flashdata('success');?>

                            <?php } ?>
            <div class="has-feedback">
              <form class="form-inline example example53" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>Insurance/InsuranceSummmary">
                <div class="form-group col-md-12 no_padding">
                  <input type="text" autocomplete="off" class="game_date" id="game_date" name="start_date" placeholder="Start Date" value="">
                  <input type="text" autocomplete="off" class="game_date" name="end_date" placeholder="End Date" value="<?php // echo $gear_name; ?>" >
                  <button type="submit" style="width: 26%;">Download Insurance Summary</button>
                </div>
              </form>
              <!-- </div> -->
              <!-- </div> -->
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <form name="bulk_action_form" action="<?php echo base_url();?>Insurance/select_delete" method="post" onSubmit="return delete_confirm();"/>
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
                  <th class="font_n"><input type="checkbox" name="select_all" id="select_all"  /></th>
                  <th class="font_n">#</th>
                  <th class="font_n">Insurance Name</th>
                  <!-- <th class="font_n">Percentage</th> -->
                  <th class="font_n">Status</th>
                  <th class="font_n">created Date</th>
                  <th class="font_n">Updated Date</th>
                  
                  <th class="font_n taC">Action</th>
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
				//	print_r($row);die;
					?>
                <tr>
                  <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->ks_insurance_category_type_id;?>"></td>
                  <td class="mailbox-name"><?php echo $i;?></td>
                  <td class="mailbox-subject"><?php echo $row->name;?></td>
                  <!-- <td class="mailbox-subject"><?php echo $row->percent;?></td> -->
                  <td class="mailbox-subject"><?php echo $row->status;?></td>
                  <td class="mailbox-subject"><?php echo date('M-d-Y' ,strtotime($row->created_date)); ?></td>
                  <td class="mailbox-subject"><?php if ($row->updated_date == '0000-00-00') {
                    echo "Not Available";
                  }else{ echo date('M-d-Y' ,strtotime($row->updated_date));} ?></td>
                  <td class="mailbox-date">
            				  <a href="<?php echo base_url();?>Insurance/view/<?php echo $row->ks_insurance_category_type_id; ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>
            				  <a href="<?php echo base_url();?>Insurance/edit/<?php echo $row->ks_insurance_category_type_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> 
            				  
            				  <a onClick="return confirm('Would You Like To Delete This?');" href="<?php echo base_url();?>Insurance/delete_record/<?php echo $row->ks_insurance_category_type_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a> 
                  </td>
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
<script src="<?php echo base_url();?>assets/js/bootstrap-datepicker.js"></script>
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
$('#ks_category_id').change( function (){
	var categories = $('#ks_category_id').val();
	
	 $.ajax({
            type:"get",
            url:"<?php echo base_url(); ?>gear_desc/getsubcat/"+categories,
            
            success:function(response){
                
			$("#sub_cat").html(response);
            }
        });
});	
var j = jQuery.noConflict(); 
j(function() {
  j('.game_date').datepicker({
autoclose: true
});
});
</script>
