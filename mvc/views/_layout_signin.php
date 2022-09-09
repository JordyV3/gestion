<?php echo doctype("html5"); ?>
<html class="white-bg-login" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Sign in</title>
    <link rel="SHORTCUT ICON" href="<?=base_url("uploads/images/$siteinfos->photo")?>" />
    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet"  type="text/css">
    <!-- font Awesome -->
    <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet"  type="text/css">
    <!-- Style -->
    <link href="<?php echo base_url($backendThemePath.'/style.css'); ?>" rel="stylesheet"  type="text/css">
    <!-- lesson css -->
    <link href="<?php echo base_url($backendThemePath.'/lesson.css'); ?>" rel="stylesheet"  type="text/css">
    <link href="<?php echo base_url('assets/lesson/responsive.css'); ?>" rel="stylesheet"  type="text/css">
</head>

<body class="white-bg-login">

    <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
        <?php
            if(count($siteinfos->photo)) {
                echo "<center><img width='50' height='50' src=".base_url('uploads/images/'.$siteinfos->photo)." /></center>";
            }
        ?>
        <center><h4><?php echo namesorting($siteinfos->sname, 25); ?></h4></center>
    </div>

    <?php $this->load->view($subview); ?>

    <?php if(config_item('demo')) { ?>

        <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
    	<nav class="navbar navbar-default">
    	  <div class="container-fluid">
    	    <div class="navbar-header">
    	      <a class="navbar-brand" href="#">
    	       &nbsp; &nbsp; &nbsp; Elige un tipo de usuario para ver el DEMO
    	      </a>
    	    </div>
    	  </div>
    	</nav>
    	</div>
    	<div class="col-md-6 col-md-offset-3 marg" >
    	    <center>
    	        <div class="btn-group" role="group" aria-label="...">
    	            <button class="btn btn-sm btn-primary" id="admin">Admin</button>
    	            <button class="btn btn-sm btn-success" id="teacher">Docente</button>
    	            <button class="btn btn-sm btn-warning" id="student">Estudiante</button>
    	            <button class="btn btn-sm btn-info" id="parent">Padres</button>
    	            <button class="btn btn-sm btn-danger" id="accountant">Contador</button>
    	            <button class="btn btn-sm btn-default" id="librarian">Bibliotecario</button>
    		    <button class="btn btn-sm btn-primary" id="recep">Recepcionista</button>
    	        </div>
                <div><br>
                    <a href="https://facebook.com/anthoncode" target="_black">
                        <i class="fa fa-facebook-square fa-2x "></i>
                    </a>
                    <a href="https://twitter.com/anthoncode" target="_black">
                        <i class="fa fa-twitter-square fa-2x"></i>
                    </a>
                    <a href="https://pinterest.com/anthoncode" target="_black">
                        <i class="fa fa-pinterest-square fa-2x"></i>
                    </a>
                    <a href="https://github.com/anthoncode" target="_black">
                        <i class="fa fa-github-square fa-2x"></i>
                    </a>
                    <a href="https://youtube.com/c/anthoncode" target="_black">
                        <i class="fa fa-youtube-square fa-2x"></i>
                    </a>
                </div>
    	    </center>
    	</div>

    <?php } ?>

    <script type="text/javascript" src="<?php echo base_url('assets/lesson/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/bootstrap.min.js'); ?>"></script>

    <?php if(config_item('demo')) { ?>
        <script type="text/javascript">
            $('#admin').click(function () {
                $("input[name=username]").val('admin');
                $("input[name=password]").val('admin');
            });
            $('#teacher').click(function () {
                $("input[name=username]").val('docente');
                $("input[name=password]").val('123456');
            });
            $('#student').click(function () {
                $("input[name=username]").val('estudiante');
                $("input[name=password]").val('123456');
            });
            $('#parent').click(function () {
                $("input[name=username]").val('padres');
                $("input[name=password]").val('123456');
            });
            $('#accountant').click(function () {
                $("input[name=username]").val('contador');
                $("input[name=password]").val('123456');
            });
            $('#librarian').click(function () {
                $("input[name=username]").val('bibliotecario');
                $("input[name=password]").val('123456');
            });
            $('#recep').click(function () {
                $("input[name=username]").val('recepcionista');
                $("input[name=password]").val('123456');
            });
        </script>
    <?php } ?>
</body>
</html>
