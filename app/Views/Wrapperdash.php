
<!DOCTYPE html>
<html>
    <head>
        <title>MAS Monitoring</title>
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
                    <!-- remark popup -->
                    <button style="display:none" id="remark" href="#rmModal" class="trigger-btn" data-toggle="modal"></button>
                    <div id="rmModal" class="modal fade">
                        <div class="modal-dialog modal-confirm">
                            <div class="modal-content">
                                <div class="modal-header flex-column">
                                    <div class="icon-box" style="border: 3px solid #93BE52" >
                                        <i style="color: #93BE52;" class="ti-pencil-alt"></i>
                                    </div>
                                    <h4 class="modal-title w-100">Please select the remark.</h4>
                                    <button type="button" id="close_del_1" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="optionbox">
                                    <select id="remark_select"> 
                                        <option value="Air Leakage - Top">Air Leakage - Top</option>
                                        <option value="Air leakage - Middle">Air leakage - Middle</option>
                                        <option value="Air Leakage - Bottom ">Air Leakage - Bottom</option>
                                        <option value="Exceeding the required pressures">Exceeding the required pressures</option>
                                        <option value="Installation error">Installation error</option>
                                        <option value="Sensor Issue">Sensor Issue</option>
                                        <option value="Device Malfunction (DM)">Device Malfunction (DM)</option>
                                        <option value="Pressure Checker Malfuntion (PCM)">Pressure Checker Malfuntion (PCM)</option>
                                    </select>
                                    <input id="remark_input" placeholder="add a comment"></input>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button"  class="btn btn-danger"  onclick="remark_popup('delete')">Remove</button>
                                    <button type="button" style="background:#93BE52;" class="btn btn-danger"  onclick="remark_popup('add')">ADD</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- delete popup -->
                    <button style="display:none" id="del" href="#myModal" class="trigger-btn" data-toggle="modal"></button>
                    <div id="myModal" class="modal fade">
                        <div class="modal-dialog modal-confirm">
                            <div class="modal-content">
                                <div class="modal-header flex-column">
                                    <div class="icon-box">
                                        <i class="ti-trash"></i>
                                    </div>
                                    <h4 class="modal-title w-100">Are you sure?</h4>
                                    <button type="button" id="close_del_2" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <p>Do you really want to delete these records? This process cannot be undone.</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger"  onclick="delete_data_popup()">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
var page = 1;
var b_page = 1;
var per_page = 15;
var pair_del = "";
var data_del = "";
var battery_del = "";
var export_ids = [];
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
    if(window.location.pathname == "/battery"){

        load_battery_data(1);
    }
    else{
        load_data(1);
    }
});

$(document).on('click','.pagination a',function(e) {
    // Load pagination
    if(window.location.pathname == "/battery"){
        e.preventDefault();
        b_page = e.target.innerText;
        const conv = +b_page;
        if (conv) {
            load_battery_data(e.target.innerText,$('#B_search').val());
        } else {
            const element = document.querySelector('[aria-label="'+b_page+'"]');
            b_page = element.getAttribute("href").split("=")[1]
            load_battery_data(b_page,$('#B_search').val());
        }
    }
    else{
        e.preventDefault();
        page = e.target.innerText;
        const conv = +page;
        if (conv) {
            load_data(e.target.innerText,$('#search').val(),start_date,end_date);
        } else {
            const element = document.querySelector('[aria-label="'+page+'"]');
            page = element.getAttribute("href").split("=")[1]
            load_data(page,$('#search').val(),start_date,end_date);
        }
    }
   
});


$(document).on('input','#search',function(e) {
    start_date = '';
    end_date = '';
    $('input[name="daterange"]').datepicker( "option", "maxDate", null );
    $('input[name="daterange"]').datepicker( "option", "minDate", null );
    load_data(1,e.target.value);
});

$(document).on('input','#B_search',function(e) {
    load_battery_data(1,e.target.value);
});

$(document).on('click','.refresh_data',function() {
     if($("#search").is(":visible")){
        load_data(1);
     }
     else{
        pair_Data($('#pair').val());
     } 
});

$(document).on('click','.refresh_data_b',function() {
    load_battery_data(1);
     
});


$(document).on('click','.download_csv',function() {
        if($("#search").is(":visible")){
            $.ajax({
                url: '<?php echo base_url(); ?>export_data',
                type: 'POST',
                data: {
                    page: page,
                    keyword:$('#search').val(),
                    start:start_date,
                    end:end_date,
                    per_page:per_page
                },
                dataType: 'json',
                
                
                success: function (data) {
                    if(data){
                        // Create a Blob with the CSV data
                        var blob = new Blob([data], {type: 'text/csv'});

                        // Create a temporary link element
                        var link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.download = 'pneumatic_data.csv'; // Set the download file name
                        link.click();

                        // Clean up
                        URL.revokeObjectURL(link.href);
                        link.remove();
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
               
            });
        }
        else{
        
        } 
});

$(document).on('click','#back',function() {

    load_data(page,$('#search').val(),start_date,end_date);
});

$(document).on('change','#export_check',function(e) {
     if(this.checked) {
        console.log(e.target.value);
        export_ids.push(e.target.value);
    }
    else{
        export_ids = export_ids.filter(arrayItem => arrayItem !== e.target.value);
    }
    if(export_ids.length >= 1){
        $('#row_data_down').show();
    }
    else{
        $('#search_wrap').removeClass('col-sm-3');
         $('#row_data_down').hide();
    }
});

$(document).on('click','#row_data_down',function() {

    console.log(export_ids);
    $.ajax({
        url: '<?php echo base_url(); ?>export_raw_data_array',
        type: 'POST',
        data: {
            ids: export_ids
        },
        dataType: 'json',
        
        
        success: function (data) {
            if(data){
                // Create a Blob with the CSV data
                // console.log(data);
                var blob = new Blob([data], {type: 'text/csv'});

                // Create a temporary link element
                var link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'raw_data_bulk.csv';// Set the download file name
                link.click();

                // Clean up
                URL.revokeObjectURL(link.href);
                link.remove();
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
        
    });
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
            end:end,
            per_page:per_page
        },
        dataType: 'json',
        
        success: function(response) {
            
            console.log(response);
            // Update table data
            $('#back').hide();
            $('#head').show();
            $('#search').show();
            $('#per_page').show();
            $('#date_data').show();
            $('#date').show();
            $('.download_csv').show();
            $('#row_data_button').show();
            $('#D_table').html(response.table_data);
            $('#pagination').html(response.links);
            $('#date_data').html(response.summary);
            $('#row_data_down').hide();
            export_ids = [];
        }
    });
}

function load_battery_data(page,keyword) {
    // Send AJAX request
    $.ajax({
        url: '<?php echo base_url(); ?>get_battery_data',
        type: 'POST',
        data: {
            page: page,
            keyword: keyword
        },
        dataType: 'json',
        
        success: function(response) {
            console.log(response);
            $('#B_table').html(response.table_data);
            $('#B_pagination').html(response.links);
        }
    });

    setTimeout(function(){
            load_battery_data(b_page,$('#B_search').val());
            },
    3000);
}

function pair_Data(id) {
    const varsuper = '<?php echo session('is_super');?>';
    if( varsuper == 1){
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
                $('#per_page').hide();
                $('.download_csv').hide();
                $('#date').hide();
                $('#date_data').hide();
                $('#back').show();
                $('#head').hide();
                $('#row_data_button').hide();
                $('#D_table').html(response.table_data);
                $('#pagination').html('');
                $('#row_data_down').hide();
                export_ids = [];
            }
        });
    }
   
}

function raw_data(id,pair_id) {
    $.ajax({
        url: '<?php echo base_url(); ?>export_raw_data',
        type: 'POST',
        data: {
            id: id,
            pair_id:pair_id
        },
        dataType: 'json',
        
        
        success: function (data) {
            if(data){
                // Create a Blob with the CSV data
                //console.log(response);
                var blob = new Blob([data], {type: 'text/csv'});

                // Create a temporary link element
                var link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'raw_data.csv';// Set the download file name
                link.click();

                // Clean up
                URL.revokeObjectURL(link.href);
                link.remove();
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
        
    });
       
}

function delete_data(id,data_id) {
    pair_del = id;
    data_del = data_id;
    document.getElementById('del').click();
}

function delete_battery_data(id) {
    battery_del = id;
    document.getElementById('del').click();
}

function delete_data_popup() {
   
    if(window.location.pathname == "/battery"){
        $.ajax({
            url: '<?php echo base_url(); ?>delete_B_data',
            type: 'POST',
            data: {
                id:battery_del
            },
            dataType: 'json',
            
            success: function(response) {
                document.getElementById('close_del_2').click();
                if(response.deleted == 1){
                    load_battery_data(b_page,$('#B_search').val());
                }
            }
        });
    }
    else{
        $.ajax({
            url: '<?php echo base_url(); ?>delete_data',
            type: 'POST',
            data: {
                id:pair_del,
                data_id:data_del
            },
            dataType: 'json',
            
            success: function(response) {
                document.getElementById('close_del_2').click();
                if(response.deleted == 1){
                    pair_Data(pair_del);
                }
                else if(response.deleted == 2){
                    load_data(page,$('#search').val(),start_date,end_date);
                }
                
            }
        });
    }
   
}

function remark(id) {
    remark_id = id;
    document.getElementById('remark').click();
}

function remark_popup(mode) {
   

    var e = document.getElementById("remark_select");
    var value = e.value;
    var i = document.getElementById("remark_input").value;
   $.ajax({
       url: '<?php echo base_url(); ?>remark_data',
       type: 'POST',
       data: {
           id:remark_id,
           value:value,
           input:i,
           mode:mode
       },
       dataType: 'json',
       
       success: function(response) {
           document.getElementById('close_del_1').click();
           if(response.remarked == 1){
                pair_Data($('#pair').val());
           }
           
       }
   });
}

function per_page_change(){
    var e = document.getElementById("per_page");
    per_page = e.value;
    load_data(1,$('#search').val(),start_date,end_date);
}
function pin_data(id,status) {
    $.ajax({
        url: '<?php echo base_url(); ?>pin_data',
        type: 'POST',
        data: {
            id:id,
            status:status
        },
        dataType: 'json',
        
        success: function(response) {
            // Update table data
            if(response.pinned == 1){
                console.log(response.pinned);
                load_data(page,$('#search').val(),start_date,end_date);
            }
        }
    });
}

</script>
</html>