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
                            <a href="/">
                                <span class="pcoded-micon"><i class="ti-plug"></i><b>D</b></span>
                                <span class="pcoded-mtext" data-i18n="nav.dash.main">Battery Voltage</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
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
                                                <h5 id="head">Battery Tester dashboard</h5>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="pcoded-search">
                                                            <div class="pcoded-search-box ">
                                                                <input id="B_search" type="text" placeholder="Search by ID ">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-header-right">    <ul class="list-unstyled card-option">        <li><i class="icofont icofont-simple-left "></i></li>        <li><i class="icofont icofont-maximize full-card"></i></li>        <li><i class="icofont icofont-minus minimize-card"></i></li>        <li><i class="icofont icofont-refresh reload-card refresh_data_b"></i></li>        <li><i class="icofont icofont-error close-card"></i></li>    </ul></div>
                                            </div>
                                            <div class="card-block table-border-style">
                                                <div  class="table-responsive  table-container">
                                                    <table id="B_table" class="table table-hover">
                                                            <!-- table from backend -->
                                                    </table>
                                                    <div class="row" style="margin-left: 0px;margin-right:0px;">
                                                        <div id="B_pagination" class=" col-sm-12 pagination_tab d-flex justify-content-center">
                                                                <!-- pagination from backend -->
                                                                
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
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


