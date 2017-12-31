  <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                   <a class="logo" href="{{route('dashboard')}}">
                        POS </a>
                </div>
                <!-- /Logo -->
                
                <ul class="nav navbar-top-links navbar-right pull-right">
                    
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="javascript:void(0)"><b class="hidden-xs">{{Auth::user()->name}}</b><span class="caret"></span> </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li>
                                <div class="dw-user-box">
                                  
                                    <div class="u-text">
                                        <h4>{{Auth::user()->name}}</h4>
                                        <p class="text-muted">{{Auth::user()->email}}</p></div>
                                </div>
                            </li>
                            
                            <li><a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-menu hidden-xs"></i><i class="ti-close visible-xs"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                <ul class="nav" id="side-menu">
                    <li class="user-pro">
                        <a href="javascript:void(0)" class="waves-effect"><img src="images/users/varun.jpg" alt="user-img" class="img-circle"> <span class="hide-menu">{{Auth::user()->name}}<span class="fa arrow"></span></span>
                        </a>
                        <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                            
                            <li><a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> <span class="hide-menu">Logout</span></a></li>
                        </ul>
                    </li>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>


                    <li> <a href="{{url('partners')}}" class="waves-effect"> Partner Section </a></li>
                    <li> <a href="javascript:void(0)" class="waves-effect"> C&P Section </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="{{url('companies')}}"><i class=" fa-fw">C</i><span class="hide-menu">Companies</span></a> </li>
                            <li> <a href="{{url('products')}}"><i class=" fa-fw">P</i><span class="hide-menu">Products</span></a> </li>
                        </ul>
                    </li>  

                    
                   

                    <li> <a href="javascript:void(0)" class="waves-effect"> Purchases Section </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="{{url('purchases')}}"><i class=" fa-fw">P</i><span class="hide-menu">Purchases</span></a> </li>
                            <li> <a href="{{url('purchases/history')}}"><i class=" fa-fw">H</i><span class="hide-menu">History</span></a> </li>
                            <li> <a href="{{url('purchase/edit')}}"><i class=" fa-fw">E</i><span class="hide-menu">Edit & Delete</span></a> </li>
                            
                        </ul>
                    </li> 
                    <li> <a href="javascript:void(0)" class="waves-effect"> Sale Section </a>
                        <ul class="nav nav-second-level">
                            <li> <a href="{{url('sale')}}"><i class=" fa-fw">S</i><span class="hide-menu">Sale</span></a> </li>
                            <li> <a href="{{url('sale/history')}}"><i class=" fa-fw">H</i><span class="hide-menu">History</span></a> </li>
                            <li> <a href="{{url('sale/edit')}}"><i class=" fa-fw">E</i><span class="hide-menu">Edit & Delete</span></a> </li>
                        </ul>
                    </li>    
                    <li> <a href="{{url('stock')}}" class="waves-effect"> Stock Section </a></li>
                    <li> <a href="{{url('movements')}}" class="waves-effect"> Movement Section </a></li>
                    

                </ul>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->