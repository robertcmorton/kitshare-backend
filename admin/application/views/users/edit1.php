<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Update User </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>home"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url();?>users">Users</a></li>
      <li class="active">Update</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-8">
        <!-- /.box -->
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Update </h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body pad">
            <form action="<?php echo base_url();?>users/update_users" method="post" id="myFrm">
              <?php foreach($users as $row) { ?>
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="hidden" name="id" value="<?php echo $this->uri->segment(3);?>" />
                    <input type="text" placeholder="Username" name="username" id="username"  class="form-control" value="<?php echo $row->username; ?>">
                  </div>
                </div>
                <div class="clr"></div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mbr">
                    <label for="exampleInputEmail1">Password</label>
                    <?php $password= $this->common_model->base64De(2,$row->password) ?>
                    <input type="password" placeholder="Password" name="password" id="password"   class="form-control" value="<?php echo $password; ?>">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Confirm Password</label>
                    <input type="password" placeholder="Confirm Password" name="cnfpwd" id="cnfpwd"  class="form-control" value="<?php echo $password; ?>">
                  </div>
                </div>
                <div class="clr"></div>
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Add Privilege:</label>
                <br />
                <div class="col-sm-3">
                  <div class="form-group">
                    <label >Add</label>
                    <br />
                    <input type="radio" name="pre_add" id="optionsRadios4" value="Y" <?php if ($row->pre_add == 'Y') echo "checked='checked'"; ?>>
                    Yes
                    <input type="radio" name="pre_add" id="optionsRadios5" value="N" <?php if ($row->pre_add == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Edit</label>
                    <br />
                    <input type="radio" name="pre_edit" id="optionsRadios4" value="Y" <?php if ($row->pre_edit == 'Y') echo "checked='checked'"; ?>>
                    Yes
                    <input type="radio" name="pre_edit" id="optionsRadios5" value="N" <?php if ($row->pre_edit == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">View</label>
                    <br />
                    <input type="radio" name="pre_view" id="optionsRadios4" value="Y" <?php if ($row->pre_view == 'Y') echo "checked='checked'"; ?> >
                    Yes
                    <input type="radio" name="pre_view" id="optionsRadios5" value="N" <?php if ($row->pre_view == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Delete</label>
                    <br />
                    <input type="radio" name="pre_delete" id="optionsRadios4" value="Y" <?php if ($row->pre_delete == 'Y') echo "checked='checked'"; ?>>
                    Yes
                    <input type="radio" name="pre_delete" id="optionsRadios5" value="N" <?php if ($row->pre_delete == 'N') echo "checked='checked'"; ?>>
                    No </div>
                </div>
              </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Edit</button>
              </div>
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
      <!-- /.col-->
    </div>
    <!-- ./row -->
  </section>
  <!-- /.content -->
</div>
