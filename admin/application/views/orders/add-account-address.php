		<div class="form-group col-md-12">
			<h2>Buisness Address</h2>
		</div>	
			
			<div class="form-group col-md-4">
				<label>Suite or Apartment Number</label>
				<input type="text" class="form-control form-control-line" id="street_address_line1" name="street_address_line1[]" value="<?php echo set_value('street_address_line1'); ?>">
				<?php echo form_error('street_address_line1','<span class="text-danger">','</span>'); ?>
			</div>
			<div class="form-group col-md-4">
				<label>Street Address </label>
				<input type="text" class="form-control form-control-line" id="street_address_line2" name="street_address_line2[]" value="<?php echo set_value('street_address_line2'); ?>">
				<?php echo form_error('street_address_line2','<span class="text-danger">','</span>'); ?>
			</div>
			
			<div class="form-group col-md-3">
				<label>Country</label>
				<select type="text" class="form-control form-control-line ks_country_id_<?php echo $counter; ?>" id="ks_country_id" name="ks_country_id[]" onchange="changeStates(<?php echo $counter ; ?>)" value="<?php echo set_value('ks_country_id'); ?>">
					<option value="" >--Select Country--</option>
					<?php if(!empty($countries)){  
								foreach($countries AS $country){	
							
					?>
					<option value="<?php echo $country->ks_country_id; ?>"><?php echo $country->ks_country_name; ?></option>
					<?php }} ?>
				</select>
				<?php echo form_error('ks_country_id','<span class="text-danger">','</span>'); ?>
			</div>
			<div class="form-group col-md-3">
				<label>State</label>
				<select type="text" class="form-control form-control-line  ks_state_id_<?php echo $counter; ?>" id="ks_state_id" name="ks_state_id[]" onchange="changeSuburb(<?php echo $counter ; ?>)"  value="<?php echo set_value('ks_state_id'); ?>">
					<option value="" >--Select State--</option>
				
				</select>
				<?php echo form_error('ks_state_id','<span class="text-danger">','</span>'); ?>
			</div>
			<div class="form-group col-md-3">
				<label>Town/Suburb</label>
				<select type="text" class="form-control form-control-line ks_suburb_id_<?php echo $counter; ?>" id="ks_suburb_id" name="ks_suburb_id[]" onchange="Changepincode(<?php echo $counter ; ?>)"  value="<?php echo set_value('ks_suburb_id'); ?>">
					<option value="" >--Select Suburb--</option>
				
				</select>
				<?php echo form_error('ks_suburb_id','<span class="text-danger">','</span>'); ?>
			</div>
			<div class="form-group col-md-3">
				<label>Postcode </label>
				<input type="text" class="form-control form-control-line postcode_<?php echo $counter; ?>" id="postcode" name="postcode[]" value="">
				<?php echo form_error('postcode','<span class="text-danger">','</span>'); ?>
			</div>
			<div class="form-group col-md-3">
		        <label> Deafult Address </label>
		        <br>
		        <input type="radio" class=" default_address_<?php echo $counter; ?>" id="default_address" name="default_address_<?php echo $counter ;?>" onclick= "defaultaddress(<?php echo $counter; ?>)" value="0">
		        <?php echo form_error('postcode','<span class="text-danger">','</span>'); ?>
	       </div>
	