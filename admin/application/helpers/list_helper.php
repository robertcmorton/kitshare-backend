<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('list1'))
{
    function list1($id,$i,$fl){
		if($id!=NULL){
			$ci =& get_instance();
			$ci->load->database();
			$sql = "SELECT * FROM ks_gear_categories WHERE is_active='Y' AND gear_category_id=".$id;
			$q = $ci->db->query($sql);
			if($q->num_rows() > 0)
			{
				foreach($q->result() as $v) {	  
					list1($v->gear_sub_category_id,$i+1,$fl);
					if($i==0){?> 
					<li class="breadcrumb-item active"><?php echo $v->gear_category_name;?></li><?php
					}
					else
					{?> 
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_categories/index/<?php echo $id; ?>"><?php echo $v->gear_category_name;?></a></li><?php
					}
				}
			}
		}
		else {
			if($fl!=0){
		?><li class="breadcrumb-item"><a href="<?php echo base_url(); ?>gear_categories">Gear Categories Details List</a></li>
		<?php }
			else{
		?><li class="breadcrumb-item active">Gear Categories Details List</li>
		<?php }
		}
	}
}
?>