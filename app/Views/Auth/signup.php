<?= $this->extend('Wrapper')?>
<?= $this->section('content')?>
    <section class="login p-fixed d-flex text-center bg-primary common-img-bg">
        <!-- Container-fluid starts -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <div class="signup-card card-block auth-body mr-auto ml-auto">
                        <form class="md-float-material" action="<?php echo base_url(); ?>/SignupController/store" method="post">
                            <div class="text-center">
                                <img src="assets/images/auth/logo-dark-signup.png" alt="logo.png">
                            </div>
                            <div class="auth-box">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center txt-primary">Sign up. It is fast and easy.</h3>
                                    </div>
                                </div>
                                <hr/>
                                <?php if(session()->getFlashdata('msg')):?>
                                    <div class="alert alert-warning">
                                        <?= session()->getFlashdata('msg') ?>
                                    </div>
                                <?php endif;?>
                                <div class="input-group">
                                    <input name="name" type="text" class="form-control" placeholder="Choose Username">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input name="email" type="text" class="form-control" placeholder="Your Email Address">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input name="password" type="password" class="form-control" placeholder="Choose Password">
                                    <span class="md-line"></span>
                                </div>
                                <div class="input-group">
                                    <input name="confirmpassword" type="password" class="form-control" placeholder="Confirm Password">
                                    <span class="md-line"></span>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <button type="submit" type="button" class="btn btn-primary btn-md btn-block waves-effect text-center m-b-20">Sign up now.</button>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-10">
                                        <p class="text-inverse text-left m-b-0">Thank you and enjoy our website.</p>
                                        <p class="text-inverse text-left"><b>Your Authentication Team</b></p>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="assets/images/auth/Logo-small-bottom.png" alt="small-logo.png">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- end of form -->
                    </div>
                    <!-- Authentication card end -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
<?= $this->endSection()?>
