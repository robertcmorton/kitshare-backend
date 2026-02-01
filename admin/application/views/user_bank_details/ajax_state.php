	<?php 
		if($count>0){ ?>
		<option value="">--Select State--</option>
		<?php foreach($state as $s){?>
		<option value="<?php echo $s->ks_state_id;?>"><?php echo $s->ks_state_name; ?></option>
	<?php }}
		else{?><option>This country does not contain a listed state.</option>
		<script>findsuburb("");</script><?php } ?>