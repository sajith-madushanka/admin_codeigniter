
<!DOCTYPE html>
<html>
    <head>
        <title>MAS Monitering</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Favicon icon -->
        <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
        <!-- Google font-->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800" rel="stylesheet">
        <!-- Required Fremwork -->
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap/css/bootstrap.min.css">
        <!-- themify-icons line icon -->
        <link rel="stylesheet" type="text/css" href="assets/icon/themify-icons/themify-icons.css">
        <!-- ico font -->
        <link rel="stylesheet" type="text/css" href="assets/icon/icofont/css/icofont.css">
        <!-- Style.css -->
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <link rel="stylesheet" type="text/css" href="assets/css/jquery.mCustomScrollbar.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    </head>
	<body>
        <div class="theme-loader">
            <div class="ball-scale">
                <div class='contain'>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                    <div class="ring"><div class="frame"></div></div>
                </div>
            </div> 
        </div>
        <div id="pcoded" class="pcoded">
            <div class="pcoded-overlay-box"></div>
                <div class="pcoded-container navbar-wrapper">
                    <nav class="navbar header-navbar pcoded-header">
                        <div class="navbar-wrapper">
                            <div class="navbar-logo" logo-theme="theme6">
                                <a class="mobile-menu" id="mobile-collapse" href="#!">
                                    <i class="ti-menu"></i>
                                </a>
                                <a href="/">
                                    <img class="img-fluid" src="assets/images/logo.png" alt="Theme-Logo" />
                                </a>
                                
                                <a class="mobile-options">
                                    <i class="ti-more"></i>
                                </a>
                            </div>

                            <div class="navbar-container container-fluid">
                                <ul class="nav-left">
                                    <li>
                                        <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                                    </li>

                                    <li>
                                        <a href="#!" onclick="javascript:toggleFullScreen()">
                                            <i class="ti-fullscreen"></i>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav-right">
                                    <li class="user-profile header-notification">
                                        <a href="#!">
                                            <img src="assets/images/avatar-4.jpg" class="img-radius" alt="User-Profile-Image">
                                            <span><?php echo session('name');?></span>
                                            <i class="ti-angle-down"></i>
                                        </a>
                                        <ul class="show-notification profile-notification">
                                            <li>
                                                <a href="#!">
                                                    <i class="ti-settings"></i> Settings
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#">
                                                    <i class="ti-user"></i> Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/logout">
                                                    <i class="ti-layout-sidebar-left"></i> Logout
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                    <?= $this->renderSection('content')?>
                </div>
            </div>
        </div>
       
	</body>
    <script type="text/javascript" src="assets/js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="assets/js/popper.js/popper.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap/js/bootstrap.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="assets/js/jquery-slimscroll/jquery.slimscroll.js"></script>
    <!-- modernizr js -->
    <script type="text/javascript" src="assets/js/modernizr/modernizr.js"></script>
    <script type="text/javascript" src="assets/js/modernizr/css-scrollbars.js"></script>
    <script type="text/javascript" src="assets/js/common-pages.js"></script>
<script type="text/javascript" src="assets/js/script.js"></script>
<script type="text/javascript " src="assets/js/SmoothScroll.js"></script>
<script src="assets/js/pcoded.min.js"></script>
<script src="assets/js/demo-12.js"></script>
<script src="assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
var $window = $(window);
var start_date = '';
var end_date = '';
var nav = $('.fixed-button');
    $window.scroll(function(){
        if ($window.scrollTop() >= 200) {
         nav.addClass('active');
     }
     else {
         nav.removeClass('active');
     }
 });
 $(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    start_date = start.format('YYYY-MM-DD');
    end_date = end.format('YYYY-MM-DD');
    $('#search').val('');
    load_data(1,$('#search').val(),start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
  });
});

 $(document).ready(function() {
    load_data(1);
});
$(document).on('click','.pagination a',function(e) {
    // Load pagination
    e.preventDefault();
    load_data(e.target.innerText,$('#search').val(),start_date,end_date);
     
   
});

$(document).on('input','#search',function(e) {
    start_date = '';
    end_date = '';
    load_data(1,e.target.value);
});

$(document).on('click','.icofont-refresh',function() {
     if($("#search").is(":visible")){
        load_data(1);
     }
     else{
        pair_Data($('#pair').val(),1);
     } 
});

function load_data(page,keyword,start,end) {
    // Send AJAX request
    $.ajax({
        url: '<?php echo base_url(); ?>get_data',
        type: 'POST',
        data: {
            page: page,
            keyword: keyword,
            start:start,
            end:end
        },
        dataType: 'json',
        
        success: function(response) {
            // console.log(response);
            // Update table data
            $('#back').hide();
            $('#search').show();
            $('#date_data').show();
            $('#D_table').html(response.table_data);
            $('#pagination').html(response.links);
            $('#date_data').html(response.summary);
        }
    });
}

function pair_Data(id) {
    $.ajax({
        url: '<?php echo base_url(); ?>pair_data',
        type: 'POST',
        data: {
            id:id
        },
        dataType: 'json',
        
        success: function(response) {
            // Update table data
            $('#search').hide();
            $('#date').hide();
            $('#date_data').hide();
            $('#back').show();
            $('#D_table').html(response.table_data);
            $('#pagination').html('');
        }
    });
}

</script>
</html>