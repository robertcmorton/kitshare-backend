<?php if($count>0){?>
	<option value="">--Select Suburb--</option>
	<?php foreach($suburb as $su){?>
	<option value="<?php echo $su->ks_suburb_id;?>"><?php echo $su->suburb_name; ?></option>
<?php }}
	else{?><option value="">This state does not contain a listed suburb.</option><?php } ?>