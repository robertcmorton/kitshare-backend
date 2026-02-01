<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>::Kitshare::</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/responsive.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
  <div class="wrapper">
    <div class="row">
      <div class="col-md-8">
        <div class="logo_pdf"> <img src="images/pdf_logo.png" class=" img-responsive"> </div>
      </div>
      <div class="col-md-4">
        <div class="info_heading">
          <h3>TAX INVOICE</h3>
          <table width="100%" border="0">
            <tr>
              <td class="table_head">REFERENCE:</td>
              <td class="reff">20140904001</td>
            </tr>
            <tr>
              <td class="table_head">BILLING DATE:</td>
              <td class="reff">09 Nov 2018</td>
            </tr>
            <tr>
              <td class="table_head">RENTAL DATES:</td>
              <td class="reff">09Nov-19Nov</td>
            </tr>
            <tr>
              <td class="table_head">OWNER:</td>
              <td class="reff">Robert Morton</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="add_details_new">
      <div class="row">
        <div class="col-md-6">
          <div class="add_left">
            <h2>OUR INFORMATION</h2>
            <h3>Kitshare Pty Ltd</h3>
            <ul>
              <li>PO BOX 131</li>
              <li>Seaforth</li>
              <li>NSW 2092</li>
              <li>Australia</li>
              <li>ABN 85 623 435 709</li>
            </ul>
          </div>
        </div>
        <div class="col-md-6">
          <div class="add_left">
            <h2>BILLING TO</h2>
            <h3>Renter Business name</h3>
            <ul>
              <li> Contact Name</li>
              <li>Suite Street Address</li>
              <li>Suburb</li>
              <li>STATE POSTCODE</li>
              <li>Country</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="invoice_table">
      <div class="row">
        <div class="table_invoice">
          <div class="table-responsive">
            <table class="table table-condensed">
              <thead>
                <tr>
                  <td><strong>Description </strong></td>
                  <td class="text-center"><strong>Quantity </strong></td>
                  <td class="text-center"><strong>Unit Price</strong></td>
                  <td class="text-right"><strong>GST</strong></td>
                  <td class="text-right"><strong>Amount AUD</strong></td>
                </tr>
              </thead>
              <tbody>
                <!-- foreach ($order->lineItems as $line) or some such thing here -->
                <tr>
                  <td>Arri MMB2 Stage Matte Box with 8 Tiffen ND/IRND filters</td>
                  <td class="text-center">1.0</td>
                  <td class="text-center">1300.00</td>
                  <td class="text-right">10%</td>
                  <td class="text-right">1300.00</td>
                </tr>
                <tr>
                  <td>Arri MMB2 Stage Matte Box with 8 Tiffen ND/IRND filters</td>
                  <td class="text-center">1.0</td>
                  <td class="text-center">1300.00</td>
                  <td class="text-right">10%</td>
                  <td class="text-right">1300.00</td>
                </tr>
                <tr>
                  <td class="thick-line"></td>
                  <td class="thick-line"></td>
                  <td class="thick-line"></td>
                  <td class="thick-line text-right"><strong>Subtotal</strong></td>
                  <td class="thick-line text-right">80 INR</td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>Discount(15%)</strong></td>
                  <td class="no-line text-right">-199.0</td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right "><strong>Insurance Fee</strong></td>
                  <td class="no-line text-right">132.0</td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>Community Fee</strong></td>
                  <td class="no-line text-right">67.33</td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>TOTAL ex GST</strong></td>
                  <td class="no-line text-right">1,327.15</td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right"><strong>TOTAL GST 10%</strong></td>
                  <td class="no-line text-right">132.75</td>
                </tr>
                <tr>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line"></td>
                  <td class="no-line text-right" style="border-top:2px solid #000; font-size:20px; font-weight:bolder"><strong>TOTAL AUD</strong></td>
                  <td class="no-line text-right" style="border-top:2px solid #000;  font-size:20px; font-weight:bolder">1460.24</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="payment_infor">
      <div class="row">
        <div class="col-md-12">
          <div class="add_left">
            <h2>PAYMENT INFORMATION</h2>
            <p>Subject to Kitshare ‘Terms and Conditions’ available at www.kitshare.com.au
              If you have any  questions concerning this invoice, contact accounts at support@kitshare.com.au <br>
              <br>
              <br>
              Thank you for your buisness.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 

<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>