<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>校缘后台系统</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

  {{--   <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="https://cdn.bootcss.com/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/iCheck/1.0.2/skins/all.css"> --}}

    <link rel="stylesheet" href="{{ asset('vendor/bootcss/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/font-awesome/4.5.0/css/font-awesome.min.css">
{{--     <link rel="stylesheet" href="{{ asset('vendor/bootcss/css/font-awesome.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('vendor/bootcss/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/bootcss/css/all.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.bootcss.com/select2/4.0.3/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/iCheck/1.0.2/skins/all.css"> 

    <link rel="stylesheet" href="{{ asset('vendor/datetimepicker/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker-bs3.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datepicke/css/bootstrap-datepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('vendor/adminLTE/css/AdminLTE.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminLTE/css/skins/skin-blue.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script type="text/javascript" src="{{ asset('vendor/bootcss/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/zcjy.js') }}"></script>
    <style type="text/css">
    .skin-blue .main-header .logo,.skin-blue .main-header .navbar , .btn-primary , .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
        background-color: #393D49;
        border-color: white;
    }
    .box.box-primary , .box.box-info , .nav-tabs-custom>.nav-tabs>li.active{
        border-top-color: #393D49;
    }
    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
    background-color: black;
    }
    </style>
    @yield('css')
</head>

<body class="skin-blue sidebar-mini">
@if (auth('admin')->check())
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <b>校缘后台系统</b>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="/images/logo.png"
                                     class="user-image" alt="User Image"/>
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{{-- {!! auth('admin')->user()->name !!} --}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                             {{--    <li class="user-header">
                                    <img src="http://infyom.com/images/logo/blue_logo_150x150.jpg"
                                         class="img-circle" alt="User Image"/>
                                    <p>
                                        {!! auth('admin')->user()->name !!}
                                        <small>Member since {!! auth('admin')->user()->created_at->format('M. Y') !!}</small>
                                    </p>
                                </li> --}}
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{!! route('settings.edit_pwd') !!}" class="btn btn-default btn-flat">
                                            修改密码
                                        </a>
                                       
                                    </div>
                                    <div class="pull-right">
                                        <a href="{!! url('/logout') !!}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                           退出
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Main Footer -->
        <footer class="main-footer" style="max-height: 100px;text-align: center">
            <strong>Copyright © 2018 <a href="http://www.yunlike.cn" target="_blank">芸来软件</a>.</strong> All rights reserved.
        </footer>

    </div>
@else
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{!! url('/') !!}">
                    InfyOm Generator
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{!! url('/home') !!}">Home</a></li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li><a href="{!! url('/login') !!}">Login</a></li>
                    <li><a href="{!! url('/register') !!}">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @endif



    <!-- jQuery 2.1.4 -->
{{--     <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.bootcss.com/select2/4.0.3/js/select2.min.js"></script>
    <script src="https://cdn.bootcss.com/iCheck/1.0.2/icheck.min.js"></script> --}}


    <script type="text/javascript" src="{{ asset('vendor/bootcss/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bootcss/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/bootcss/js/icheck.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script type="text/javascript" src="{{ asset('vendor/adminLTE/js/app.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datepicke/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/datepicke/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/tinymce/jquery.tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/layer/layer.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/admin.js') }}"></script>
    <script type="text/javascript">


    function removeByValue(arr, val) {
      for(var i=0; i<arr.length; i++) {
        if(arr[i] == val) {
          arr.splice(i, 1);
          break;
        }
      }
    }
    
    $('#refresh').click(function(){
                 layer.msg('清理中', {
                  icon: 16
                  ,shade: 0.01
                });
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
            $.ajax({
                url:'/clearCache',
                type:'post',
                success:function(data){
                    if(data.status){
                  setTimeout(function(){
                layer.closeAll('loading');
                    layer.msg('清理完成', {
                    icon: 1,
                    skin: 'layer-ext-moon' 
                    });
                }, 1500);
                    }else{
                layer.open({
                    content: '未知错误!'
                    ,skin: 'msg'
                    ,time: 2 
                  });
                        return false;
                    }
                }
            })
    });
    </script>
    @yield('scripts')
</body>
</html>