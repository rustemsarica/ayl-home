<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .alert{
        text-shadow:none;
        color:#fff;
    }
</style>
<!--print error messages-->
<?php if ($this->session->flashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <strong>Hata!</strong> <?php echo $this->session->flashdata('errors'); ?>
    </div>
<?php endif; ?>

<!--print custom error message-->
<?php if ($this->session->flashdata('error')): ?>
    <?php if ($this->session->flashdata('error') == "Address in mailbox given [] does not comply with RFC 2822, 3.6.2."): ?>
        <div class="form-group">
            <div class="error-message">
                <p>
                    Please make your email settings from Email Settings Section!
                </p>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <strong>Hata!</strong> <?php echo $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>
    <!--print custom success message-->
<?php elseif ($this->session->flashdata('success')): ?>
    <div class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <strong>Başarılı!</strong> <?php echo $this->session->flashdata('success'); ?>
    </div>
<?php endif; ?>
