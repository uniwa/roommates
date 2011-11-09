<!--TODO rewrite this-->
<!--<style>

.content{
	width: 360px;
	/*background-color: #fff;*/
	background-color: #63bad8;
    border:2px solid #667;
	text-align: left;
    margin: 80px auto;
    padding-bottom: 10px;
}

#darkbanner{
	margin: -6px -18px 20px -18px;
	padding: 8px 10px 10px 40px;
	background: #424242;
	position: relative;
    box-shadow: 4px 3px 5px #667;
}
#darkbanner span{
	padding-left: 35px;
	color: #fff;
	display: block;

}



h2{
	font: bold 24px helvetica, arial, sans-serif;
	color: #fff;
	display: inline;
	margin-left: 10px;
        text-shadow: 1px 1px 6px #667;

}


/* LOGIN */

body#login {
	width: 410px;
}
body#login #wrappertop{
	width: 370px;
}
body#login #wrapperbottom{
	width: 370px;
}
body#login #wrapperbottom_branding{
        width: 370px;
        padding: 0 20px 0 20px;
}
body#login #wrapperbottom_branding_text{
        width: 370px;
        background-color: #fff;
        padding-top: 3px;
        padding-bottom: 10px;
        color: #C0C0C0;
        font: 11px 'Lucida Grande', arial, sans-serif;
}
body#login #wrapperbottom_branding_text a{
        color: #C0C0C0;
}
body#login #wrapper{
	width: 370px;
	padding: 0 20px 0 20px;
}
body#login #content{
	width: 370px;
}
body#login #header {
	width: 350px;
}
body#login fieldset{
	width: 330px;
	border: 0;
	margin: 0px;
	display: block;
	margin-top: -30px;
	background-color: #fff;
	padding-top: 20px;
}
body#login fieldset p{
	color: #333333;
}
body#login fieldset p.error{
	padding: 10px 10px 10px 10px;
	color: #7b0c00;
	display: block;
	border: 2px solid #bc6f6b;
	background: #eac5c5;
	margin-bottom: 20px;
	font-weight: bold;
	}
body#login fieldset p.error img{
	margin-right: 5px;
	}
label{
	float: left;
	text-align: right;
	width: 100px;
	font-weight: bold;
	margin-right: 10px;
	padding-top: 7px;
}
 input[type=text], input[type=password]{
	height: 20px;
	width: 220px;
	_width: 237px;
	margin-bottom: 15px;
	padding: 3px;
	font: 16px;
}
body#login .form button{
    display:block;
    float:left;
    margin:0 3px 0 80px;
    _margin-left: 42px;
}

.submit input[type=submit]{
    display:block;
    margin:0 7px 0 110px;
    background-color:#92c97c;
    border:1px solid #73b35a;
    font-family:"Lucida Grande", Tahoma, Arial, Verdana, sans-serif;
    font-size:100%;
    line-height:130%;
    text-decoration:none;
    font-weight:bold;
    color:#e8f7df;
    cursor:pointer;
    padding:5px 10px 6px 7px;  Links
    outline:none;
background-image: none;
    -moz-border-radius: 0;
    border-radius: 0;
    text-shadow: none;

}


input[type=submit]:hover{
    background-color:#e8f7df;
    border:1px solid #92c97c;
    color:#31940c;
}

#header h1{
 font: 22px;
 padding:9px;
 font-weight:bold;
 text-align:center;
     text-shadow: 1px 1px 4px #ccc;

}

a{outline:none}

    #authMessage {
    float: left;
    padding-left: 60px;
    color: red;
}

		</style>-->
<style>
    #wrapper{
        border: 2px solid;
        border-radius: 15px;
        width: 300px;
        height:177px;
        margin-left: auto;
        margin-right: auto;
    }

    #darkbanner {

        height: 30px;
    }

    .login-main {
        
        height:130px;
    }
    #darkbanner h2{
        
        background:#FFFFFF;
        font-weight: bold; 
        border: 2px solid;
        border-top-left-radius: 60px 90px;
        border-bottom-right-radius: 60px 90px;
        border-bottom-left-radius: 13px 20px;
        border-top-right-radius: 13px 20px;
        -moz-box-shadow: 0 0 5px #53868B;
        -webkit-box-shadow: 0 0 5px 5px #53868B;
        box-shadow: 0 0 5px #53868B;
        width: 120px;
        height: 6px;
        margin-top: -10px;
        margin-left:15px;
        text-align: center;
        vertical-align:text-top;
        letter-spacing: 1.5px;
        padding: 3px 6px;
        display: inline;
    }
    
    .loginForm label{
        margin-left:30px; 
    }

    .loginForm input {
        border-radius: 7px;
        margin-left: 30px;
        width: 220px;
    }

    .loginForm {
        margin-top: 20px;
        width: 300px;
        height:130px;
    }

    .loginForm button { 
        border-top-left-radius: 60px 90px;
        border-bottom-right-radius: 60px 90px;
        border-bottom-left-radius: 13px 20px;
        border-top-right-radius: 13px 20px;
        float:right;
        position:relative;
        top:16.5px;
        background:#D1EEEE;;
    }

    #authMessage.message {
        margin-top:-10;
        width: 320px;
        margin-left:20px;
        float:left;
    }

    .loginForm hr {
        
        position:relative;
        top:20px;
     
    }

#flashMessage{
    border: 1px solid;
    margin: 10px 0;
    padding: 15px 10px 15px 50px;
    background-repeat: no-repeat;
    background-position: 10px center;
    color: #00529B;
    background-color: #d3e3fa;
    background-image: url(../img/info.png);
}

.required {
    /* override global setting for login */
    background-image: none !important;
}
    
</style>

<?php echo $this->Session->flash();?>
        <div id='registration'>
            <?php echo $this->Html->link('Εγγραφή', array('controller' => 'users', 'action' => 'register')); ?> 
        </div>
		<div id="wrappertop"></div>
			<div id="wrapper">
					<div class="content">
						<div id="darkbanner" class="banner320">
							<h2>Αυθεντικοποίηση</h2>
						</div>
						<div id="darkbannerwrap">
						</div>

        <?php   echo $this->Session->flash('auth');?>
        <div class="login-main">
        
        <?php   echo $this->Form->create('User', array('action' => 'login',"class" => "loginForm"));?>
        <?php   echo $this->Form->input('username', array('label' => 'Όνομα χρήστη:' ) );?>
        <?php   echo $this->Form->input('password', array('label' => 'Συνθηματικό:' ) );?>
        <?php   echo '<hr>';
                echo $this->Form->button( 'Είσοδος' ,array( 'type' => 'submit' ) );
        ?>
        
        <?php    echo $this->Form->end();
        ?>

        </div>

					</div>
				</div>


<!---->
<!-- Δεν εχει νοημα το rss εδω-->
<!--    <div class="rssIcon">-->
<!--        --><?php //echo $this->Html->image(  "rss.png",
//                                        array('url' => array(   'controller' => 'houses',
//                                                                'action' => 'index',
//                                                                'ext' => 'rss'  )));
//        ?>
<!--    </div>-->
