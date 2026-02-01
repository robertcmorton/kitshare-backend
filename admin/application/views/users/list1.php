<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users' List
        <small><?php echo $total_rows;?> User(s)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">User</li>
      </ol>
    </section>
	<?php if($this->session->flashdata('success')!=''){ ?>
		<?php echo $this->session->flashdata('success');?>
		<?php } ?>
    <!-- Main content -->
    <section class="content">
      <div class="row">
       
        <!-- /.col -->
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Search by:</h3>

              <div class="panel-body">
                <div class="has-feedback">
				  <form class="form-inline" data-toggle="validator" role="form" method="get" action="<?php echo base_url();?>users">
				  <div class="row">
				     <div class="col-md-12">
						<div class="col-md-8">
							  <div class="form-group col-md-4">
								<input type="text" class="form-control input-sm" name="username" placeholder="Username" value="<?php echo $username; ?>">
							  </div>
							  <div class="form-group col-md-4">
								<button type="submit" class="btn btn-success"> <i class="fa fa-search"></i> Search </button>
							  </div>
							  
						</div>
						<div class="col-md-4">
								<a href="<?php echo base_url();?>users/add" class="btn btn-primary">Add User  <i class="fa fa-plus" aria-hidden="true"></i></a>
						 </div>
					  </div>
                </div>
				</form>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
			</div>
			</div>
			<form name="bulk_action_form" action="<?php echo base_url();?>users/select_delete" method="post" onSubmit="return delete_confirm();"/>
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
               			<input type="submit" class="btn btn-danger" name="bulk_delete_submit" value="Delete"/>

                                <div class="pull-right">
                <?php echo $paginator;?>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
				  <thead>
                  <tr>
				  	<th><input type="checkbox" name="select_all" id="select_all"  /></th>
					<th>SI No.</th>
					<th>Username</th>
					<th>Added By</th>
					<th>Add</th>
					<th>Edit</th>
					<th>View</th>
					<th>Delete</th>
					<th>Created Date</th>
                    <th class="taC">Action</th>
                  </tr>
                </thead>
                  <tbody>
				  <?php if($users->num_rows()>0){$i=1;
				  foreach($users->result() as $row){ ?>
                  <tr>
                    <td><input type="checkbox" name="checked_id[]" class="checkbox" id="checked_id" value="<?php echo $row->app_user_id;?>"></td>
                    <td class="mailbox-name"><?php echo $i;?></td>
					<td class="mailbox-subject"><?php echo $row->app_user_name;?></td>
					<td>
					<?php
					if($row->level >0){
					$record=$this->db->query('select app_user_name from m_app_user where app_user_id='.$row->level.'');
			        echo $name=$record->row()->app_user_name;	
					} else { echo 'Super Admin';}
					?>
					</td>
					<td class="mailbox-subject"><?php echo $row->pre_add;?></td>
					<td class="mailbox-subject"><?php echo $row->pre_edit;?></td>
					<td class="mailbox-subject"><?php echo $row->pre_view;?></td>
                    <td class="mailbox-attachment"><?php echo $row->pre_delete;?></td>
                    <td class="mailbox-date"><?php echo date('M d, Y', strtotime($row->created_date)); ?></td>
					<td class="mailbox-date">
					<a href="<?php echo base_url();?>users/edit/<?php echo $row->app_user_id; ?>" title="Modify"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
					<a onClick="return confirm('Would You Like To Delete This Page ?');" href="<?php echo base_url();?>users/delete_record/<?php echo $row->app_user_id; ?>" title="Delete"><i class="fa fa-trash-o"></i></a></td>
                  </tr>
				  <?php $i++; } ?>
				  <?php } else {?>
				  <tr><td colspan="10" align="center">No record found</td> </tr>
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
                <div class="pull-right">
                <?php echo $paginator;?>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->

    <!-- /.content -->
  </div>
  </div>
  </section>
  
  <script type="text/javascript" src="<?php echo base_url();?>dist/js/jquery.min.js"></script>
<script type="text/javascript">
function delete_confirm(){
	var result = confirm("Are you sure to delete users?");
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