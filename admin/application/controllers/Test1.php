<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class Test1 extends CI_Controller {
 
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

    public function LoadPage()
    {
       
        $data = array();

        $this->load->view('common/header'); 

        $this->load->view('common/left-menu');  

        $this->load->view('test', $data);

        $this->load->view('common/footer');     

    }

    public function Calculate($value='')
    {
        echo "<pre>";
        $total_days = '' ;
        $count = '';
        $unavailble_dates =  $this->input->post('date') ; 
        $date_array =  explode( '-' ,$unavailble_dates);
        $from_date_array =  explode('/',$date_array[0]);
        echo "From Date:-";
        echo  $from_date =  trim($from_date_array[2]).'-'.trim($from_date_array[0]).'-'.trim($from_date_array[1]);
        echo "<br>" ;
        echo "To Date:-";
        $to_date_array =  explode('/',$date_array[1]);
        echo  $to_date =  trim($to_date_array[2]).'-'.trim($to_date_array[0]).'-'.trim($to_date_array[1]);
        echo "<br>" ;
        $diff = abs(strtotime($to_date) -strtotime($from_date));
        $days = floor(($diff)/ (60*60*24));
        echo "total days:- " ;
        if ($days > 1) {
           $days =   $days +1-2 ; 
        }else{
             $days =   1;
        }
        print_r($days); 
        echo "<br>" ; 
        echo "Days:-"; 
        echo  $tola_days_reamining =   $days%7 ;         
        echo "<br>" ;
        echo "Weeks:-";   
        echo  $tola_week =  floor( $days/7) ;   
        echo "<br>" ;  
        if  ($tola_week > 0 ){
            echo "Weeks days:-";
            echo $total_days = $tola_week*3 ;
            echo "<br>";
        
           
        } 
        if ($tola_days_reamining  > 1 && $tola_days_reamining <= 6  ) {
            for ($i=1; $i <= $tola_days_reamining ; $i++) { 
                $days1[] =   date('D', strtotime('+'.$i.' day', strtotime($from_date)));
            }
            $count = 0;
            $count1 = 0;


            foreach ($days1 as $value) {
                if ($value == 'Sat'  || $value == 'Sun'  ) {
                    $count1 += 1; 
                }else{
                    $count += 1;

                    if($count >= 3)
                        break;
                }
            }
            print_r($days1);
            echo "Next Day :-";
            echo   date('D', strtotime('+1 day', strtotime($from_date)));
            $next_day =  date('D', strtotime('+1 day', strtotime($from_date)));
            echo "<br>";
            echo "Return Day :-";
            echo   date('D', strtotime('+0 day', strtotime($to_date)));
            echo "<br>";
            echo 'Total Days:- ' ; 
            if ( $count1 >=1 && $count < 3) {
                 $count = $count + 1 ;
             }
            echo $count ;   
            echo "<br>";
            echo $count1 ;   
            echo "<br>";
            echo "total_days :- " ;
            echo  $count+ $total_days  ;    

        }elseif($tola_days_reamining == '1'  && $tola_week ==  0 || $tola_days_reamining == '0'  &&  $tola_week ==  0 ){
            $count = 1 ;
        }elseif($tola_days_reamining == '1' &&  $tola_week  > 0     ){
            $count = 1 ;
        }
        echo "<br>";
        echo "total_days :- " ;
        echo  $count+ $total_days  ;    
    }
    
}
?>