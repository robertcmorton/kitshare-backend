<?php	
	if($cnt>0){
	//	print_r($result);
  ?>
    <div id="category_<?php echo $y+1; ?>"><br><select name="gear_category_id_<?php echo $y+1; ?>" id="gear_category_id_2" class="form-control" onChange="findnext(this.value,<?php echo $y+1; ?>,<?php echo $cnt2;?>)">
			<option value="">--Select Category--</option>
			<?php foreach($gear_categories as $v){?>
			<option value="<?php echo $v->gear_category_id; ?>"  <?php if(!empty($result)){  if($result->gear_category_id ==  $v->gear_category_id ){  echo "selected";} } ?>  ><?php echo $v->gear_category_name; ?></option>
	<?php }} ?>
		</select>
	</div>