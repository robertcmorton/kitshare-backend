<section class="dashboard_body" ng-init="game_init_info()" ng-controller="GameBasicController">
   <div class="container-fluid">
   <div class="row">
      <div class="info page-info">
         <div class="prof-wrapper">
            <div class="row1">
               <div class="col-md-10 col-md-offset-1">
                  <div class="prof-lg">
                     <main class="prof-lg__primary-col">
                        <section class="prof-lg__primary-info">
                           <div class="prof-lg__primary-info__name-follow">
                              <img class="avatar-img img-responsive" alt="" src="{{user_profile_picture_link}}">
                           </div>
                           <div class="prof-lg__primary-info-__about">
                              <!--="" user="" name,="" title,="" badges,="" current="" location,="" and="" member="" status="" --="">
                                 <div class="prof-user-heading">
                                   <!-- Name -->
                              <h1 class="prof-user-heading__name">
                                 {{app_user_first_name}}   {{app_user_last_name}}
                              </h1>
                              <!-- Title -->
                              <!-- Location & Status -->
                              <h3 class="prof-user-heading__location-status">
                                 <span class="joined-since">Member since {{formattedDate}}</span>
                              </h3>
                              <div class="prof-user-about">
                                 <div class="prof-user-about__verified-info sk-message--text-black">
                                    <h4>
                                       Verified Information
                                       <div class="badge--vetted-user--mini">
                                          <i class="fa fa-certificate">
                                          <i class="fa fa-check"></i>
                                          </i>
                                       </div>
                                    </h4>
                                    <div class="prof-user-about__verified-check">
                                       <i class="fa {{is_active}}"></i>
                                       <span>Email Address</span>
                                    </div>
                                    <div class="prof-user-about__verified-check">
                                       <i class="fa {{is_active}}"></i>
                                       <span>Name</span>
                                    </div>
                                    <!-- TODO: Use new/pending Address work here-->
                                 </div>
                                 <!-- Bio -->
                                 <p class="prof-user-about__bio"></p>
                                 <!-- Web Presence -->
                              </div>
                              <div class="prof-user-heading__badges">
                                 <!-- Super User 100+ Rentals Badge
                                    Product needs to finalize what kind of badges we want
                                    -->
                              </div>
                           </div>
                           <!-- Verified info, bio, and web presence -->
                        </section>
                        <section class="prof-lg__listings">
                           <!-- Listings -->
                           <div class="prof-user-listings">
                              <div class="prof-user-listings__heading">
                                 <h4>Listings <span>({{ (listings | filter: gear_name).length }})</span></h4>
                                 <div class="sk-form__search-bar prof-user-listings__heading__search">
                                    <div class="input-icon text-dark-grey input-icon1">
                                       <i class="fa fa-search"></i>
                                       <div id="bloodhound" class="navbar__search__inputs">
                                          <input ng-model="gear_name" name="filters[search]" id="search-field" value="" placeholder="What are you looking for?"class="typeahead nav-search splash-search-form form-underline tt-input change_color"autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="sk-zero-message" id="Listings0" style="display:none;">
                                 <h5><a href="/add-listing">Add your gear!</a></h5>
                              </div>
                              <div class="category-buttons" ng-model="query">
                                 <button data="{{filter.gear_category_id}}" ng-click="search($event)" data-id="1" class="category-buttons__button--active">
                                 All Categories&nbsp;({{ (filters | filter: gear_name).length }})
                                 </button>
                                 <button data-val="{{filter.gear_category_id.length}}" data="{{filter.gear_category_id}}" ng-click="search($event)" ng-repeat="filter in filters | filter: searchQuery" data-id="9" class="category-buttons__button">
                                 {{filter.gear_category_name}}({{filter.gear_category_id.length}})
                                 </button>
                              </div>
                              <div id="listing-btns" class="prof-user-listings__wrapper">
                                 <div class="col-xs-12 col-sm-4 listing-btn-container" ng-repeat="listing in listings | filter:gear_name | orderBy: 'gear_name' ">
                                    <div class="listing-btn-container">
                                       <div class="listing-btn listing-143833">
                                          <!-- Image Container -->
                                          <div class="fill">
                                             <a href="#/" target="_self">
                                             <img src="{{listing.gear_display_image}}" alt="{{listing.gear_name}}" class="listing-btn-image center-block ">
                                             </a>
                                          </div>
                                          <!-- END Image Container -->
                                          <!-- Info Bar -->
                                          <div class="listing-btn-info">
                                             <!-- Gear Info Container Link -->
                                             <a href="/rent/canon-g7-x-san-francisco-ca" target="_self" class="gear-info-container-link">
                                                <!-- Gear Info Container -->
                                                <div class="gear-info-container">
                                                   <!-- Item Name -->
                                                   <h4 class="listing-btn-name">
                                                      {{listing.gear_name}}
                                                   </h4>
                                                   <!-- Distance -->
                                                   <!--    -->
                                                   <!-- Price -->
                                                   <h5 class="listing-btn-price">
                                                      {{listing.per_day_cost_aud_in_gst}}
                                                      <span class="per-day">per day in Gst</span>
                                                   </h5>
                                                </div>
                                                <!-- END Gear Info Container -->
                                             </a>
                                             <!-- END Gear Info Container Link -->
                                             <!-- User Info Container -->
                                             <div class="user-info-container">
                                                <!-- Owner Image -->
                                                <a href="/dylan-n-alamo-square-san-francisco-ca-1473" target="_self">
                                                <img src="{{user_profile_picture_link}}" alt="Dylan Nord Avatar" class="listing-owner-img">
                                                </a>
                                                <!-- Owner Info Container -->
                                                <div class="listing-owner-info-container">
                                                   <!-- Owner Name -->
                                                   <p class="listing-owner-name">
                                                      <a href="/dylan-n-alamo-square-san-francisco-ca-1473" target="_self">{{app_user_first_name}}</a>
                                                   </p>
                                                   <!-- Stars -->
                                                   <div class="listing-owner-rating">
                                                      <div class="rating-stars">
                                                         <span ng-repeat="rating in ratings">
                                                            <!--{{rating.current}} out of{{rating.max}}-->
                                                            <div class="disable" star-rating rating-value="rating.current" max="rating.max" on-rating-selected="getSelectedRating(rating)"></div>
                                                         </span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <!-- END Owner Info Container -->
                                                <!-- Favorite 
                                                   <div class="fave-listing-container">
                                                   <div data-listing-id="143833" class="favorites-shortcut-icons">
                                                   <div class="favorite-heart">
                                                   <a class="listing-fav-icon-link">
                                                   <i class="fa fa-heart-o"></i>
                                                   <span class="within-lists-count hidden">0</span>
                                                   </a>
                                                   </div>
                                                   <a aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" id="user-lists-dropdown" class="listing-fav-dropdown-toggle">
                                                   <span class="caret"></span>
                                                   </a>
                                                   <ul aria-labelledby="user-lists-dropdown" class="dropdown-menu listing-faves-menu">
                                                   <div id="user-lists" class="user-lists">
                                                      <div data-list-id="26832" class="fav-item">
                                                   	  <a href="/lists/favorites-2b482417-c2dc-42f1-a161-81adc0dd0094" class="list-name">Favorites</a>
                                                   	  <a id="favorite-link">
                                                   	  <i class="fa fa-heart-o"></i>
                                                   	  </a>
                                                      </div>
                                                   </div>
                                                   <label class="sk-form__input-wrapper create-new-list-container">
                                                   <input type="text" class="sk-form__text-input new-list-name" placeholder="My New List" id="list_name" name="list_name">
                                                   <span class="sk-form__input-label">Add to a new List</span>
                                                   <button id="save-to-new-list" class="create-new-list-btn">+</button>
                                                   </label>
                                                   </ul>
                                                   </div>
                                                   </div>-->
                                             </div>
                                             <!-- END User Info Container -->
                                          </div>
                                          <!-- END Bottom Info Bar -->
                                       </div>
                                       <!-- Consider additional listings -->
                                    </div>
                                 </div>
                              </div>
                        </section>
                        <section id="reviews-section" class="prof-lg__reviews">
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
                        <div  class="user-rating__body"><p>{{review.cust_gear_review_desc}}</p></div>
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
                        <section id="references-section" class="prof-lg__references">
                        <div class="prof-user-endorsements">
                        <div class="prof-user-endorsements__heading">
                        <div class="prof-user-endorsements__heading__title">
                        <h4>
                        References
                        </h4>
                        </div>
                        </div>
                        <!-- Leave Endorsements -->
                        <div class="modal fade" id="leaveEndorsementModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <div class="row">
                        <div class="col-md-12">
                        <h3>Write a reference for Monojit Mondal</h3>
                        <p>
                        Tell us about how you know Monojit Mondal and why they'd be a great renter or owner on KitSplit.
                        </p>
                        </div>
                        </div>
                        <div class="panel panel-default endorsement">
                        <div class="space-above-10"></div>
                        <div id="new-endorsement-alert" class="msg-alert hidden">
                        An error occurred with your reference. Our apologies.
                        </div>
                        <div class="new-review panel-default">
                        <form id="endorsement-form" novalidate class="simple_form edit_user" action="/" accept-charset="UTF-8" method="post"><input name="utf8" type="hidden" value="✓"><input type="hidden" name="authenticity_token" value="U/OfO/eThzBslCqTRppPygNFIovtwFjGNxrjf77DQZF0wPmGW8ZKXRtS31OThqFSV3I/3iEydckkMZ3uIJxdDg==">
                        <textarea id="endorsement-text" class="text required form-control" placeholder="Write your reference here" name="user[endorsements][content]"></textarea>
                        <input value="26574" type="hidden" name="user[endorsements][receiver_id]" id="user_endorsements_receiver_id">
                        <input value="26574" type="hidden" name="user[endorsements][author_id]" id="user_endorsements_author_id">
                        </form> 
                        <div class="new-review-stars">
                        <button type="button" class="btn btn-default btn-sm" onclick="submitEndorsementIfValid(26574)">Submit</button>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div> 
                        <div class="sk-message-box leave-endorsement-message-box">
                        <h6>
                        You don't
                        have any references yet.
                        </h6>
                        <p>
                        <a href="/ask-for-references">Request one now!</a>
                        </p>
                        </div>
                        </div>
                        </section>
                     </main>
                     <aside class="prof-lg__secondary-col">
                     <!-- User stats, followers, and videos -->
                     <div class="prof-user-sidebar">
                     <!-- Edit profile button. Visible for Admin OR if this is your profile -->
                     <div class="prof-user-sidebar__ADMIN__edit-button-container">
                     <a class="sk-button--size-lg dashboard-button-link  hvr-float-shadow" href="#Editprofile">
                     <i class="fa fa-pencil-square-o"></i>
                     Edit Profile
                     </a>    </div>
                     <!-- ADMIN ONLY --> 
                     <div class="prof-user-sidebar__ADMIN">
                     </div>
                     <!-- User Stats -->
                     <div class="prof-user-sidebar__stats">
                     <div class="prof-user-sidebar__stats__stat">
                     <a data-role="none" href="#reviews-section">
                     <img class="stat-img" alt="Reviews" src="https://s3.amazonaws.com/kitsplit/img/icons/reviews.png">
                     </a>
                     <div class="stat__text">
                     <h5 class="stat__text__primary">
                     <a data-role="none" href="#reviews-section">New User</a>
                     </h5>
                     <p class="stat__text__secondary">
                     <a data-role="none" href="#reviews-section">
                     No Reviews Yet
                     </a>
                     </p>
                     </div>
                     </div>
                     </div>
                     <!-- Followers & Following -->
                     <div class="prof-user-sidebar__follow-stats">
                     <div class="prof-user-sidebar__follow-stats__followers">
                     <h5>
                     <a data-toggle="modal" data-target="#follows-modal" class="cursor-pointer">
                     0 Followers 
                     </a>
                     </h5>
                     </div>
                     <div class="prof-user-sidebar__follow-stats__following">
                     <h5>
                     <a data-toggle="modal" data-target="#follows-modal" class="cursor-pointer">
                     0 Following
                     </a>
                     </h5>
                     </div>
                     </div>
                     </div>
                     </aside>
                     </div>
                     <input type="hidden" id="profile-id" value="26574">
                  </div>
               </div>
               <!-- FOLLOWERS MODAL -->
               <div class="modal fade follows-modal" id="follows-modal" tabindex="-1" role="dialog" aria-labelledby="follows-modal" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                        <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                           <ul class="modal-nav nav nav-pills">
                              <li class="nav-item active">
                                 <a href="#followers-content" data-toggle="tab" role="tab" class="nav-link active">
                                 Followers (<strong>0</strong>)
                                 </a>
                              </li>
                              <li class="nav-item">
                                 <a href="#following-content" data-toggle="tab" role="tab" class="nav-link">
                                 Following (<strong>0</strong>)
                                 </a>
                              </li>
                           </ul>
                        </div>
                        <div class="modal-body">
                           <div class="tab-content">
                              <div class="tab-pane fade in active" id="followers-content" role="tabpanel">
                                 <!--TODO: Add No results state-->
                                 <div class="row">
                                    <div class="col-xs-12">
                                    </div>
                                 </div>
                              </div>
                              <div class="tab-pane fade" id="following-content" role="tabpanel">
                                 <!--TODO: Add No results state-->
                                 <div class="row">
                                    <div class="col-xs-12">
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
