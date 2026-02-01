<section class="dashboard_body">
   <style>
      label {
      font-weight: 100;
      }
      .btn-success{
      margin-left:1.5%;
      }
   </style>
   <div class="container-fluid">
      <div class="row">
         <div class="col-sm-3">
            <a href="#" class="nav-tabs-dropdown btn btn-block btn-primary">Tabs</a>
            <ul id="nav-tabs-wrapper" class="nav nav-tabs nav-pills nav-stacked well">
               <li ng-class="{ active: isSet(1) }">
                  <a href ng-click="setTab(1)" data-toggle="tab">Profile Info</a>
               </li>
               <li ng-class="{ active: isSet(2) }">
                  <a href ng-click="setTab(2)" data-toggle="tab">Contact Info</a>
               </li>
               <li ng-class="{ active: isSet(3) }">
                  <a href ng-click="setTab(3)" data-toggle="tab">Account Settings</a>
               </li>
               <li ng-class="{ active: isSet(4) }">
                  <a href ng-click="setTab(4)" data-toggle="tab">Bank & Credit Cards</a>
               </li>
               <li ng-class="{ active: isSet(5) }">
                  <a href ng-click="setTab(5)" data-toggle="tab">Insurance</a>
               </li>
               <li ng-class="{ active: isSet(6) }">
                  <a href ng-click="setTab(6)" data-toggle="tab">Photo ID</a>
               </li>
               <li ng-class="{ active: isSet(7) }">
                  <a href ng-click="setTab(7)" data-toggle="tab">Facebook Connect</a>
               </li>
            </ul>
         </div>
         <div class="col-sm-9">
            <div class="tab-content">
               <div role="tabpanel" class="tab-pane fade in active" id="vtab1" ng-show="isSet(1)">
                  <div class="col-xs-12 col-sm-12 space-above-20">
                     <div class="row">
                        <div class="col-xs-12">
                           <a href="#Profile">
                           <button type="button" class="btn btn-success">
                           <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                           View Profile
                           </button>
                           </a>        
                        </div>
                     </div>
                     <br>
                     <div id="profile-info-container" class="col-xs-12 col-sm-12 space-above-20">
                        <div class="profile-info-form">
                           <div class="row">
                              <div class="col-xs-12">
                                 <u>
                                    <h2 style="margin-top: 0 !important;">Profile Info</h2>
                                 </u>
                              </div>
                           </div>
                           <br>
                           <div class="row">
                              <div class="col-xs-12">
                                 <div class="avatar-panel well">
                                    <form id="avatar-dropzone" class="simple_form dropzone dz-clickable" novalidate="novalidate" action="" accept-charset="UTF-8" method="post">
                                       <img ng-model="user.user_profile_picture_link" class="img-circle" id="current-avatar" ng-src="{{user.user_profile_picture_link}}" alt="Picture?1518416060" style="max-height: 10rem;">
                                       <span class="drop-message">
                                          <!--<span id="uploading">
                                             <span class="flower-loader">
                                             Loading…
                                             </span>
                                             <span class="saving-message">Saving new photo </span>
                                             </span>-->
                                       </span>
                                       <span>Click here or drop a new photo to add image.</span>
                                    </form>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">First Name:</label>
                                    <input ng-model="user.app_user_first_name" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">Last Name:</label>
                                    <input ng-model="user.app_user_last_name" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">Username</label>
                                    <input ng-model="user.app_username" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="sel1">I am a</label>
                                    <select  class="form-control" id="sel1">
                                       <option ng-selected="data.unit == item.id" ng-repeat="item in unit"  ng-value="item.id">{{item.label}}</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">Cell number</label>
                                    <input ng-model="user.primary_mobile_number" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="sel1">Profession</label>
                                    <select class="form-control" id="Profession">
                                       <option ng-selected="data.Profession == item.id" ng-repeat="item in Profession"  ng-value="item.id">{{item.label}}</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">Website</label>
                                    <input ng-model="user.user_website" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">Facebook:</label>
                                    <input ng-model="user.facebook_link" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="form-group">
                                    <label for="usr">Twitter</label>
                                    <input ng-model="user.twitter_link" type="text" class="form-control" id="">
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="form-group">
                                    <label for="comment">About me</label>
                                    <textarea ng-model="user.about_me" class="form-control" rows="5" id="comment"></textarea>
                                 </div>
                              </div>
                              <div class="row" id="video-links">
                                 <div class="add-video-link">
                                    <h4>Manage Videos</h4>
                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Another Video">
                                    <i class=" fa fa-plus-square"></i>
                                    </a>
                                 </div>
                              </div>
                              <div class="col-xs-12 ">
                                 <input name="commit" value="Update Profile" id="submit-btn" class="btn btn btn-default" type="submit">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div role="tabpanel" class="tab-pane fade in active" id="vtab2" ng-show="isSet(2)">
			   <div class="col-xs-12 col-sm-12 space-above-20" id="">
                  <div class="row">
                     <div class="col-xs-12">
                        <a href="#Profile">
                        <button type="button" class="btn btn-success">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                        View Profile
                        </button>
                        </a>        
                     </div>
                  </div>
                  <br>
                  <div class="col-xs-12">
                     <u>
                        <h2 style="margin-top: 0 !important;">Contact Info</h2>
                     </u>
                  </div>
                  <br><br>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="comment">Company or home address</label>
                        <input type="text" ng-model="user.street_address" class="form-control"  id="" placeholder="Street"></input><br>
                        <input type="text" ng-model="user.apartment_number" class="form-control"  id="" placeholder="Apt #"></input>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="usr">City</label>
                        <input ng-model="user.gs_state_name" type="text" class="form-control" id="">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="sel1">country</label>
                        <select class="form-control" id="" data-ng-model="country" data-ng-options="country.name for country in countries" data-ng-change="updateCountry()">
                           <option value="">Select country</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="sel1">state</label>
                        <select class="form-control" id="" data-ng-model="state" data-ng-options="state.name for state in availableStates">
                           <option value="">Select state</option>
                        </select>
                     </div>
                  </div>
				  <br>
                  <div class="col-md-4">
                     <div class="form-group">
                        <label for="sel1">Zip Code</label>
                             <input ng-model="user.suburb_postcode" type="text" class="form-control" id="">
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="usr">SSN Last 4 </label>
                        <span title="" data-placement="top" data-toggle="tooltip" class="glyphicon glyphicon-question-sign" data-original-title="We ask for the last 4 digits of your Social Security number in order to help speed up the verification process. We take security very seriously, and all sensitive information is fully encrypted."></span>
                        <input ng-model="user.app_password" type="password" class="form-control" id="" disabled>
                     </div>
                  </div>
                  <div class="col-md-12">
                     <div class="form-group">
                        <label for="sel1">Date of birth</label>
                     </div>
                  
					  <div id="dob-year" class="input-wrapper col-md-4 columns" style="padding-left: 0;">
						<div class="form-group">
						<select class="form-control" name="year" id="year" ng-model="year" ng-options="y for y in years" ng-change="updateDate('year')" onchange="changeMe(this)" required>
						  <option value='' disabled>Year</option>
						</select>
						</div>
					  </div>
					  <div id="dob-month" class="input-wrapper col-md-4 columns">
						<div class="form-group">
						<select class="form-control" name="month" id="month" ng-model="month" ng-options="m.name for m in months | validMonths:year" ng-change="updateDate('month')" onchange="changeMe(this)" required>
						  <option value='' disabled>Month</option>
						</select>
						</div>
					  </div>
					  <div id="dob-day" class="input-wrapper col-md-4 columns" style="padding-right: 0;">
						<div class="form-group">
						<select  class="form-control" name="day" id="day" ng-model="day" ng-options="d for d in days | daysInMonth:year:month | validDays:year:month" ng-change="updateDate('day')" onchange="changeMe(this)" required>
						  <option value='' disabled>Day</option>
						</select>
						</div>
					  </div>
					  </div>
               </div>
			   </div>
               <div role="tabpanel" class="tab-pane fade in active" id="vtab3" ng-show="isSet(3)"> TAB 3
               </div>
               <div role="tabpanel" class="tab-pane fade in active" id="vtab3" ng-show="isSet(4)"> TAB 4
               </div>
               <div role="tabpanel" class="tab-pane fade in active" id="vtab3" ng-show="isSet(5)"> TAB 5
               </div>
               <div role="tabpanel" class="tab-pane fade in active" id="vtab3" ng-show="isSet(6)"> TAB 6
               </div>
               <div role="tabpanel" class="tab-pane fade in active" id="vtab3" ng-show="isSet(7)"> TAB 7
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
