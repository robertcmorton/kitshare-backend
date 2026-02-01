<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Upload_models extends CI_Controller {
 
    public function __construct() {
        parent::__construct();
		$this->load->helper(array('url','form','html','text'));
		$this->load->library(array('session','form_validation','pagination','email','upload'));
		$this->load->model(array('common_model','mail_model','model'));
        $this->load->model('Import_model', 'import');
		
    }
 
    // upload xlsx|xls file
    public function index() {
        $data['page'] = 'import';
        $data['title'] = 'Import XLSX | Kitshare';
        $this->load->view('import/index', $data);
    }
    // import excel data
    public function save() {
	
	   
        $this->load->library('excel');
		
		//GST% and USD to AUD are fetched
		$where = array("settings_id"=>1);
		$settings = $this->common_model->get_all_record("ks_settings",$where);
        
        if ($this->input->post()!='') {
		
		    
            $path = ROOT_UPLOAD_IMPORT_PATH;
 
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls|jpg|png';
            $config['remove_spaces'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
			
			
			//print_r($data); die;
            
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
			
			
            $inputFileName = $path . $import_xls_file;
			
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			
			
			//print_r($allDataInSheet); die;
            
            $arrayCount = count($allDataInSheet);
            $flag = 0;
			
			$manufacturer = 'Manufacturer';
			$model = 'Model';
			$description = 'Description';
			$price_per_Day_USD = 'Price per Day (USD)';
			$price_Per_Day_AUD_ex_GST = 'Price Per Day (AUD ex GST)';
			$price_per_Day_inc_GST = 'Price per Day (inc GST)';
			$replacement_Value_USD = 'Replacement Value (USD)';
			$replacement_Value_AUD_ex_GST = 'Replacement Value (AUD ex GST)';
			$replacement_Value_AUD_inc_GST = 'Replacement Value (AUD inc GST)';
			$replacement_day_rate_percent = 'Replacement/Day rate %';
			$image_Name = 'Image Name';
			$category = 'Category';
			$sub_category = 'Sub Category';
			
			
			
            $createArray = array($manufacturer, $model, $description, $price_per_Day_USD, $price_Per_Day_AUD_ex_GST,$price_per_Day_inc_GST,$replacement_Value_USD,$replacement_Value_AUD_ex_GST,$replacement_Value_AUD_inc_GST,$replacement_day_rate_percent,$image_Name,$category,$sub_category);
            $makeArray = array('manufacturer' => $manufacturer, 
							   'model' => $model, 
							   'description' => $description, 
							   'price_per_Day_USD' => $price_per_Day_USD, 
							   'price_Per_Day_AUD_ex_GST' => $price_Per_Day_AUD_ex_GST,
							   'price_per_Day_inc_GST'=>$price_per_Day_inc_GST,
							   'replacement_Value_USD'=>$replacement_Value_USD,
							   'replacement_Value_AUD_ex_GST'=>$replacement_Value_AUD_ex_GST,
							   'replacement_Value_AUD_inc_GST'=>$replacement_Value_AUD_inc_GST,
							   'replacement_day_rate_percent'=>$replacement_day_rate_percent,
							   'image_Name'=>$image_Name,
							   'category'=>$category,
							   'sub_category'=>$sub_category);
			
			
            $SheetDataKey = array();
			
			
            foreach ($allDataInSheet as $dataInSheet) {
                foreach ($dataInSheet as $key => $value) {
                    if (in_array(trim($value), $createArray)) {
                        $value = preg_replace('/\s+/', '', $value);
                        $SheetDataKey[trim($value)] = $key;
                    } else {
                        
                    }
                }
            }
			
            $data = array_diff_key($makeArray, $SheetDataKey);
			
			//print_r($SheetDataKey);exit();
			
			//echo count($data); die;
           
            if(empty($data)) {
			   
                $flag = 1;
            }
			
			
			$flag = 1;
			
			
            if ($flag == 1) {
			   
                for ($i = 2; $i <= $arrayCount; $i++) {
				
                    $addresses = array();
					$action = "add";

					
                    $manufacturer = $SheetDataKey['Manufacturer'];
					
					$model = $SheetDataKey['Model'];
					$description = $SheetDataKey['Description'];
					$PriceperDayUSD = $SheetDataKey['PriceperDay(USD)'];
				
					$PricePerDayAUDexGST = $SheetDataKey['PricePerDay(AUDexGST)'];
					$PriceperDayincGST = $SheetDataKey['PriceperDay(incGST)'];
					
					/*$PricePerDayAUDexGST = $SheetDataKey['PriceperDay(USD)']*$settings[0]->usd_to_aud;
					$PriceperDayincGST = $PricePerDayAUDexGST*$settings[0]->gst_percent;*/
					$replacementValueUSD = $SheetDataKey['ReplacementValue(USD)'];
					
					$replacementValueAUDexGST = $SheetDataKey['ReplacementValue(AUDexGST)'];
					$ReplacementValueAUDincGST = $SheetDataKey['ReplacementValue(AUDincGST)'];
					
					$replacementDayRatePercent = $SheetDataKey['Replacement/Dayrate%'];
					
					$imagename = $SheetDataKey['ImageName'];
					$category = $SheetDataKey['Category'];
					$subcategory = $SheetDataKey['SubCategory'];
					
					$manufacturer = addslashes(trim($allDataInSheet[$i][$manufacturer]));
					$model = addslashes(trim($allDataInSheet[$i][$model]));
					if($model!=""){
					
							//Checked whether this Model Name exists or not
							$model_result = $this->model->checkduplicatemodel($model);
							if(count($model_result)>0){
							
								$action = "update";
								$model_id = $model_result[0]->model_id;								
							}							
							
						
							$description = addslashes(trim($allDataInSheet[$i][$description]));
							$PriceperDayUSD = addslashes(trim($allDataInSheet[$i][$PriceperDayUSD]));
							//$PricePerDayAUDexGST = filter_var(trim($allDataInSheet[$i][$PricePerDayAUDexGST]), FILTER_SANITIZE_STRING);
							//$PriceperDayincGST = filter_var(trim($allDataInSheet[$i][$PriceperDayincGST]), FILTER_SANITIZE_STRING);
							$PricePerDayAUDexGST = $PriceperDayUSD*$settings[0]->usd_to_aud;
							$PriceperDayincGST = $PricePerDayAUDexGST*$settings[0]->gst_percent;
							
							$replacementValueUSD = addslashes(trim($allDataInSheet[$i][$replacementValueUSD]));
							//$replacementValueAUDexGST = filter_var(trim($allDataInSheet[$i][$replacementValueAUDexGST]), FILTER_SANITIZE_STRING);
							//$ReplacementValueAUDincGST = filter_var(trim($allDataInSheet[$i][$ReplacementValueAUDincGST]), FILTER_SANITIZE_STRING);	
							
							$replacementValueAUDexGST = $replacementValueUSD*$settings[0]->usd_to_aud;
							$ReplacementValueAUDincGST = $replacementValueAUDexGST*$settings[0]->gst_percent;

							if($ReplacementValueAUDincGST>0)
								$replacementDayRatePercent = round(($PriceperDayincGST/$ReplacementValueAUDincGST)*100,2);
							else
								$replacementDayRatePercent = 0.00;
											
							$imagename = addslashes(trim($allDataInSheet[$i][$imagename]));
							$category = addslashes(trim($allDataInSheet[$i][$category]));
							$subcategory = addslashes(trim($allDataInSheet[$i][$subcategory]));
							
							//checking whether the Manufacturers is empty or not
							if($manufacturer!=''){
								//duplicate checking for Manufacturers
								$query = $this->common_model->GetAllWhere("ks_manufacturers",array("manufacturer_name"=>$manufacturer));
								if($query->num_rows()>0){
									$result = $query->result();
									$manufacturer_id = $result[0]->manufacturer_id;
								}else{
									//insert Manufacturers
									$row['manufacturer_name']= $manufacturer;
									$row['is_active'] = 'Y';
									$row['create_user'] = $this->session->userdata('ADMIN_ID');
									$row['create_date'] = date('Y-m-d');
									$manufacturer_id = $this->common_model->addRecord('ks_manufacturers',$row);
								}
						   }
						   
						   //cheacking whether the Gear Category is empty or not
							if($category!=''){
								//duplicate checking for Gear Category
								$query_category = $this->common_model->GetAllWhere("ks_gear_categories",array("gear_category_name"=>$category));
								if($query_category->num_rows()>0){
									$result_category = $query_category->result();
									$gear_category_id = $result_category[0]->gear_category_id;
								}else{
									//$row_category = array();
									//insert category
									//$row_category['gear_category_name']= $category;
									//$row_category['is_active'] = 'Y';
									//$row_category['create_user'] = $this->session->userdata('ADMIN_ID');
									//$row_category['create_date'] = date('Y-m-d');
									//$gear_category_id = $this->common_model->addRecord('ks_gear_categories',$row_category);
									
									
								}
							}
								
								//cheacking whether the Gear Sub Category is empty or not
								if($subcategory!=''){
									//duplicate checking for Manufacturers
									$query_sub_category = $this->common_model->GetAllWhere("ks_gear_categories",array("gear_category_name"=>$subcategory,"gear_category_id"=>$gear_category_id));
									if($query_sub_category->num_rows()>0){
										$result_sub_category = $query_sub_category->result();
										$gear_category_id = $result_sub_category[0]->gear_category_id;
										
									}else{
										//$row_sub_category = array();
										//insert sub category
									///	$row_sub_category['gear_sub_category_id']= $gear_category_id;
									//	$row_sub_category['gear_category_name']= $subcategory;
										//$row_sub_category['is_active'] = 'Y';
									//	$row_sub_category['create_user'] = $this->session->userdata('ADMIN_ID');
									//	$row_sub_category['create_date'] = date('Y-m-d');
									//	$gear_category_id = $this->common_model->addRecord('ks_gear_categories',$row_sub_category);
									}
							   }
								
						   
						   //cheacking whether the Model is empty or not
							/*if($model!=''){
								//duplicate checking for Manufacturers
								$query_model = $this->common_model->GetAllWhere("ks_models",array("model_name"=>$model,"gear_category_id"=>$gear_category_id));
								if($query_model->num_rows()>0){
									$result_model = $query_model->result();
									$model_id = $result_model[0]->model_id;
								}else{*/
									//insert Manufacturers
							if( $category!=''  && $subcategory !=''){		
									$row_model['model_name']= $model;
									$row_model['model_description'] = $description;
									$row_model['gear_category_id'] = $gear_category_id;
									$row_model['manufacturer_id'] = $manufacturer_id;
									$row_model['per_day_cost_usd'] = $PriceperDayUSD;
									$row_model['per_day_cost_aud_ex_gst'] = $PricePerDayAUDexGST;
									$row_model['per_day_cost_aud_inc_gst'] = $PriceperDayincGST;
									$row_model['per_weekend_cost_usd'] = $PriceperDayUSD;
									$row_model['per_weekend_cost_aud_ex_gst'] = $PricePerDayAUDexGST;
									$row_model['per_weekend_cost_aud_inc_gst'] = $PriceperDayincGST;
									$row_model['per_week_cost_usd'] = $PriceperDayUSD*3;
									$row_model['per_week_cost_aud_ex_gst'] = $PricePerDayAUDexGST*3;
									$row_model['per_week_cost_aud_inc_gst'] = $PriceperDayincGST*3;
									$row_model['replacement_value_usd'] = $replacementValueUSD;
									$row_model['replacement_value_aud_ex_gst'] = $replacementValueAUDexGST;
									$row_model['replacement_value_aud_inc_gst'] = $ReplacementValueAUDincGST;
									$row_model['replacement_day_rate_percent'] = $replacementDayRatePercent;
									$row_model['model_image'] = $imagename;
									$row_model['is_approved'] = 'N';
									$row_model['is_active'] = 'Y';
									$row_model['create_user'] = $this->session->userdata('ADMIN_ID');
									$row_model['create_date'] = date('Y-m-d');
									
									if($action == "update"){	
										$this->db->where("model_id", $model_id);
										$this->db->update('ks_models', $row_model);
										//$this->db->get();
									}
									else																	
										$model_id = $this->common_model->addRecord('ks_models',$row_model);
							}		
								//}
						   //}  
						
						}
					}
				
				
            } else {
                echo "Please import correct file";
            }
			
        }
		$message = '<div class="alert alert-success"><p>Model uploaded successfully.</p></div>';
		$this->session->set_flashdata('success', $message);
		redirect('models');
        
    }
}
?>