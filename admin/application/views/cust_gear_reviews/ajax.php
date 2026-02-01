<ul style="list-style:none;">
	<?php foreach($rev as $s){ ?>
		<li>
			<div class="row"><span style="font-weight:bold;"><font><?php $sql1="SELECT app_username FROM gs_users WHERE app_user_id=".$s->app_user_id;$result1=$this->db->query($sql1);foreach($result1->result() as $row1){echo $row1->app_username; }?></font><b><?php echo "  ".date('d M, Y', strtotime($s->cust_gear_review_date)); ?></b></span></div>
			<div><?php echo $s->cust_gear_review_desc; ?><div>
			<script>findnext(<?php echo $s->gs_cust_gear_review_id; ?>);</script>
		</li>
	<?php }	?>
</ul>