<section class="search_sec">
  <div class="container-fluid application-wrapper">
    <div class="row">
      <div id="main-col"> 
        <!-- ************* LEFT COLUMN **************** -->
        <div class="col-xs-12 col-md-8 search-left">
          <div class="map-search-tools">
            <div class="map-search-tools__categories sk-form__input-wrapper">
              <div class="">
                <div id="categories-below-fold">
                  <div class="categories-container" id="featured-categories">
                    <div tabindex="0" class="category-btn top-level active btn btn-danger" id="category-btn-3">
                      <div class="dropdown">
                        <div aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="owner-types-dropdown" class="dropdown-toggle search-filter__toggle cursor-pointer"> <span class="filter-label">7 Mar &ndash; 8 Mar<span class="count"></span> </span> <span class="applied-filter"></span></div>
                      </div>
                    </div>
                    <div tabindex="0" class="category-btn top-level btn btn-danger" id="category-btn-3">
                      <div class="dropdown">
                        <div aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="owner-types-dropdown" class="dropdown-toggle search-filter__toggle cursor-pointer"> <span class="filter-label">CATEGORY<span class="count"></span> 
						
						</span> <span class="applied-filter"></span></div>
                        <ul  aria-labelledby="owner-types-dropdown" class="dropdown-menu search-filter">
                                 <li ng-repeat="Geardetail in Geardetails | filter: ratingQuery">
                                 <a href data-val="{{filter.gear_category_id.length}}" data="{{filter.gear_category_id}}" ng-click="Rsearch($event)">Rating</a>
                                 </li>
								 
                        </ul>
						

                      </div>
                    </div>
                    <div tabindex="0" class="category-btn top-level btn btn-danger" id="category-btn-3">
                      <div class="dropdown">
                        <div aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="owner-types-dropdown" class="dropdown-toggle search-filter__toggle cursor-pointer"> <span class="filter-label">OWNER TYPE<span class="count"></span> </span> <span class="applied-filter"></span></div>
                        <ul aria-labelledby="owner-types-dropdown" class="dropdown-menu search-filter">
                          <li>
                            <label class="control control--checkbox">
                            <input type="checkbox" onchange="showIndividualOwners(this);" id="show_individual" name="show_individual">
                            Individual
                            <div class="control__indicator"></div>
                            </label>
                          </li>
                          <li>
                            <label class="control control--checkbox">
                            <input type="checkbox" onchange="showRentalHouses(this);" id="show_rental_houses" name="show_rental_houses">
                            Rental Houses
                            <div class="control__indicator"></div>
                            </label>
                          </li>
                          <li>
                            <label class="control control--checkbox">
                            <input type="checkbox" onchange="showProductionCompanies(this);" id="show_production_companies" name="show_production_companies">
                            Companies
                            <div class="control__indicator"></div>
                            </label>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <div tabindex="0" class="category-btn top-level btn btn-danger" id="category-btn-3">
                      <div class="dropdown">
                        <div aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="owner-types-dropdown" class="dropdown-toggle search-filter__toggle cursor-pointer"> <span class="filter-label">KIT TYPE<span class="count"></span> </span> <span class="applied-filter"></span></div>
                        <ul aria-labelledby="owner-types-dropdown" class="dropdown-menu search-filter">
                          <li> <a href="#">gbhbnbnbnbn</a> </li>
                        </ul>
                      </div>
                    </div>
                    <div tabindex="0" class="category-btn top-level btn btn-danger" id="category-btn-3">
                      <div class="dropdown">
                        <div aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="owner-types-dropdown" class="dropdown-toggle search-filter__toggle cursor-pointer"> <span class="filter-label">BRAND<span class="count"></span> </span> <span class="applied-filter"></span></div>
                        <ul aria-labelledby="brands-dropdown" class="dropdown-menu search-filter">
                          <form method="get" data-remote="true" accept-charset="UTF-8" action="/brand_search" class="simple_form /brand_search" novalidate="" id="brand_search_form">
                            <input type="hidden" value="✓" name="utf8">
                            <input type="hidden" value="" id="brand-search-selected-brands-field" name="selected_brands">
                            <input type="text" onkeyup="debouncedBrandSearch();" class="sk-form__text-input" placeholder="Search Brand" value="" id="brand-search-field" name="brand_search">
                          </form>
                          <div id="brands-dropdown-options">
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, '360 Systems')" id="select_brand_360 Systems" name="select_brand_360 Systems">
                              360 Systems
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, '360Heros')" id="select_brand_360Heros" name="select_brand_360Heros">
                              360Heros
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, '360RIZE')" id="select_brand_360RIZE" name="select_brand_360RIZE">
                              360RIZE
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, '3DR')" id="select_brand_3DR" name="select_brand_3DR">
                              3DR
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, '3DRobotics')" id="select_brand_3DRobotics" name="select_brand_3DRobotics">
                              3DRobotics
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'Aadyn Tech')" id="select_brand_Aadyn Tech" name="select_brand_Aadyn Tech">
                              Aadyn Tech
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'Aaton')" id="select_brand_Aaton" name="select_brand_Aaton">
                              Aaton
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'Acebil')" id="select_brand_Acebil" name="select_brand_Acebil">
                              Acebil
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'Acratech')" id="select_brand_Acratech" name="select_brand_Acratech">
                              Acratech
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'Action Products')" id="select_brand_Action Products" name="select_brand_Action Products">
                              Action Products
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'AJA')" id="select_brand_AJA" name="select_brand_AJA">
                              AJA
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                            <li>
                              <label class="control control--checkbox">
                              <input type="checkbox" onchange="setBrandFilter(this, 'AKG')" id="select_brand_AKG" name="select_brand_AKG">
                              AKG
                              <div class="control__indicator"></div>
                              </label>
                            </li>
                          </div>
                        </ul>
                      </div>
                    </div>
                    <div tabindex="0" class="category-btn top-level btn btn-danger" id="category-btn-3">
                      <div class="dropdown">
                        <div aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="owner-types-dropdown" class="dropdown-toggle search-filter__toggle cursor-pointer"> <span class="filter-label">MORE FILTERS<span class="count"></span> </span> <span class="applied-filter"></span></div>
                        <ul aria-labelledby="owner-types-dropdown" class="dropdown-menu search-filter">
                          <li> <a href="#">gbhbnbnbnbn</a> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
   
                </div>
              </div>
            </div>

          </div>
          <div id="not-launched-notifications-section"><!-- Not launched in city yet, but still more than 20 listings -->
            <div class="">
              <div class="col-md-12 rental-period-msg">
                <p><strong>This area is currently in beta.</strong></p>
                <p> You can list gear and do rentals here. However, we haven't fully launched yet, so hang tight until we do an official launch. </p>
              </div>
            </div>
          </div>
          <div class="row map-row"> 
            <!-- LISTING BUTTONS -->
            <div class="col-md-12 search-listing-btns">
              <div id="search-results">
                <div class="row ">
                  <div class="col-xs-12 padding_10">
                    <div class="col-xs-7 col-sm-6 results-counter"> Showing <b>31 - 60</b> of <b>100+</b> results 
                      <!-- For display before a search has been done --> 
                    </div>
                    <div style=" text-align: right;" class="col-xs-5 col-sm-6">
                        <strong>Number of results: </strong>
       
      <select id="limit" name="limit" ng-model="scount">
        <option value="1" ng-selected="true">25</option>
        <option value="2">50</option>
        <option value="3">100</option>
      </select>
                    </div>
                
                  </div>
                </div>



 <div class="row">
                  <div class="col-xs-12  results-sort1">
                     <div class="col-xs-6 col-sm-6 results-sort">
                      <div class="btn-group "> 
					  <button class="btn btn-default btn-sm" id="list"><span class="glyphicon glyphicon-th-list"> </span></button> 
					  <button class="btn btn-default btn-sm" id="grid"><span class="glyphicon glyphicon-th"></span></button> </div>
                    </div>

                    <div class="col-xs-6 col-sm-6 results-sort">
                      <div class="sort-results-control">
                        <div style="text-align:right;" class="dropdown">
						<span aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" id="dropdownMenu1" class="dropdown-toggle"> 
						Sort by <span style="padding:.4rem .1rem;" class="form-underline"> Relevance
						<span class="glyphicon glyphicon-chevron-down smaller"></span> </span> 
						</span>
                          <ul class="dropdown-menu dropdown-menu-right" ng-model="orderProp">
                            <li><span>Best Match or Relevance</span></li>
                            <li ng-click="asc = false"><span>Price: lowest first</span></li>
                            <li ng-click="asc = true"><span>Price: highest first</span></li>
                            <li><span>Distance: nearest first</span></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                   
                  </div>
                </div>

                <div id="normal-results-section">
				
                  <div class="row">
                    <div style="display:none" class="col-md-12" id="gridview">
					 
                      <div ng-repeat="Geardetail in filterData = (Geardetails | filter : {itemName: filterText}) | limitTo:scount:scount*(page-1) |orderBy: orderProp:asc"  class="listing_view">
                        <div class="image_list"><img class="img-responsive" src="{{Geardetail.gear_display_image}}"></div>
                        <div class="listing_info">
                          <h4 class="listing-btn-name"> {{Geardetail.gear_name }}
                            <div class="user-info-container user-info-container10"> 
                              
                              <!-- Owner Image --> 
                              <a href="#" target="_self"> <img src="{{Geardetail.user_profile_picture_link}}" alt="" class="listing-owner-img"> </a> 
                              <!-- Owner Info Container -->
                              <div class="listing-owner-info-container"> 
                                <!-- Owner Name -->
                                <p class="listing-owner-name"> <a href="#" target="_self">{{Geardetail.app_user_first_name}}</a> </p>
                                <!-- Stars -->
                                <div class="listing-owner-rating">
                                  <div class="rating-stars">
									<span>
										<!--{{rating.current}} out of{{rating.max}}-->
										<div class="disable" star-rating rating-value="Geardetail.rating" max="5"></div>
									</span>
								  </div>
                                </div>
                              </div>
                              <!-- END Owner Info Container --> 
                              
                              <!-- Favorite --> 
                              
                            </div>
                          </h4>
                          <h5 class="listing-btn-price listing_design"> {{Geardetail.per_day_cost_aud_inc_gst}} <span class="per-day">per day cost in GST</span> </h5>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
				  
<div  id="listview" class="row search-row-of-listings" style="display: flex;">
<div class="listing-btn-container" ng-repeat="Geardetail in filterData = (Geardetails | filter : {itemName: filterText}) | limitTo:scount:scount*(page-1) |orderBy: orderProp:asc">
                      <div class="listing-btn listing-143317" "=""> 
                        
                        <!-- Image Container -->
                        <div class="fill"> <a target="_self" href="#">
						<img class="listing-btn-image center-block " alt="Rent Canon EF-S 18-135mm f/3.5-5.6 IS" src="{{Geardetail.gear_display_image}}"> </a></div>
                        <!-- END Image Container --> 
                        
                        <!-- Info Bar -->
                        <div class="listing-btn-info"> 
                          
                          <!-- Gear Info Container Link --> 
                          <a class="gear-info-container-link" target="_self" href="#"> 
                          <!-- Gear Info Container -->
                          <div class="gear-info-container"> 
                            
                            <!-- Item Name -->
                            <h4 class="listing-btn-name"> {{Geardetail.gear_name }} </h4>
                            
                            <!-- Distance --> 
                            <!--    --> 
                            
                            <!-- Price -->
                            <h5 class="listing-btn-price"> {{Geardetail.per_day_cost_aud_inc_gst}} <span class="per-day">per day cost in GST</span> </h5>
                          </div>
                          <!-- END Gear Info Container --> 
                          </a> <!-- END Gear Info Container Link --> 
                          
                          <!-- User Info Container -->
                          <div class="user-info-container"> 
                            
                            <!-- Owner Image --> 
                            <a target="_self" href="#"> <img class="listing-owner-img" alt="{{Geardetail.app_user_first_name}}" src="{{Geardetail.user_profile_picture_link}}"> </a> 
                            <!-- Owner Info Container -->
                            <div class="listing-owner-info-container"> 
                              <!-- Owner Name -->
                              <p class="listing-owner-name"> <a target="_self" href="#">{{Geardetail.app_user_first_name}}</a> </p>
                              <!-- Stars -->
                              <div class="listing-owner-rating">
                                <div class="rating-stars"> 
								 <span>
									<!--{{rating.current}} out of{{rating.max}}-->
									<div class="disable" star-rating rating-value="Geardetail.rating" max="5"></div>
								 </span>
								</div>
                              </div>
                            </div>
                            <!-- END Owner Info Container --> 
                            
                            <!-- Favorite --> 
                            
                          </div>
                          <!-- END User Info Container --> 
                          
                        </div>
                        <!-- END Bottom Info Bar --> 
                        
                      </div>
                      
                      <!-- Consider additional listings --> 
                      

</div>
					  
					  
					  
					  
					  
					  
					  
					  
					  
                </div>
              </div>
            </div>
          </div>
          <div class="row each_row">
            <div class="back-to-top text-center"><a id="return-to-top">⬆ Back to the Top</a></div>
            <div id="infinite-scrolling">
              <div class="text-center col-xs-12">
			 
                <div class="pagination1">
				 <uib-pagination class="pagination-sm pagination" total-items="filterData.length" ng-model="scount"
			     ng-change="pageChanged()" previous-text="← Previous" next-text="Next →" items-per-page=1></uib-pagination>            
                  
				 </div>
              </div>
            </div>
          </div>
          <div class="hidden-xs row">
            <footer class="application-footer-search hidden-xs">
              <div class="application-footer-links">
                <div class="space-above-40">
                  <div class="row">
                    <div class="col-sm-3 hidden-xs">
                      <ul>
                        <li>
                          <h4 class="footer-header">ABOUT Kitshare</h4>
                        </li>
							  <li><a class="footer-link" href="#about">What is KitSplit?</a></li>
							  <li><a class="footer-link" href="#Terms">Terms and Conditions</a></li>
							  <li><a class="footer-link" href="#Trust">Trust &amp; Safety</a></li>
							  <li><a class="footer-link" href="#Insurance">Insurance</a></li>
							  <li><a class="footer-link" href="#Privacy">Privacy Policy</a></li>
							  <li><a class="footer-link" href="#OwnerPolicy">Owner's Protection Policy</a></li>
                      </ul>
                    </div>
                    <div class="col-sm-4  hidden-xs">
                      <ul>
                        <li>
                          <h4 class="footer-header">CONNECT</h4>
                        </li>
                        <li><a href="#Contact" class="footer-link">Contact Us</a></li>
                        <li><a href="#" class="footer-link">Blog</a></li>
                        <li>
                          <hr class="footer-hr">
                        </li>
                        <li><a href="#" class="footer-link text-white">Need help finding gear?</a></li>
                        <li>
                          <p class="footer-text text-white">Call Us:</p>
                        </li>
                        <li><a href="#" class="footer-link text-yellow">917.856.7125</a></li>
                      </ul>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-5 hidden-xs">
                      <ul>
                        <li><!-- Begin MailChimp Signup Form -->
                          
                          <ul class="footer_link">
                            <li>
                              <h4 class="footer-header">STAY UP TO DATE!</h4>
                            </li>
                            <li><!-- Begin MailChimp Signup Form -->
                              
                              <div id="mc_embed_signup">
                                <div class="input-group input-group12"> <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                  <input type="text" placeholder="email" value="" name="username" class="form-control email_design" id="login-username">
                                </div>
                                <input type="submit" class="btn btn-success12 hvr-wobble-horizontal" id="mc-embedded-subscribe" name="subscribe" value="SUBSCRIBE">
                              </div>
                              <!--End mc_embed_signup--></li>
                            <li style="margin-top: 1.2em;"></li>
                          </ul>
                          <!--End mc_embed_signup--></li>
                        <li style="margin-top: 1.2em;">
                          <p class="footer-text">&copy;2018 KitShare. <span>All rights reserved</span></p>
                        </li>
                      </ul>
                    </div>
                    <div class="col-xs-12 visible-xs hidden-sm hidden-md hidden-lg hidden-xl">
                      <div class="mobile text-center">
                        <p class="mobile-footer-p"></p>
                        <h4 class="footer-header">ABOUT Kitshare</h4>
                        <p class="mobile-footer-p"><a href="#about" class="footer-link">What is Kitshare?</a></p>
                        <p class="mobile-footer-p"><a href="#Terms" class="footer-link">Terms and Conditions</a></p>
                        <p class="mobile-footer-p"><a href="#Trust" class="footer-link">Trust &amp; Safety</a></p>
                        <p class="mobile-footer-p"><a href="#Insurance" class="footer-link">Insurance</a></p>
                        <p class="mobile-footer-p"><a href="#Privacy" class="footer-link">Privacy Policy</a></p>
                        <p class="mobile-footer-p"><a href="#OwnerPolicy" class="footer-link">Owner's Protection Policy</a></p>
                        <p class="mobile-footer-p"></p>
                        <h4 class="footer-header">CONNECT</h4>
                        <p></p>
                        <p class="mobile-footer-p"><a href="#Contact" class="footer-link">Contact Us</a></p>
                        <p class="mobile-footer-p"><a href class="footer-link">Blog</a></p>
                        <p class="mobile-footer-p"></p>
                        <hr class="footer-hr" style="width: 50%;">
                        <p></p>
                        <p class="mobile-footer-p"><a href class="footer-link text-white">Need help finding gear?</a></p>
                        <p class="mobile-footer-p"></p>
                        <p class="footer-text text-white">Call Us:</p>
                        <p></p>
                        <p class="mobile-footer-p"><a href class="footer-link text-yellow">917.225.7456</a></p>
                        <p class="mobile-footer-p"></p>
                        <h4 class="footer-header">STAY UP TO DATE!</h4>
                        <p class="mobile-footer-p"></p>
                        <p class="footer-text">Join the Kitshare mailing list:</p>
                        <p></p>
                        <p class="mobile-footer-p"><!-- Begin MailChimp Signup Form --> 
                          
                        </p>
                        <ul class="footer_link">
                          <li>
                            <h4 class="footer-header">STAY UP TO DATE!</h4>
                          </li>
                          <li><!-- Begin MailChimp Signup Form -->
                            
                            <div id="mc_embed_signup">
                              <div class="input-group input-group12"> <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                <input type="text" placeholder="email" value="" name="username" class="form-control email_design" id="login-username">
                              </div>
                              <input type="submit" class="btn btn-success12 hvr-wobble-horizontal" id="mc-embedded-subscribe" name="subscribe" value="SUBSCRIBE">
                            </div>
                            <!--End mc_embed_signup--></li>
                          <li style="margin-top: 1.2em;"></li>
                        </ul>
                        <!--End mc_embed_signup-->
                        <p></p>
                        <p style="margin-top: 1.2em;"></p>
                        <p class="footer-text">&copy;2018 KitShare. <span>All rights reserved</span></p>
                        <p></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </footer>
          </div>
        </div>
        <div class="space-above-20 visible-xs visible-sm"></div>
        
        <!-- ********* RIGHT COLUMN ******** -->
        <div class=""> 
          <!-- MAP -->
          <div class="hidden-xs hidden-sm col-md-4 no-padding"> 
            <!-- map goes here -->
            <div id="map-container">
              <div style="position: relative; overflow: hidden;" id="map-canvas-search">
                <iframe width="100%" height="100%" frameborder="0" allowfullscreen="" style="border:0" src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d14734.403370661663!2d88.39675335!3d22.5940276!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1520326749979"></iframe>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>