
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
    <h3 class="text-themecolor m-b-0 m-t-0">App User Rent Details</h3>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>app_users/rent/<?php echo $this->uri->segment(3);  ?>/owner">App User Rent Details</a></li>
      <li class="breadcrumb-item active">View App User Chat  Details</li>
    </ol>
  </div>
</div>
<?php 
// echo "<pre>";print_r($response);//die;
?>
<div class="row">
<div class="col-12">
  <div class="card">
    <div class="card-body">
      
         <div class="row">
                    <div class="col-12">
                        <div class="card m-b-0">
                            <!-- .chat-row -->
                            <div class="chat-main-box">
                                <!-- .chat-left-panel -->
                                
                                <!-- .chat-left-panel -->
                                <!-- .chat-right-panel -->
                                <div class="chat">
                                    <div class="chat-main-header">
                                        <div class="p-20 b-b">
                                            <h3 class="box-title">Chat Message</h3>
                                            <?php if (!empty($response['message'])) {  echo 'Chat  History between ' .  $response['chatDetais']['sender_details'][0]->app_user_first_name . ' '. $response['chatDetais']['sender_details'][0]->app_user_last_name.' And ' .$response['chatDetais']['receiver_detail'][0]->app_user_first_name .' '. $response['chatDetais']['receiver_detail'][0]->app_user_last_name  ; }?>
                                             

                                        </div>
                                    </div>
                                    <div class="chat-rbox">
                                        <ul class="chat-list p-20">
                                            <?php if (!empty($response['message'])) {
                                              $i = 0;
                                              foreach ($response['message'] as $value) {
                                                  if ($i == 0 ) {
                                                    $sender_id=  $value->sender_id;
                                                  }
                                                ?>
                                                    <li class="<?php   if($value->sender_id == $this->uri->segment(3)){echo  "reverse"; }else{ }  ?>" >
                                                         <?php if($value->sender_id == $this->uri->segment(3)){ ?>
                                                         <div class="chat-time"><?php echo  date('d-m',strtotime($value->create_date)).' '. $value->created_time;?></div>
                                                         <?php  }else{ ?>
                                                             <div class="chat-img"><img src="<?php echo $value->sender_details['user_profile_picture_link'];?>" alt="user" /></div>         
                                                         <?php } ?> 
                                                       
                                                        <div class="chat-content">
                                                            <h5><?php echo $value->sender_details['app_username'];?></h5>
                                                            <div class="box bg-light"><?php echo $value->message;?></div>
                                                        </div>
                                                        <?php if($value->sender_id== $this->uri->segment(3)){ ?>
                                                          <div class="chat-img"><img src="<?php echo $value->sender_details['user_profile_picture_link'];?>" alt="user" /></div>         
                                                         
                                                        <?php  }else{ ?>
                                                          <div class="chat-time"><?php echo  date('d-m',strtotime($value->create_date)) .       ' ' . $value->created_time;?></div>    
                                                         <?php } ?>   
                                                    </li> 
                                             <?php  $i++;}
                                            }else{?>

                                              <P>No Chat Found</p>
                                            <?php } ?>
                                           
                                            <!--chat Row -->
                                          
                                            <!--chat Row -->
                                           
                                            
                                            <!--chat Row -->
                                            
                                            <!--chat Row -->
                                        </ul>
                                    </div>
                                  
                                </div>
                                <!-- .chat-right-panel -->
                            </div>
                            <!-- /.chat-row -->
                        </div>
                    </div>
                </div>
	  
		
    </div>
  </div>
  <!-- ============================================================== -->
  <!-- End Page Content -->
  <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
