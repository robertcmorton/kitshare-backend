<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from wrappixel.com/demos/admin-templates/material-pro/material/table-data-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 21 Dec 2017 12:51:16 GMT -->
<head>
<!-- You can change the theme colors from here -->
<link href="css/colors/blue.css" id="theme" rel="stylesheet">
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
    <h3 class="text-themecolor m-b-0 m-t-0">Model List</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>models" >Model List</a></li>
      <li class="breadcrumb-item active">Model List</li>
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
        <h4 class="card-title">Error List Data</h4>
       
        <?php  echo $this->session->userdata('success');?>
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Search by:</h3> -->
          <div class="panel-body">
            <div class="has-feedback">
              
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
       
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <div class="pull-right">
              <div class="col-md-12">
		 
		  </div>
		
            </div>
            <!-- /.pull-right -->
          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
              <thead>
                <tr>
                  
                  <th>#</th>
				  <th>Models</th>
				  <th>Gear Category Name</th>
                  <th>Gear Sub Category Name</th>
                  <th>Manufacturer</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($error_data)){
                 
                  $i=1 ;
                  foreach ($error_data as  $value) {
                  
                ?>
                  <tr>
                    <td> <?php echo $i; ?></td>
                    <td><?php echo  $value['model_name'] ;  ?></td>
                    <td><?php echo  $value['gear_category_id'] ;  ?></td>
                    <td><?php echo  $value['gear_sub_category_id'] ;  ?></td>
                    <td><?php echo  $value['manufacturer_id'] ;  ?></td>
                  </tr>    
                <?php  $i++; }
                } ?>
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
            <!-- <div class="pull-right"> <?php echo $paginator;?> </div> -->
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


<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Duplicate Data List</h4>
       
        <div class="box-header with-border">
          <!-- <h3 class="box-title">Search by:</h3> -->
          <div class="panel-body">
            <div class="has-feedback">
              
            </div>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
       
        <div class="box-body no-padding">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <div class="pull-right">
              <div class="col-md-12">
     
      </div>
    
            </div>
            <!-- /.pull-right -->
          </div>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
              <thead>
                <tr>
                  
                  <th>#</th>
                  <th>Models</th>
                  <th>Gear Category Name</th>
                  <th>Gear Sub Category Name</th>
                  <th>Manufacturer</th>
                  
                </tr>
              </thead>
              <tbody>
                <?php if(!empty($repeated)){
                 
                  $i=1 ;
                  foreach ($repeated as  $value) {
                  
                ?>
                  <tr>
                    <td> <?php echo $i; ?></td>
                    <td><?php echo  $value['model_name'] ;  ?></td>
                    <td><?php echo  $value['gear_category_id'] ;  ?></td>
                    <td><?php echo  $value['gear_sub_category_id'] ;  ?></td>
                    <td><?php echo  $value['manufacturer_id'] ;  ?></td>
                  </tr>    
                <?php  $i++; }
                } ?>
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
            <!-- <div class="pull-right"> <?php echo $paginator;?> </div> -->
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
<!--model-->

<!--end model-->


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
$(function() {
    $("#limit").change(function() {
        var limit = $('option:selected', this).val();
		window.location.href = "<?php echo base_url() ?>models/?limit="+limit;
		
		
    });
});
</script>
