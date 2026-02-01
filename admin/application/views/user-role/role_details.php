 <style>
.new {
	background-color: #03161d1a;
	width: 243px;
	padding: 25px;
}
</style>
<div class="new"><h4><b>Plivilege for Role:</b></h4>
<ul>
<?php
$pri_type_id = $this->common_model->GetAllWhere(APP_ROLE_PRIV,array("app_role_id"=>$role_id));
if(!empty($pri_type_id)){
	foreach($pri_type_id->result() as $key=>$val)
	{
		$app_priv_id = $val->app_priv_id;
		$pri_type = $this->common_model->GetAllWhere(M_APP_PRIVILEGE,array("app_priv_id"=>$app_priv_id));
		$str='';
		foreach($pri_type->result() as $k=>$v)
		{
			$str=ucfirst($v->privilege_type); ?>
			
			<li><i><?php echo $str; ?></i></li>
			
		<?php } 
	} }
	else {
			echo "No privilege added";
	} ?>
</ul>
</div>


