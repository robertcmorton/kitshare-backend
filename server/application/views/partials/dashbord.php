<section class="dashboard_body">
   <div class="container-fluid" ng-controller="TabController">
      <div class="row">
         <div class="col-md-12">
		 

            <ul class="jtab-trigger jtab-ul">
               <li ng-class="{ active: isSet(1) }">
                  <a href ng-click="setTab(1)">Rentals</a>
               </li>
               <li ng-class="{ active: isSet(2) }">
                  <a href ng-click="setTab(2)">Invoices</a>
               </li>
               <li ng-class="{ active: isSet(3) }">
                  <a href ng-click="setTab(3)">References</a>
               </li>
               <li ng-class="{ active: isSet(4) }">
                  <a href ng-click="setTab(4)">Reviews</a>
               </li>
            </ul>
            <div class="jtab-content-list">
			
               <div id="tab2" ng-show="isSet(2)">
                  <div class="row">
                     <div class="col-xs-12">
					 
                        <div class="dashboard-header-bar renter">
                           <div class="switch-view-section renter">
                              <p class="text-center">Renter dashboard</p>
                              <div class="space-above-20"></div>
                              <a href="/monojit/dashboard?role=owner">
                              <button type="button" class="btn btn-blue-bg">Switch to owner view</button>
                              </a>        
                           </div>
                           <div class="profile-section renter">
                              <a href="/profile/monojit/edit">
                                 <span class="dashboard-profile">
                                    <span class="profile-image inline-block">
                                    <img class="img-circle avatar-img" src="{{user_profile_picture_link}}" alt="Picture?1518416060">
                                    <span class="text-content">Edit</span>
                                    </span>
                                    <span class="profile-text inline-block">
                                       <h2>
                                          Welcome, {{app_user_first_name}}!
                                          <span class="smaller">
                                          </span>
                                          <!-- <small>@Monojit</small> -->
                                       </h2>
                                    </span>
                                 </span>
                              </a>
                           </div>
                           <div class="subnav">
                              <ul>
                                 <li>
                                    <a class="active" href="#">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    All rentals
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-time"></span>
                                    Awaiting approval
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-phone"></span>
                                    Owner Contacted
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    Upcoming
                                    </a>            
                                 </li>
                                 <li>
                                    <span class="glyphicon glyphicon-resize-horizontal"></span>
                                    <a href="#">
                                    In progress
                                    </a>            
                                 </li>
                                 <li>
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <a href="#">
                                    Completed
                                    </a>            
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="tab3" ng-show="isSet(3)">
                  <h2>Mauris quis porta elit.</h2>
                  <p>Mauris quis porta elit. Ut et egestas nisi. Fusce fringilla leo tellus. Pellentesque ullamcorper interdum
                     justo, eget placerat enim accumsan et. Ut eleifend, metus sit amet pellentesque faucibus, metus ante tempor
                     lorem, eget dictum felis massa at elit. Nullam vitae malesuada lacus. Aenean sem dolor, condimentum sed
                     sodales at, condimentum at neque. Vivamus id sem turpis, id iaculis est. Mauris egestas mauris et sem
                     faucibus eu viverra justo accumsan. Vestibulum sagittis suscipit ligula, eu luctus enim interdum ornare. Sed
                     non mauris purus, nec hendrerit odio. Duis a mauris sed diam pulvinar auctor. Proin non est velit. Ut varius
                     volutpat pharetra.
                  </p>
                  <p>Pellentesque quis mi justo. Fusce eu nibh libero. Vestibulum nunc mauris, varius eu mollis nec, interdum at
                     lorem. Ut posuere justo ac dui facilisis eleifend. Mauris nec justo ac urna blandit accumsan ac eu ipsum.
                     Aliquam erat volutpat. Curabitur vitae lectus et ante volutpat rhoncus quis ut tortor. Fusce vel lacus id
                     ipsum ornare convallis. Cras faucibus tellus vel leo sodales porta. Maecenas a neque nibh.
                  </p>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed malesuada mollis odio eu ornare. Cras pharetra
                     nunc massa. Cras mollis massa nec mauris blandit blandit. Curabitur at eros lectus, at iaculis sem. Duis
                     pharetra, neque nec ultrices faucibus, risus risus venenatis nibh, ac feugiat massa sapien et odio. Integer
                     eu dui enim, id dignissim leo. Aenean a nulla nulla. Vivamus accumsan mollis rutrum. Nullam sed facilisis
                     erat.
                  </p>
               </div>
               <div id="tab4"  ng-show="isSet(4)">
                  <div class="row">
                     <div class="col-xs-12">
                        <div class="dashboard-header-bar renter">
                           <div class="switch-view-section renter">
                              <p class="text-center">Renter dashboard</p>
                              <div class="space-above-20"></div>
                              <a href="/monojit/dashboard?role=owner">
                              <button type="button" class="btn btn-blue-bg">Switch to owner view</button>
                              </a>        
                           </div>
                           <div class="profile-section renter">
                              <a href="/profile/monojit/edit">
                                 <span class="dashboard-profile">
                                    <span class="profile-image inline-block">
                                    <img class="img-circle avatar-img" src="{{user_profile_picture_link}}" alt="Picture?1518416060">
                                    <span class="text-content">Edit</span>
                                    </span>
                                    <span class="profile-text inline-block">
                                       <h2>
                                          Welcome, {{app_user_first_name}}!
                                          <span class="smaller">
                                          </span>
                                          <!-- <small>@Monojit</small> -->
                                       </h2>
                                    </span>
                                 </span>
                              </a>
                           </div>
                           <div class="subnav">
                              <ul>
                                 <li>
                                    <a class="active" href="#">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    All rentals
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-time"></span>
                                    Awaiting approval
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-phone"></span>
                                    Owner Contacted
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    Upcoming
                                    </a>            
                                 </li>
                                 <li>
                                    <span class="glyphicon glyphicon-resize-horizontal"></span>
                                    <a href="#">
                                    In progress
                                    </a>            
                                 </li>
                                 <li>
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <a href="#">
                                    Completed
                                    </a>            
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                     <div class="space-above"></div>
                     <div class="prof-user-reviews">
                        <div class="prof-user-reviews__heading">
                           <div class="prof-user-reviews__heading__title">
                              <h4>Reviews <span>(0)</span></h4>
                           </div>
                        </div>
                        <div class="sk-message-box leave-endorsement-message-box">
                           <h6>You don't have any reviews yet.</h6>
                           <p>You'll get your first review after you complete your first rental. Why not <a href="/search">find some gear</a> to rent?</p>
                        </div>
                     </div>
                  </div>
               </div>
               <div id="tab1" ng-show="isSet(1)">
                  <div class="row">
                     <div class="col-xs-12">
					<!--<locationpicker options="locationpickerOptions"></locationpicker>-->
                        <div class="dashboard-header-bar renter">
                           <div class="switch-view-section renter">
                              <p class="text-center">Renter dashboard</p>
                              <div class="space-above-20"></div>
                              <a href="/monojit/dashboard?role=owner">
                              <button type="button" class="btn btn-blue-bg">Switch to owner view</button>
                              </a>        
                           </div>
                           <div class="profile-section renter">
                              <a href="/profile/monojit/edit">
                                 <span class="dashboard-profile">
                                    <span class="profile-image inline-block">
                                    <img class="img-circle avatar-img" src="{{user_profile_picture_link}}" alt="Picture?1518416060">
                                    <span class="text-content">Edit</span>
                                    </span>
                                    <span class="profile-text inline-block">
                                       <h2>
                                          Welcome, {{app_user_first_name}}!
                                          <span class="smaller">
                                          </span>
                                          <!-- <small>@Monojit</small> -->
                                       </h2>
                                    </span>
                                 </span>
                              </a>
                           </div>
                           <div class="subnav">
                              <ul>
                                 <li>
                                    <a class="active" href="#">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                    All rentals
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-time"></span>
                                    Awaiting approval
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-phone"></span>
                                    Owner Contacted
                                    </a>            
                                 </li>
                                 <li>
                                    <a href="#">
                                    <span class="glyphicon glyphicon-arrow-right"></span>
                                    Upcoming
                                    </a>            
                                 </li>
                                 <li>
                                    <span class="glyphicon glyphicon-resize-horizontal"></span>
                                    <a href="#">
                                    In progress
                                    </a>            
                                 </li>
                                 <li>
                                    <span class="glyphicon glyphicon-ok"></span>
                                    <a href="#">
                                    Completed
                                    </a>            
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="dashboard-page">
                     <div class="row">
                        <div class="col-xs-12 col-sm-7">
                           <h3>Your Messages</h3>
                           <div class="panel no-content-yet-msg">
                              <img src="assets/images/discover.png" alt="Discover">
                              <h4>No messages here</h4>
                              <p>
                                 <a href="/search">
                                 But click here to check out some of the cool gear that's been added recently!
                                 </a>    
                              </p>
                           </div>
                        </div>
                        <div class="col-sm-5 hidden-xs">
                           <!-- Deactivate several -->
                           <h3>Tips &amp; Resources</h3>
                           <a href="/search">
                              <div class="tip tip-well">
                                 <img src="assets/images/makesomething.png" alt="Makesomething">
                                 <h3>Make something beautiful!</h3>
                                 <p>We take care of the logistics so you can focus on making great work. Here’s to embracing more opportunities to create and collaborate!</p>
                              </div>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
			
			
         </div>
      </div>
   </div>
</section>
