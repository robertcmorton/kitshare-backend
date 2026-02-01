<style>
#manufacturer {
  border: none;
  border-radius: 0px;
  padding-left: 0px;
  border-bottom: 1px solid #d9d9d9; }
#status {
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
    <h3 class="text-themecolor m-b-0 m-t-0">Upload  Model Excel</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>models">Model List</a></li>
      <li class="breadcrumb-item active">Upload  Model Excel</li>
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
      <h4 class="card-title">Upload  Model Excel</h4>
      <form class="form-inline" data-toggle="validator" role="form" method="post" action="<?php echo base_url();?>Upload_models/save" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <input type="file" name="userfile" id="csv" required accept="">
            </br>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="importfile" class="btn btn-success">Submit</button>
        </div>
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

<script>
$('#gear_category_id').on('change',function (){
	
	var gear_category_id = $('#gear_category_id').val();
	$.ajax({
            type:"post",
            url:"<?php echo base_url(); ?>models/getsubcategory",
            data:{gear_category_id:gear_category_id,},
            success:function(response){
                //alert(y);
				$('#gear_sub_category_id').html(response);
            }
        });
	
})
</script>
