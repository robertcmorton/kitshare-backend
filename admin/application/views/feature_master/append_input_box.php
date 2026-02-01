<div class="form-group col-md-4" id="appenddivFeatureValue<?php echo $counter+1;?>">
		<label for="exampleInputImage">&nbsp;</label>
		<input type="text" class="form-control" name="feature_value[]" id="feature_value<?php echo $counter+1;?>" value="" placeholder="Feature Value">&nbsp;
<!--<a href="javascript:void(0);" onClick="addFeatureValue('divFeatureValue','<?php //echo $counter+1;?>','addFeatureVal<?php //echo $counter+1;?>','removeFeatureVal<?php //echo $counter+1;?>')" id="addFeatureVal<?php //echo $counter+1;?>">Add</a>-->
<a href="javascript:void(0);" onClick="removeFeatureValue('appenddivFeatureValue<?php echo $counter+1;?>')">Remove</a>
</div>