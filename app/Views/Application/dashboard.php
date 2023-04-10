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
                                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>D</b></span>
                                <span class="pcoded-mtext" data-i18n="nav.basic-components.main">Devices</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                    <div class="pcoded-navigatio-lavel" data-i18n="nav.category.forms">Overall</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li>
                            <div class="card widget-card-1">
                                    <div class="card-block-small">
                                        <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                                        <span class="text-c-blue f-w-100">Total Finished</span>
                                        <h4><?php echo $finished;?>/<?php echo $total;?></h4>
                                    </div>
                            </div>
                        </li>
                        <li>
                            <div class="card widget-card-1">
                                    <div class="card-block-small">
                                        <i class="icofont icofont-warning-alt bg-c-pink card1-icon"></i>
                                        <span class="text-c-pink f-w-100">Total Rejected</span>
                                        <h4><?php echo $rejected;?>/<?php echo $total;?></h4>
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
                                            <a  id="back" href="/"><i  class="ti-arrow-left" style="padding-right: 1%;"></i></a>
                                                <h5>Pneumatic Data tab</h5>
                                                
                                                <div class="pcoded-search">
                                                    <div class="pcoded-search-box ">
                                                        <input id="search" type="text" placeholder="Search by ID or RFID">
                                                    </div>
                                                </div>
                                                <input id="date" class="form-control" type="text" name="daterange"/>
                                                <div class="card-header-right">    <ul class="list-unstyled card-option">        <li><i class="icofont icofont-simple-left "></i></li>        <li><i class="icofont icofont-maximize full-card"></i></li>        <li><i class="icofont icofont-minus minimize-card"></i></li>        <li><i class="icofont icofont-refresh reload-card"></i></li>        <li><i class="icofont icofont-error close-card"></i></li>    </ul></div>
                                            </div>
                                            <div class="card-block table-border-style">
                                                <div  class="table-responsive">
                                                    <table id="D_table" class="table table-hover">
                                                            <!-- table from backend -->
                                                    </table>
                                                    <div id="pagination" class="pagination_tab d-flex justify-content-center">
                                                            <!-- pagination from backend -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Bootstrap tab card end -->
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


