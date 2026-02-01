<?php
class Mail_model extends CI_Model {

	public function __construct() {
	
		parent::__construct();
	}
	
	public function welcome_mail($name,$url) {
	
		$msg = '<div style="margin:0;padding:10px 0;background-image:url('.base_url().'assets/images/bg-tile.png)" bgcolor="#f0f0f0"> <br>
  <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="text-align: center; background: rgb(244, 244, 244) none repeat scroll 0% 0%;">
    <tbody>
      <tr>
        <td align="center" valign="top"><table width="600" cellpadding="0" cellspacing="0" border="0" class="m_9107790119469208415m_5762250937799609813container" bgcolor="#ffffff">
            <tbody>
              <tr>
                <td class="m_9107790119469208415m_5762250937799609813logo"><table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                      <tr>
                        <td><div style="padding: 12px 0px; text-align: center; background-color: #004165;"> <a href="'.base_url().'" target="_blank" ><img src="'.base_url().'assets/images/logo.png" width="140"  class="CToWUd"></a> </div></td>
                        <td style="text-align:right" valign="middle"></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td class="m_9107790119469208415m_5762250937799609813container-padding" bgcolor="#ffffff" style="text-align: center;background-color:#ffffff;padding-left:30px;padding-right:30px;padding-bottom:20px;padding-top:30px;line-height:22px"><table width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><div style="font-size:15px;line-height:24px;color:#4a5359"> Dear '.$name.',<br>
                          </div></td>
                      </tr>
                      <tr>
                        <td height="20">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><div style="font-size:15px;line-height:24px;color:#4a5359"> Thank you for signing up to Kitshare! <br>
                            <br>
                            To complete your account please click on the following link: <br>
                            <br>
                            <div> <a href="'.$url.'" style="background-color:#f26522;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:15px;font-weight:bold;line-height:44px;text-align:center;text-decoration:none;width:180px" target="_blank" >Confirm your account</a> </div>
                            <br>
                            This link can only be used once.
                            <p style="font-size:15px;color:#4a5359"> The Kitshare team. </p>
                          </div></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
</div>';
	
	return $msg;

	}
	
public function forgetpassword_mail($name,$url) {
	
		$msg = '<div style="margin:0;padding:10px 0;background-image:url('.base_url().'assets/images/bg-tile.png)" bgcolor="#f0f0f0"> <br>
  <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="text-align: center; background: rgb(244, 244, 244) none repeat scroll 0% 0%;">
    <tbody>
      <tr>
        <td align="center" valign="top"><table width="600" cellpadding="0" cellspacing="0" border="0" class="m_9107790119469208415m_5762250937799609813container" bgcolor="#ffffff">
            <tbody>
              <tr>
                <td class="m_9107790119469208415m_5762250937799609813logo"><table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                      <tr>
                        <td><div style="padding: 12px 0px; text-align: center; background-color: #004165;"> <a href="'.base_url().'" target="_blank" ><img src="'.base_url().'assets/images/logo.png" width="140"  class="CToWUd"></a> </div></td>
                        <td style="text-align:right" valign="middle"></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td class="m_9107790119469208415m_5762250937799609813container-padding" bgcolor="#ffffff" style="text-align: center;background-color:#ffffff;padding-left:30px;padding-right:30px;padding-bottom:20px;padding-top:30px;line-height:22px"><table width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                        <td><div style="font-size:15px;line-height:24px;color:#4a5359"> Dear '.$name.',<br>
                          </div></td>
                      </tr>
                      <tr>
                        <td height="20">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><div style="font-size:15px;line-height:24px;color:#4a5359"> To change your password, click the following link! <br>
                            <br>
                            This link will expire in 1 hour, so be sure to use it right away. <br>
                            <br>
                            <div> <a href="'.$url.'" style="background-color:#f26522;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:15px;font-weight:bold;line-height:44px;text-align:center;text-decoration:none;width:180px" target="_blank" >Confirm your account</a> </div>
                            <br>
                            This link can only be used once.
                            <p style="font-size:15px;color:#4a5359"> The Kitshare team. </p>
                          </div></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
</div>';
	
	return $msg;

	}
  public function Reference_mail($msg,$url)
  {
    $msg = '<div style="margin:0;padding:10px 0;background-image:url('.base_url().'assets/images/bg-tile.png)" bgcolor="#f0f0f0"> <br>
  <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="text-align: center; background: rgb(244, 244, 244) none repeat scroll 0% 0%;">
    <tbody>
      <tr>
        <td align="center" valign="top"><table width="600" cellpadding="0" cellspacing="0" border="0" class="m_9107790119469208415m_5762250937799609813container" bgcolor="#ffffff">
            <tbody>
              <tr>
                <td class="m_9107790119469208415m_5762250937799609813logo"><table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                      <tr>
                        <td><div style="padding: 12px 0px; text-align: center; background-color: #004165;"> <a href="'.base_url().'" target="_blank" ><img src="'.base_url().'assets/images/logo.png" width="140"  class="CToWUd"></a> </div></td>
                        <td style="text-align:right" valign="middle"></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              <tr>
                <td class="m_9107790119469208415m_5762250937799609813container-padding" bgcolor="#ffffff" style="text-align: center;background-color:#ffffff;padding-left:30px;padding-right:30px;padding-bottom:20px;padding-top:30px;line-height:22px"><table width="100%" cellspacing="0" cellpadding="0">
                    <tbody>
                      <tr>
                       
                      </tr>
                      <tr>
                        <td height="20">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><div style="font-size:15px;line-height:24px;color:#4a5359"> 
                          "'.$msg.'"
                            <br>
                            <br>
                            <div> <a href="'.$url.'" style="background-color:#f26522;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:15px;font-weight:bold;line-height:44px;text-align:center;text-decoration:none;width:180px" target="_blank" >Give Reference </a> </div>
                            <br>
                            This link can only be used once.
                            <p style="font-size:15px;color:#4a5359"> The Kitshare team. </p>
                          </div>

                        </td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
              
            </tbody>
          </table></td>
      </tr>
    </tbody>
  </table>
</div>';
  
  return $msg;
  }
	
	
   public function Contact_mail($name,$content,$url ,$sender_name) {
  
    $msg = '<div style="margin:0;padding:10px 0;background-image:url('.base_url().'assets/images/bg-tile.png)" bgcolor="#f0f0f0"> <br>
      <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="text-align: center; background: rgb(244, 244, 244) none repeat scroll 0% 0%;">
        <tbody>
          <tr>
            <td align="center" valign="top"><table width="600" cellpadding="0" cellspacing="0" border="0" class="m_9107790119469208415m_5762250937799609813container" bgcolor="#ffffff">
                <tbody>
                  <tr>
                    <td class="m_9107790119469208415m_5762250937799609813logo"><table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                          <tr>
                            <td><div style="padding: 12px 0px; text-align: center; background-color: #004165;"> <a href="'.base_url().'" target="_blank" ><img src="'.base_url().'assets/images/logo.png" width="140"  class="CToWUd"></a> </div></td>
                            <td style="text-align:right" valign="middle"></td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  <tr>
                    <td class="m_9107790119469208415m_5762250937799609813container-padding" bgcolor="#ffffff" style="text-align: center;background-color:#ffffff;padding-left:30px;padding-right:30px;padding-bottom:20px;padding-top:30px;line-height:22px"><table width="100%" cellspacing="0" cellpadding="0">
                        <tbody>
                          <tr>
                            <td><div style="font-size:15px;line-height:24px;color:#4a5359"> Dear '.$name.',<br>
                              </div></td>
                          </tr>
                          <tr>
                            <td height="20">&nbsp;</td>
                          </tr>
                          <tr>
                            <td><div style="font-size:15px;line-height:24px;color:#4a5359"> '.$sender_name.' from kitshare sent you a message !  <br>
                                <br>
                                '.$content.'
                                <br>
                                <br>
                                <br>
                                <br>
                                <div> <a href="'.$url.'" style="background-color:#f26522;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:15px;font-weight:bold;line-height:44px;text-align:center;text-decoration:none;width:180px" target="_blank" >Reply Message</a> </div>
                                <br>
                                
                                <p style="font-size:15px;color:#4a5359"> The Kitshare team. </p>
                              </div></td>
                          </tr>
                        </tbody>
                      </table></td>
                  </tr>
                  
                </tbody>
              </table></td>
          </tr>
        </tbody>
      </table>
    </div>';
  
  return $msg;

  }
  
} // end of class