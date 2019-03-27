<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
    <title>@yield('title') | e-Pelayanan Kelurahan</title>
    <!-- Bootstrap Core CSS -->
    <link href="/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->

    <link href="/css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="/css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="/css/helper.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    @yield('styles')
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">
                        <!-- Logo icon -->
                        <b><img src="/images/logo_bandung.png" /></b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>e-Pelayanan Kelurahan</span>
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0 pull-right">
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/images/users/5.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated fadeIn">
                                <ul class="dropdown-user">
                                    <!-- <li><a href="#"><i class="ti-user"></i> Profile</a></li>
                                    <li><a href="#"><i class="ti-wallet"></i> Balance</a></li>
                                    <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                                    <li><a href="#"><i class="ti-settings"></i> Setting</a></li> -->
                                    <li><a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fa fa-power-off"></i> Logout
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        @if (Auth::user()->lvl_user != 3)
                            <li class="nav-devider"></li>
                            <li> <a href="/"><i class="fa fa-dashboard"></i> <span class="hide-menu">Dashboard</span></a>
                            </li>
                            <li> <a href="/layanan"><i class="fa fa-handshake-o"></i> <span class="hide-menu">Layanan</span></a>
                            </li>
                            <li> <a href="/permohonan"><i class="fa fa-file-text"></i> <span class="hide-menu">Permohonan</span></a>
                            </li>
                            <li> <a href="/pengaduan"><i class="fa fa-exclamation-triangle"></i> <span class="hide-menu">Pengaduan</span></a>
                            </li>
                            <li> <a href="/warga"><i class="fa fa-users"></i> <span class="hide-menu">Data Warga</span></a>
                            </li>
                            <li> <a href="/arsip"><i class="fa fa-archive"></i> <span class="hide-menu">Arsip</span></a>
                            </li>
                            @if (Auth::user()->lvl_user == 1)
                            <li> <a href="/verifikasi"><i class="fa fa-check"></i> <span class="hide-menu">Verifikasi Surat</span></a>
                            </li>
                            <li> <a href="/petugas"><i class="fa fa-user"></i> <span class="hide-menu">Petugas</span></a>
                            </li>
                            @endif
                        @else
                            <li class="nav-devider"></li>
                            <li> <a href="/verifikasi"><i class="fa fa-check"></i> <span class="hide-menu">Verifikasi Surat</span></a>
                            </li>
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Container fluid  -->
            <div class="container-fluid">
                
                @yield('content')

                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
            <!-- footer -->
            <footer class="footer"> © 2018 All rights reserved
            <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="/js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="/js/lib/bootstrap/js/popper.min.js"></script>
    <script src="/js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="/js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="/js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->

    <script src="/js/lib/calendar-2/moment.latest.min.js"></script>
    <!-- scripit init-->
    <script src="/js/lib/calendar-2/semantic.ui.min.js"></script>
    <!-- scripit init-->
    <script src="/js/lib/calendar-2/prism.min.js"></script>
    <!-- scripit init-->
    <script src="/js/lib/calendar-2/pignose.calendar.min.js"></script>
    <!-- scripit init-->
    <script src="/js/lib/calendar-2/pignose.init.js"></script>

    <script src="/js/lib/owl-carousel/owl.carousel.min.js"></script>
    <script src="/js/lib/owl-carousel/owl.carousel-init.js"></script>
    <script src="/js/scripts.js"></script>
    <!-- scripit init-->

    <script src="/js/custom.min.js"></script>

    @yield('scripts')

</body>

</html>