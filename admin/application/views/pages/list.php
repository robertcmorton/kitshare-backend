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
      <h3 class="text-themecolor m-b-0 m-t-0">CMS Pages List</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
        <li class="breadcrumb-item active">CMS Pages List</li>
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
          <h4 class="card-title">App Page</h4>
          <h6 class="card-subtitle">(
            <?php if($total_rows>1){ echo $total_rows." Pages "; }else{ echo $total_rows." Page"; } ?>
            )</h6>
          <?php echo $this->session->userdata('success'); ?>
          <div class="box-header with-border">
            <h3 class="box-title">Search by:</h3>
            <div class="panel-body">
              <div class="has-feedback">
                <form class="form-inline  example example51" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>pages">
                  <div class="form-group col-md-10 no_padding">
                    <input type="text" class="" name="page" placeholder="CMS Page" value="<?php echo $page; ?>">
                    
                    <button type="submit"><i class="fa fa-search"></i></button>
                  </div>
                  <div class="form-group col-md-2"> <a href="<?php echo base_url();?>pages/add" class="btn btn-primary pull-right add_userbtn">Add Page&nbsp;<i class="fa fa-plus" aria-hidden="true"></i></a> </div>
                </form>
                <!-- <div class="form-group col-md-4"> -->
                <!-- <div class="form-group col-md-8"> -->
                <!-- </div> -->
                <!-- </div> -->
              </div>
            </div>
            <!-- /.box-tools -->
          </div>
          <form name="bulk_action_form" action="<?php echo base_url();?>pages/select_delete" method="post" onSubmit="return delete_confirm();"/>
          <?php echo $paginator;?>
          <!-- Check all button -->
          <input type="submit" class="btn btn-danger" style="margin-top:20px;" name="bulk_delete_submit" value="Delete"/>
          <div class="table-responsive m-t-40">
            <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%" style="align:center;">
              <thead>
              <th><input type="checkbox" name="select_all" id="select_all"  /></th>
                <th>#</th>
				<th>Page Name</th>
				<th>Page Content</th>
				<th>Created Date</th>
				<th class="taC">Delete</th>
                </thead>
              <tbody>
                <?php if($total_rows>0){
				
				if($this->input->get('per_page')!='')
				  {
					$i = $this->input->get('per_page')+1;
				  }
				  else
				  {
					$i=1;
				  }

				 foreach($pages as $row){ //print_r($pages); ?>
                <tr>
                    <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->cms_page_id;?>"></td>
                    <td class="mailbox-name"><?php echo $i;?></td>
                    <td class="mailbox-subject"><?php echo ucfirst($row->page);?></td>
                    <td class="mailbox-subject"><?php $content = $row->content; echo substr(strip_tags(html_entity_decode($content)), 0, 75);?>...<?php //echo ucfirst($row->content);?></td>
                    <td class="mailbox-date"><?php echo date('M d, Y', strtotime($row->created_date)); ?></td>
                    <td class="mailbox-date"><a href="<?php echo base_url();?>pages/edit/<?php echo $row->cms_page_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="<?php echo base_url();?>pages/view/<?php echo $row->cms_page_id; ?>" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a> <a onClick="return confirm('Would You Like To Delete This Page ?');" href="<?php echo base_url();?>pages/delete_record/<?php echo $row->cms_page_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a></td>
                  </tr>
                <?php $i++; } ?>
                <?php } else {?>
                <tr>
                  <td colspan="10" align="center">No record found</td>
                </tr>
                <?php  }?>
              </tbody>
            </table>
          </div>
          </form>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- End PAge Content -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>
  <!-- ============================================================== -->
  <!-- End Page wrapper  -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/jquery.js"></script>
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

</script>
