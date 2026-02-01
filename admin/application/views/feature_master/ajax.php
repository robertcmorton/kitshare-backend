<?php	
	print_r($data);
	if($cnt>0){
  ?>
    <div id="category_<?php echo $y+1; ?>" class="form-group col-md-6"><select name="gear_category_id_<?php echo $y+1; ?>" id="gear_category_id" class="form-control" onChange="findnext(this.value,<?php echo $y+1; ?>,<?php echo $cnt2;?>)">
			<option value="">--Select Category--</option>
			<?php foreach($gear_categories as $v){?>
			<option value="<?php echo $v->gear_category_id; ?>"><?php echo $v->gear_category_name; ?></option>
	<?php }} ?>
		</select>
	</div>