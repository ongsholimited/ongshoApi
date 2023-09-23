<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{URL::to('/home')}}" class="brand-link bg-light">
      <img src="{{asset('storage/adminlte/dist/img/logo.png')}}" alt="Ongsho" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Ongsho</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          {{-- <img src="{{asset('storage/adminlte/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image"> --}}
          <i class="fas fa-circle mt-2 text-success"></i>
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div>
      <!-- SidebarSearch Form -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{URL::to('/home')}}" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{URL::to('/sms/api-section')}}" class="nav-link">
              <i class="nav-icon fas fa-donate"></i>
              <p>
                Manage Api
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{URL::to('/sms/otp_sms_template')}}" class="nav-link">
              <i class="nav-icon fas fa-donate"></i>
              <p>
                Sms Template
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="nav-icon fas fa-cog"></i>
                <p>
                    Post
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview ml-1">
                <li class="nav-item">
                    <a href="{{URL::to('/news/post-list')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                            Post List
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          Review List
                      </p>
                  </a>
                </li>
               
                
            </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                  Setting
                  <i class="fas fa-angle-left right"></i>
              </p>
          </a>
          <ul class="nav nav-treeview ml-1">
              <li class="nav-item">
                  <a href="{{URL::to('/sms/sms_setting')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>
                          General Setting
                      </p>
                  </a>
              </li>
              
          </ul>
      </li>
        <li class="nav-item">
            <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                LogOut
              </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
          </form>
        </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>