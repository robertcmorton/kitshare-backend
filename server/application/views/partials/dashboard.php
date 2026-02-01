<section class="dashboard_body">
   <div class="container" ng-controller="TabController">
      <div class="row">
         <div class="col-md-12">
            <div class="tabbable-panel">
               <div class="tabbable-line">
                  <ul class="nav nav-tabs ">
                     <li  ng-class="{ active: isSet(1) }" class="active">
                        <a href ng-click="setTab(1)" data-toggle="tab">
                        Rentals</a>
                     </li>
                     <li ng-class="{ active: isSet(2) }">
                        <a href ng-click="setTab(2)" data-toggle="tab">
                        Invoices</a>
                     </li>
                     <li ng-class="{ active: isSet(3) }">
                        <a href ng-click="setTab(3)" data-toggle="tab">
                        References</a>
                     </li>
                     <li ng-class="{ active: isSet(4) }">
                        <a href ng-click="setTab(4)" data-toggle="tab">
                        Messages</a>
                     </li>
                     <li ng-class="{ active: isSet(5) }">
                        <a href ng-click="setTab(5)" data-toggle="tab">
                        Reviews</a>
                     </li>
                  </ul>
                  <div class="tab-content">
                     <div class="tab-pane   active" id="tab_default_1" ng-show="isSet(1)">
                        <div class="row">
                           <div class="col-md-12 col-sm-12">
                              <div class="renter_owner_buttn">
                                 <button type="button" class="btn btn_new">Renter</button>
                                 <button type="button" class="btn btn_new active_btns">Owner</button>
                                 <select class="form-control" id="sel1">
                                    <option>2018</option>
                                    <option>2019</option>
                                    <option>2020</option>
                                    <option>2021</option>
                                 </select>
                              </div>
                              <div class="clearfix"></div>
                           </div>
                           <div class="col-md-12 col-sm-12">
                              <div class="search_rental">
                                 <div class="all_btns">
                                    <button type="button" class="btn btn-secondary category-btn">All (1)</button>
                                    <button type="button" class="btn btn-secondary category-btn"> All rentals</button>
                                    <button type="button" class="btn btn-secondary category-btn"> Upcoming</button>
                                    <button type="button" class="btn btn-secondary category-btn">In progress</button>
                                    <button type="button" class="btn btn-secondary category-btn">Disputed</button>
                                    <button type="button" class="btn btn-secondary category-btn">Completed</button>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="table-responsive points_table">
                              <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-hover">
                                 <thead>
                                    <tr>
                                       <th></th>
                                       <th>Owner Name</th>
                                       <th>Order Number</th>
                                       <th>Item</th>
                                       <th>Cost</th>
                                       <th>Start Date</th>
                                       <th>End Date</th>
                                       <th>Total Paid</th>
                                       <th></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td><img src="assets/images/owner.png"></td>
                                       <td>John Smith</td>
                                       <td>ks11111</td>
                                       <td>Aaton XTR Prod</td>
                                       <td>$267.000 per day</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>$267</td>
                                       <td>
                                          <a href="#" role="button" data-toggle="dropdown" data-target="#"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                                          <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                             <li><a href="#">In progress</a></li>
                                             <li><a href="#">Messages</a></li>
                                             <li><a href="#">Kit listing</a></li>
                                             <li><a href="#">Delivery information</a></li>
                                             <li><a href="#">Invoices</a></li>
                                          </ul>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        <div class="mesage_table">
                           <div class="row">
                              <div class="all_message">
                                 <select name="limit" id="limit" class="show_p">
                                    <option value="" selected="selected">All messages</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                 </select>
                              </div>
                              <div class="mesage_rantal">
                                 <div class="first_p">
                                    <div class="message_image">
                                       <img src="assets/images/rental_table_img.png" class="image_with1">
                                    </div>
                                    <div class="name_date">
                                       <p>Inna</p>
                                       <p>06/05/2018</p>
                                    </div>
                                    <div class="notificatio">
                                       Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                    </div>
                                    <div class="ammount">
                                       <p class="highlight1">Acepted</p>
                                       <p>$224 AUD</p>
                                    </div>
                                 </div>
                                 <div class="first_p">
                                    <div class="message_image">
                                       <img src="assets/images/rental_table_img.png" class="image_with1">
                                    </div>
                                    <div class="name_date">
                                       <p>Inna</p>
                                       <p>06/05/2018</p>
                                    </div>
                                    <div class="notificatio">
                                       Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                    </div>
                                    <div class="ammount">
                                       <p class="highlight1">Acepted</p>
                                       <p>$224 AUD</p>
                                    </div>
                                 </div>
                                 <div class="first_p">
                                    <div class="message_image">
                                       <img src="assets/images/rental_table_img.png" class="image_with1">
                                    </div>
                                    <div class="name_date">
                                       <p>Inna</p>
                                       <p>06/05/2018</p>
                                    </div>
                                    <div class="notificatio">
                                       Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                    </div>
                                    <div class="ammount">
                                       <p class="highlight1">Acepted</p>
                                       <p>$224 AUD</p>
                                    </div>
                                 </div>
                                 <div class="first_p">
                                    <div class="message_image">
                                       <img src="assets/images/rental_table_img.png" class="image_with1">
                                    </div>
                                    <div class="name_date">
                                       <p>Inna</p>
                                       <p>06/05/2018</p>
                                    </div>
                                    <div class="notificatio">
                                       Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                    </div>
                                    <div class="ammount">
                                       <p class="highlight1">Acepted</p>
                                       <p>$224 AUD</p>
                                    </div>
                                 </div>
                                 <div class="first_p">
                                    <div class="message_image">
                                       <img src="assets/images/rental_table_img.png" class="image_with1">
                                    </div>
                                    <div class="name_date">
                                       <p>Inna</p>
                                       <p>06/05/2018</p>
                                    </div>
                                    <div class="notificatio">
                                       Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                    </div>
                                    <div class="ammount">
                                       <p class="highlight1">Acepted</p>
                                       <p>$224 AUD</p>
                                    </div>
                                 </div>
                                 <div class="first_p">
                                    <div class="message_image">
                                       <img src="assets/images/rental_table_img.png" class="image_with1">
                                    </div>
                                    <div class="name_date">
                                       <p>Inna</p>
                                       <p>06/05/2018</p>
                                    </div>
                                    <div class="notificatio">
                                       Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                    </div>
                                    <div class="ammount">
                                       <p class="highlight1">Acepted</p>
                                       <p>$224 AUD</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane" id="tab_default_2" ng-show="isSet(2)">
                        <div class="row">
                           <div class="col-md-2 col-sm-12">
                              <div class="switch-view-section renter rental_dashboard responsive_for">
                                 <button type="button" class="btn btn-blue-bg">Renter dashboard</button>
                              </div>
                              <div class="switch-view-section renter rental_dashboard hidden-md hidden-lg">
                                 <div class="space-above-20"></div>
                                 <a href="#">
                                 <button type="button" class="btn btn-blue-bg pull-right">Switch to owner view</button>
                                 </a>        
                              </div>
                              <div class="clearfix"></div>
                           </div>
                           <div class="col-md-8 col-sm-12">
                              <div class="search_rental">
                                 <div class="all_btns">
                                    <button type="button" class="btn btn-secondary category-btn">All (1)</button>
                                    <button type="button" class="btn btn-secondary category-btn"> All rentals</button>
                                    <button type="button" class="btn btn-secondary category-btn"> Upcoming</button>
                                    <button type="button" class="btn btn-secondary category-btn">In progress</button>
                                    <button type="button" class="btn btn-secondary category-btn">Disputed</button>
                                    <button type="button" class="btn btn-secondary category-btn">Completed</button>
                                 </div>
                                 <div class="form-group form-group50">
                                    <label class="col-sm-3 control-label" for="expiry-month">Year</label>
                                    <div class="col-sm-9">
                                       <div class="row">
                                          <div class="col-xs-12">
                                             <select class="form-control col-sm-2" name="expiry-month" id="expiry-month">
                                                <option>Fy 17-10</option>
                                                <option value="01">Fy 16-17</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="clearfix"></div>
                                 <div class="total_amount">
                                    <p><strong>Total Paid(18)</strong></p>
                                    <p>Australia AUD$<strong>999</strong></p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-2 col-sm-2">
                              <div class="switch-view-section renter rental_dashboard hidden-xs hidden-sm">
                                 <div class="space-above-20"></div>
                                 <a href="#">
                                 <button type="button" class="btn btn-blue-bg pull-right">Switch to owner view</button>
                                 </a>        
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="table-responsive points_table">
                              <table summary="This table shows how to create responsive tables using Bootstrap's default functionality" class="table table-bordered table-hover">
                                 <thead>
                                    <tr>
                                       <th>Rental Order Number</th>
                                       <th>Item Image</th>
                                       <th>Item name</th>
                                       <th>Owners Name</th>
                                       <th>Rental Dates Start</th>
                                       <th>Rental Dates End</th>
                                       <th>Total Paid</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                    <tr>
                                       <td>47895621</td>
                                       <td><a href="#"><img src="assets/images/rental_table_img.png" class="image_with"></a></td>
                                       <td>Client monitar</td>
                                       <td>Lorenipsum</td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>sep</span></h4>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="date">
                                             <h4>01<span>oct</span></h4>
                                          </div>
                                       </td>
                                       <td>AUD$</td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane" id="tab_default_3" ng-show="isSet(3)">
                        <p>
                           Howdy, I'm in Tab 3.
                        </p>
                        <p>
                           Duis autem vel eum iriure dolor in hendrerit in vulputate. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat
                        </p>
                        <p>
                           <a class="btn btn-info" href target="_blank">
                           Learn more...
                           </a>
                        </p>
                     </div>
                     <div class="mesage_table" id="tab_default_4" ng-show="isSet(4)">
                        <div class="col-md-12 col-sm-12">
                           <div class="renter_owner_buttn">
                              <div class="make-switch switch-large" id="label-switch">
                                 <input id="ms" type="checkbox" checked="true" class="probeProbe" />
                              </div>
                           </div>
                           <div class="clearfix"></div>
                        </div>
                        <div class="row">
                           <div class="all_message">
                              <select class="show_p" id="limit" name="limit">
                                 <option selected="selected" value="">All messages</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                              </select>
                           </div>
                           <div class="mesage_rantal">
                              <div class="first_p">
                                 <div class="message_image">
                                    <img class="image_with1" src="assets/images/rental_table_img.png">
                                 </div>
                                 <div class="name_date">
                                    <p>Inna</p>
                                    <p>06/05/2018</p>
                                 </div>
                                 <div class="notificatio">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                 </div>
                                 <div class="ammount">
                                    <p class="highlight1">Acepted</p>
                                    <p>$224 AUD</p>
                                 </div>
                              </div>
                              <div class="first_p">
                                 <div class="message_image">
                                    <img class="image_with1" src="assets/images/rental_table_img.png">
                                 </div>
                                 <div class="name_date">
                                    <p>Inna</p>
                                    <p>06/05/2018</p>
                                 </div>
                                 <div class="notificatio">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                 </div>
                                 <div class="ammount">
                                    <p class="highlight1">Acepted</p>
                                    <p>$224 AUD</p>
                                 </div>
                              </div>
                              <div class="first_p">
                                 <div class="message_image">
                                    <img class="image_with1" src="assets/images/rental_table_img.png">
                                 </div>
                                 <div class="name_date">
                                    <p>Inna</p>
                                    <p>06/05/2018</p>
                                 </div>
                                 <div class="notificatio">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                 </div>
                                 <div class="ammount">
                                    <p class="highlight1">Acepted</p>
                                    <p>$224 AUD</p>
                                 </div>
                              </div>
                              <div class="first_p">
                                 <div class="message_image">
                                    <img class="image_with1" src="assets/images/rental_table_img.png">
                                 </div>
                                 <div class="name_date">
                                    <p>Inna</p>
                                    <p>06/05/2018</p>
                                 </div>
                                 <div class="notificatio">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                 </div>
                                 <div class="ammount">
                                    <p class="highlight1">Acepted</p>
                                    <p>$224 AUD</p>
                                 </div>
                              </div>
                              <div class="first_p">
                                 <div class="message_image">
                                    <img class="image_with1" src="assets/images/rental_table_img.png">
                                 </div>
                                 <div class="name_date">
                                    <p>Inna</p>
                                    <p>06/05/2018</p>
                                 </div>
                                 <div class="notificatio">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                 </div>
                                 <div class="ammount">
                                    <p class="highlight1">Acepted</p>
                                    <p>$224 AUD</p>
                                 </div>
                              </div>
                              <div class="first_p">
                                 <div class="message_image">
                                    <img class="image_with1" src="assets/images/rental_table_img.png">
                                 </div>
                                 <div class="name_date">
                                    <p>Inna</p>
                                    <p>06/05/2018</p>
                                 </div>
                                 <div class="notificatio">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                                 </div>
                                 <div class="ammount">
                                    <p class="highlight1">Acepted</p>
                                    <p>$224 AUD</p>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <section id="reviews-section" class="prof-lg__reviews" class="tab-pane" id="tab_default_5" ng-show="isSet(5)" style="border-top: medium none;">
                        <div class="prof-user-reviews">
                           <div class="prof-user-reviews__heading">
                              <div class="prof-user-reviews__heading__title">
                                 <h4>Reviews <span>({{ (reviews | filter: owner_type_id).length }})</span></h4>
                              </div>
                              <select  class="form-control" id="sel2" ng-model="owner_type_id">
                                 <!--<option value="" ng-model="owner_type_id" ng-selected="datalist.type == item.id" ng-repeat="item in type"  ng-value="item.value">{{item.label}}</option>-->
                                 <option value="">Owners & Renters</option>
                                 <option>Owner</option>
                                 <option>Renter</option>
                              </select>
                           </div>
                           <div class="user-rating" ng-if="reviews.length > 0" ng-repeat="review in reviews | filter: owner_type_id">
                              <div class="user-rating__heading">
                                 <a href="#Profile" class="user-rating__heading__avatar-link">
                                 <img alt="no image found" src="{{review.user_profile_picture_link}}" 
                                    class="user-rating__heading__avatar img-circle"></a>
                                 <a href="#Profile" class="user-rating__heading__name">{{review.profile_fname}}</a>
                                 <div class="user-rating__heading__rating">
                                    <div class="rating-stars">
                                       <span class="dont-wrap">
                                          <span>
                                             <!--{{rating.current}} out of{{rating.max}}-->
                                             <div class="disable" star-rating rating-value="review.gear_star_rating_value" max="5"></div>
                                          </span>
                                       </span>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                              <div  class="user-rating__body">
                                 <p>{{review.cust_gear_review_desc}}</p>
                              </div>
                              <div class="user-rating__meta">
                                 <p class="user-rating__meta__date">
                                    <i class="fa fa-calendar"></i>
                                    {{review.cust_gear_review_date}}
                                 </p>
                              </div>
                           </div>
                           <div class="sk-message-box leave-endorsement-message-box" id="noreview" style="display:none;">
                              <h6>You don't have any reviews yet.</h6>
                              <p>You'll get your first review after you complete your first rental. Why not find some gear to rent?</p>
                           </div>
                        </div>
                     </section>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
