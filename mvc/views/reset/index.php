
<div class="form-box" id="login-box">
    <div class="header">Restaurar contraseña</div>
    <form role="form" method="post">
        <div class="body white-bg">

        	<?php 
	            if($form_validation == "No"){
	            } else {
	                if(count($form_validation)) {
	                    echo "<div class=\"alert alert-danger alert-dismissable\">
                            <i class=\"fa fa-frown-o fa-2x\"></i>
                            <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                            $form_validation
                        </div>";
	                }
	            }

	            if($this->session->flashdata('reset_send')) {
                    $message = $this->session->flashdata('reset_send');
                    echo "<div class=\"alert alert-success alert-dismissable\">
                        <i class=\"fa fa-frown-o fa-2x\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $message
                    </div>";
                } else {
                	if($this->session->flashdata('reset_error')) {
	                    $message = $this->session->flashdata('reset_error');
	                    echo "<div class=\"alert alert-danger alert-dismissable\">
	                        <i class=\"fa fa-frown-o fa-2x\"></i>
	                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
	                        $message
	                    </div>";
	                }
                }
	        ?>
            <div class="form-group">
                <input class="form-control" placeholder="Email" name="email" type="text">
            </div>   
            <?php if(config_item('demo')) { ?>
                <input type="" class="btn btn-lg btn-warning btn-block" value="DEMO" />
            <?php }else{ ?>
                <input type="submit" class="btn btn-lg btn-success btn-block" value="ENVIAR" />
            <?php } ?>
        </div>
    </form>
</div>