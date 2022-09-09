<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$this->lang->line('panel_title')?></title>
    <?php if(config_item('demo')) { ?>
        <meta name="robots" content="noindex">
    <?php }?>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link rel="SHORTCUT ICON" href="<?=base_url("uploads/images/$siteinfos->photo")?>" />
        <link rel="stylesheet" href="<?=base_url('assets/pace/pace.css')?>">
        <script type="text/javascript" src="<?php echo base_url('assets/lesson/jquery.min.js'); ?>"></script>
        <!-- <script type="text/javascript" src="<?php echo base_url('assets/slimScroll/jquery.slimscroll.min.js'); ?>"></script> -->

        <script type="text/javascript" src="<?php echo base_url('assets/toastr/toastr.min.js'); ?>"></script>

        <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/fonts/icomoon.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/fonts/ini-icon.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css'); ?>" rel="stylesheet">
        <link id="headStyleCSSLink" href="<?php echo base_url($backendThemePath.'/style.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/lesson/hidetable.css'); ?>" rel="stylesheet">
        <link id="headlessonCSSLink" href="<?php echo base_url($backendThemePath.'/lesson.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/lesson/responsive.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/toastr/toastr.min.css'); ?>" rel="stylesheet">
        <link href="<?php echo base_url('assets/lesson/mailandmedia.css'); ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url('assets/datatables/buttons.dataTables.min.css'); ?>" >
        <link rel="stylesheet" href="<?php echo base_url('assets/lesson/combined.css'); ?>" >
        <link rel="stylesheet" href="<?php echo base_url('assets/ajaxloder/ajaxloder.css'); ?>" >

        <?php
            if(isset($headerassets)) {
                foreach ($headerassets as $assetstype => $headerasset) {
                    if($assetstype == 'css') {
                      if(count($headerasset)) {
                        foreach ($headerasset as $keycss => $css) {
                          echo '<link rel="stylesheet" href="'.base_url($css).'">'."\n";
                        }
                      }
                    } elseif($assetstype == 'js') {
                      if(count($headerasset)) {
                        foreach ($headerasset as $keyjs => $js) {
                          echo '<script type="text/javascript" src="'.base_url($js).'"></script>'."\n";
                        }
                      }
                    }
                }
            }
        ?>

        <script type="text/javascript">
          $(window).load(function() {
            $(".se-pre-con").fadeOut("slow");;
          });
        </script>
    </head>
    <body class="skin-blue fuelux">
        <div class="se-pre-con"></div>
        <div id="loading">
            <img src="<?=base_url('assets/ajaxloder/loader.gif')?>" width="150" height="150"/>
        </div>
