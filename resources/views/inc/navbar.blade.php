  
<div id="wrapper">
    <div class="overlay"></div>
  
  <!-- Sidebar -->
  <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
    <ul class="nav sidebar-nav">
        <li class="sidebar-brand">
            <a href="{{url('/')}}">
               Hitchy
            </a>
        </li>
        <li>
            <a href="{{url('/index')}}">Map</a>
        </li>
        <li>
            <a href="{{url('/register')}}">Register</a>
        </li>
    </ul>
</nav>
<!-- /#sidebar-wrapper -->

 <!-- Page Content -->
 <div id="page-content-wrapper">
    <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
        <span class="hamb-top"></span>
        <span class="hamb-middle"></span>
        <span class="hamb-bottom"></span>
    </button>

<div class="row-fluid alertRow">
    <div class="col-sm-4 col-sm-offset-4 col-xs-8 col-xs-offset-2">
    
        @if (Session::has('Success'))
            <div class="alert alert-success" id="alert" role="alert">
                {{ Session::get('Success') }}
            </div>
        @endif

        @if (Session::has('Error'))
            <div class="alert alert-danger " id="alert" role="alert">
                {{ Session::get('Error') }}
            </div>
        @endif

    </div>
</div>
    