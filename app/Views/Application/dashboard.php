<?= $this->extend('Wrapperdash')?>
<?= $this->section('content')?>
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <nav class="pcoded-navbar">
                <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigatio-lavel" data-i18n="nav.category.forms">Menu</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li class="active">
                            <a href="/">
                                <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                                <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="/battery">
                                <span class="pcoded-micon"><i class="ti-plug"></i><b>D</b></span>
                                <span class="pcoded-mtext" data-i18n="nav.dash.main">Battery Voltage</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                    <div class="pcoded-navigatio-lavel" data-i18n="nav.category.forms"><?php echo $date;?> Update</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li>
                            <div class="card widget-card-1">
                                    <div class="card-block-small">
                                        <i class="icofont icofont-pie-chart bg-c-green card1-icon"></i>
                                        <span class="text-c-green f-w-100">Pneumatic Test Passed</span>
                                        <h4><?php echo $t1p;?>/<?php echo $t1p+$t1f;?></h4>
                                        <div>
                                            <span class="f-left m-t-10 text-muted">
                                                <i class="text-c-green f-16 icofont icofont-warning m-r-10"></i><?php echo $t1f;?> Devices Failed
                                            </span>
                                        </div>
                                    </div>
                            </div>
                        </li>
                        <li>
                            <div class="card widget-card-1">
                                    <div class="card-block-small">
                                        <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                                        <span class="text-c-blue f-w-100">Final Test Passed</span>
                                        <h4><?php echo $t2p;?>/<?php echo $t2p+$t2f;?></h4>
                                        <div>
                                            <span class="f-left m-t-10 text-muted">
                                                <i class="text-c-blue f-16 icofont icofont-warning m-r-10"></i><?php echo $t2f;?> Devices Failed
                                            </span>
                                        </div>
                                    </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            
                            <!-- Page body start -->
                            <div class="page-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <!-- Bootstrap tab card start -->
                                        <div class="card">
                                            <div class="card-header">
                                                
                                            <div  id="back"><i  class="ti-arrow-left" style="padding-right: 1%;"></i><h5>Pneumatic data</h5></div>
                                                <h5 id="head">Pneumatic Tester dashboard</h5>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="pcoded-search">
                                                            <div class="pcoded-search-box ">
                                                                <input id="search" type="text" placeholder="Search by ID or RFID">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input id="date" class="form-control" type="text" name="daterange"/>
                                                    </div>
                                                </div>
                                                <div class="card-header-right">    <ul class="list-unstyled card-option">        <li><i class="icofont icofont-simple-left "></i></li>        <li><i class="icofont icofont-maximize full-card"></i><i class="icofont icofont-rounded-down download_csv"></i></li>        <li><i class="icofont icofont-minus minimize-card"></i></li>        <li><i class="icofont icofont-refresh reload-card refresh_data"></i></li>        <li><i class="icofont icofont-error close-card"></i></li>    </ul></div>
                                            </div>
                                            <div class="card-block table-border-style">
                                                <div  class="table-responsive table-container">
                                                    <table id="D_table" class="table table-hover">
                                                            <!-- table from backend -->
                                                    </table>
                                                    <div class="row" style="margin-left: 0px;margin-right:0px;">
                                                        <div id="pagination" class=" col-sm-10 pagination_tab d-flex justify-content-center">
                                                                <!-- pagination from backend -->
                                                                
                                                        </div>
                                                        <div id="row_data_button" style="text-align: right;" class=" col-sm-2 justify-content-right">
                                                            <button id="row_data_down"  class="btn btn-info btn-sm btn-round">Export Row Datas</button>  
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bootstrap tab card end -->
                                        <div class="row" id="date_data">
                                            <!-- card summary start -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Page body end -->
                        </div>
                    </div>
                    <!-- Main-body end -->

                    <div id="styleSelector">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection()?>


