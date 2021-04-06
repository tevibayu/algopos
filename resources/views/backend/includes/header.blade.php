          <!-- Main Header -->
          <header class="main-header">

            <!-- Logo -->
            <a href="{!!route('home')!!}" class="logo" style="font-weight: 600;"><img src="{{ asset("favicon.png") }}" width="32" height="auto" style="margin-top: -4px;line-height: 32px; padding: 8px;">{{ app_name() }}</a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
              <!-- Sidebar toggle button-->
              <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">{{ trans('labels.toggle_navigation') }}</span>
              </a>
              <!-- Navbar Right Menu -->
              <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                
                  
                  <!-- User Account Menu -->
                  <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <!-- The user image in the navbar-->
                        <?php
                            $my_photo_profile = access()->user()->photo != NULL && file_exists(base_path(access()->photo_profile_path() . access()->user()->photo)) ? access()->photo_profile_path() . access()->user()->photo : url('public/img/no-photo.png');
                        ?>
                        {!! HTML::image($my_photo_profile, 'photo profile', array('class' => 'user-image')) !!}
                      <!-- hidden-xs hides the username on small devices so only the image appears. -->
                      <span class="hidden-xs">{{ access()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                      <!-- The user image in the menu -->
                      <li class="user-header">
                          {!! HTML::image($my_photo_profile, 'photo profile', array('class' => 'img-circle')) !!}
                        <p>
                          {{ access()->user()->name }}
                          <small>{{ trans('strings.member_since') }} {{ access()->user()->created_at->format('d M Y') }}</small>
                        </p>
                      </li>
                    
                      <!-- Menu Footer-->
                      <li class="user-footer">
                       {{--  <div class="pull-left">
                            <a href="{!! url() !!}" class="btn btn-default btn-flat">{{ trans('navs.home_page') }}</a>
                        </div> --}}
                        <div class="pull-right">
                          <a href="{!!url('auth/logout')!!}" class="btn btn-default btn-flat">{{ trans('navs.logout') }}</a>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </nav>
          </header>

          <style>
            .main-footer {
              padding: 25px !important; 
            }
          </style>
