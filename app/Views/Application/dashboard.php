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
                            <a href="<?php echo $this->base_url; ?>/">
                                <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                                <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="<?php echo $this->base_url; ?>/">
                                <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i><b>D</b></span>
                                <span class="pcoded-mtext" data-i18n="nav.basic-components.main">Devices</span>
                                <span class="pcoded-mcaret"></span>
                            </a>
                        </li>
                    </ul>
                    <div class="pcoded-navigatio-lavel" data-i18n="nav.category.forms">First Inspection</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li>
                            <div class="card widget-card-1">
                                    <div class="card-block-small">
                                        <i class="icofont icofont-pie-chart bg-c-blue card1-icon"></i>
                                        <span class="text-c-blue f-w-100">Total Tested</span>
                                        <h4>49/50GB</h4>
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
                                <div class="row" style="display:none">
                                    <div class="col-sm-12">
                                        <!-- Tab variant tab card start -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Tab variant</h5>
                                                <div class="card-header-right">    <ul class="list-unstyled card-option">        <li><i class="icofont icofont-simple-left "></i></li>        <li><i class="icofont icofont-maximize full-card"></i></li>        <li><i class="icofont icofont-minus minimize-card"></i></li>        <li><i class="icofont icofont-refresh reload-card"></i></li>        <li><i class="icofont icofont-error close-card"></i></li>    </ul></div>
                                            </div>
                                            <div class="card-block tab-icon">
                                                <!-- Row start -->
                                                <div class="row">
                                                    <div class="col-lg-12 col-xl-6">
                                                        <!-- <h6 class="sub-title">Tab With Icon</h6> -->
                                                        <div class="sub-title">Tab With Icon</div>
                                                        <!-- Nav tabs -->
                                                        <ul class="nav nav-tabs md-tabs " role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-toggle="tab" href="#home7" role="tab"><i class="icofont icofont-home"></i>Home</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#profile7" role="tab"><i class="icofont icofont-ui-user "></i>Profile</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#messages7" role="tab"><i class="icofont icofont-ui-message"></i>Messages</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#settings7" role="tab"><i class="icofont icofont-ui-settings"></i>Settings</a>
                                                                <div class="slide"></div>
                                                            </li>
                                                        </ul>
                                                        <!-- Tab panes -->
                                                        <div class="tab-content card-block">
                                                            <div class="tab-pane active" id="home7" role="tabpanel">
                                                                <p class="m-0">1. This is Photoshop's version of Lorem IpThis is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean mas Cum sociis natoque penatibus et magnis dis.....</p>
                                                            </div>
                                                            <div class="tab-pane" id="profile7" role="tabpanel">
                                                                <p class="m-0">2.Cras consequat in enim ut efficitur. Nulla posuere elit quis auctor interdum praesent sit amet nulla vel enim amet. Donec convallis tellus neque, et imperdiet felis amet.</p>
                                                            </div>
                                                            <div class="tab-pane" id="messages7" role="tabpanel">
                                                                <p class="m-0">3. This is Photoshop's version of Lorem IpThis is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean mas Cum sociis natoque penatibus et magnis dis.....</p>
                                                            </div>
                                                            <div class="tab-pane" id="settings7" role="tabpanel">
                                                                <p class="m-0">4.Cras consequat in enim ut efficitur. Nulla posuere elit quis auctor interdum praesent sit amet nulla vel enim amet. Donec convallis tellus neque, et imperdiet felis amet.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6 tab-with-img">
                                                        <div class="sub-title">Tab With Images</div>
                                                        <!-- Nav tabs -->
                                                        <ul class="nav nav-tabs md-tabs img-tabs b-none" role="tablist">
                                                            <li class="nav-item">
                                                                <a class="nav-link active" data-toggle="tab" href="#home8" role="tab">
                                                                    <img src="assets/images/avatar-1.jpg" class="img-fluid img-circle" alt="">
                                                                    <span class="quote"><i class="icofont icofont-quote-left bg-primary"></i></span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#profile8" role="tab">
                                                                    <img src="assets/images/avatar-2.jpg" class="img-fluid img-circle" alt="">
                                                                    <span class="quote"><i class="icofont icofont-quote-left bg-primary"></i></span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#messages8" role="tab">
                                                                    <img src="assets/images/avatar-3.jpg" class="img-fluid img-circle" alt="">
                                                                    <span class="quote"><i class="icofont icofont-quote-left bg-primary"></i></span>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" data-toggle="tab" href="#settings8" role="tab">
                                                                    <img src="assets/images/avatar-4.jpg" class="img-fluid img-circle" alt="">
                                                                    <span class="quote"><i class="icofont icofont-quote-left bg-primary"></i></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                        <!-- Tab panes -->
                                                        <div class="tab-content card-block">
                                                            <div class="tab-pane active" id="home8" role="tabpanel">
                                                                <p class="text-center m-0">1. This is Photoshop's version of Lorem IpThis is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean mas Cum sociis natoque penatibus et magnis dis.....</p>
                                                            </div>
                                                            <div class="tab-pane" id="profile8" role="tabpanel">
                                                                <p class="text-center m-0">2. Cras consequat in enim ut efficitur. Nulla posuere elit quis auctor interdum praesent sit amet nulla vel enim amet. Donec convallis tellus neque, et imperdiet felis amet.</p>
                                                            </div>
                                                            <div class="tab-pane" id="messages8" role="tabpanel">
                                                                <p class="text-center m-0">3. This is Photoshop's version of Lorem IpThis is Photoshop's version of Lorem Ipsum. Proin gravida nibh vel velit auctor aliquet. Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean mas Cum sociis natoque penatibus et magnis dis.....</p>
                                                            </div>
                                                            <div class="tab-pane" id="settings8" role="tabpanel">
                                                                <p class="text-center m-0">4. Cras consequat in enim ut efficitur. Nulla posuere elit quis auctor interdum praesent sit amet nulla vel enim amet. Donec convallis tellus neque, et imperdiet felis amet.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Row end -->
                                            </div>
                                        </div>
                                        <!-- Tab variant tab card start -->
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


