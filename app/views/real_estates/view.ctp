<style>
    #leftbar{
        float: left;
        margin: 0px 0px 0px 32px;
        padding: 32px;
    }
    
    #main-inner{
        float: left;
        border-left: 1px dotted #333;
        margin: 10px 0px 20px 0px;
        padding: 24px;
    }
    
    #profilePic{
        width: 128px;
        height: 128px;
        padding: 2px;
    }
    
    #profileEdit{
        margin: 16px 0px 0px 0px;
    }
    
    #profileRss,#profileBan{
        margin: 16px 0px 0px 0px;
    }
    
    #profileRss img,#profileBan img{
        margin: 0px 4px 0px 0px;
    }
    
    .profileClear{
        clear: both;
    }
    
    .profileBlock{
        float: left;
        margin: 0px 64px 64px 16px;
        padding: 0px 8px 0px 8px;
    }

    .profileTitle{
        margin: 12px 0px 8px 18px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .profileInfo{
        margin: 0px 0px 0px 24px;
        font-size: 1.0em;
    }
</style>
<?php
    $role = $this->Session->read('Auth.User.role');
    // Profile info
	$name = $realEstate['RealEstate']['firstname']." ".$realEstate['RealEstate']['lastname'];
	$company = $realEstate['RealEstate']['company_name'];
	$email = $realEstate['RealEstate']['email'];
	$phone = ($realEstate['RealEstate']['phone'])?$realEstate['RealEstate']['phone']:'-';
	$fax = ($realEstate['RealEstate']['fax'])?$realEstate['RealEstate']['fax']:'-';
	$afm = $realEstate['RealEstate']['afm'];
	$doy = ($realEstate['RealEstate']['doy'])?$realEstate['RealEstate']['doy']:'-';
	$address = $realEstate['RealEstate']['address'];
	$postal = ($realEstate['RealEstate']['postal_code'])?$realEstate['RealEstate']['postal_code']:'';
	showDebug($fax);
    $address = $realEstate['RealEstate']['address'];
	$name = Sanitize::html($name, array('remove' => true));
	$profileThumb = 'realestate.png';
	function echoDetail($title, $option){
		$span = array("open" => "<span class='profile-strong'>", "close" => "</span>");
		echo $title.': '.$span['open'].$option.$span['close']."<br \>\n";
	}
?>
<div id='leftbar'>
    <div id='profilePic'>
        <?php
            $profilePic = $this->Html->image($profileThumb, array('alt' => $name));
            echo $profilePic;
        ?>
    </div>
    <div id='profileBan'>
        <?php
            if($role == 'admin' &&
                $realEstate['RealEstate']['user_id'] != $this->Session->read('Auth.User.id')){
                if($realEstate['User']['banned'] == 0){
                    $banContent = $this->Html->image('ban.png', array('alt' => $company));
                    $banContent .= ' Απενεργοποίηση χρήστη';
                    $banClass = 'banButton';
                    $banMsg = "Είστε σίγουρος ότι θέλετε να απενεργοποιήσετε τον λογαριασμό αυτού του χρήστη;";
                    $banCase = 'ban';
                }else{
                    $banContent = $this->Html->image('unban.png', array('alt' => $company));
                    $banContent .= ' Ενεργοποίηση χρήστη';
                    $banClass = 'unbanButton';
                    $banMsg = "Είστε σίγουρος ότι θέλετε να ενεργοποιήσετε τον λογαριασμό αυτού του χρήστη;";
                    $banCase = 'unban';
                }
                $banLink = $this->Html->link($banContent, array(
                    'controller' => 'real_estates', 'action' => $banCase, $realEstate['RealEstate']['id']),
                    array('class' => $banClass, 'escape' => false), $banMsg);
                echo $banLink;
            }
        ?>
    </div>
    <div id='profileEdit'>
        <?php
//            if($this->Session->read('Auth.User.id') == $realEstate['User']['id']){
//                echo $html->link('Επεξεργασία στοιχείων',
//                    array('action' => 'edit', $realEstate['RealEstate']['id']));
//            }
        ?>
    </div>
</div>
<div id='main-inner'>
        <?php
/*            if($this->Session->read('Auth.User.role') == 'admin' &&
                $profile['Profile']['user_id'] != $this->Session->read('Auth.User.id')){
                if($profile['User']['banned'] == 0){
                    $flash = "Είστε σίγουρος ότι θέλετε να απενεργοποιήσετε τον λογαρισμό αυτού του χρήστη;";
                    echo $html->link('Ban',
                        array('action' => 'ban', $profile['Profile']['id']),
                        array('class' => 'ban-button'), $flash);
                }else{
                    echo $html->link('Unban',
                        array('action' => 'unban', $profile['Profile']['id']),
                        array('class' => 'unban-button'));
                }
            }*/
        ?>
    <div class='profileBlock profileClear'>
        <div id='myName' class='profileTitle'>
            <h2><?php echo $company; ?></h2>
        </div>
        <div id='myProfile' class='profileInfo'>
		    <?php
			    $emailUrl = $this->Html->link($email, 'mailto:'.$email);
			    echoDetail('Διεύθυνση', $address);
			    echoDetail('ΤΚ', $postal);
			    echoDetail('Δήμος', $municipality);
			    echoDetail('ΑΦΜ', $afm);
			    echoDetail('ΔΟΥ', $doy);
			    echo '<br />';
			    echoDetail('Υπεύθυνος', $name);
			    echoDetail('Email', $emailUrl);
			    echoDetail('Τηλέφωνο', $phone);
			    echoDetail('Φαξ', $fax);
		    ?>
        </div>
    </div>
</div>

