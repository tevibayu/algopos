          <!-- Left side column. contains the logo and sidebar -->
          <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
              <!-- Sidebar user panel (optional) -->
              <div class="user-panel">
                <div class="pull-left image">
                    <?php
                        $my_photo_profile = access()->user()->photo != NULL && file_exists(base_path(access()->photo_profile_path() . access()->user()->photo)) ? access()->photo_profile_path() . access()->user()->photo : url('public/img/no-photo.png');
                    ?>
                    {!! HTML::image($my_photo_profile, 'photo profile', array('class' => 'img-circle')) !!}
                </div>
                <div class="pull-left info">
                  <p>{{ access()->user()->name }}</p>
                  <!-- Status -->
                  <a href="#"><i class="fa fa-circle text-success"></i> {{ access()->user()->roles[0]['name'] }}</a>
                </div>
              </div>

              <!-- Sidebar Menu -->
              <ul class="sidebar-menu">
                  
                {!! access()->menu() !!}

              </ul><!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
          </aside>
