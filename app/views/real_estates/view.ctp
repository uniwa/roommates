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
<div id='realestateView'>
<div id='leftbar' class='leftGeneral'>
    <div id='profileCont'>
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
                        $banContent = $this->Html->image('delete_16.png', array(
                            'alt' => $company, 'class' => 'optionIcon'));
                        $banContent .= ' Κλείδωμα χρήστη';
                        $banClass = 'banButton';
                        $banMsg = "Είστε σίγουρος ότι θέλετε να κλειδώσετε τον λογαριασμό αυτού του χρήστη;";
                        $banCase = 'ban';
                    }else{
                        $banContent = $this->Html->image('accept_16.png', array(
                            'alt' => $company, 'class' => 'optionIcon'));
                        $banContent .= ' Ξεκλείδωμα χρήστη';
                        $banClass = 'unbanButton';
                        $banMsg = "Είστε σίγουρος ότι θέλετε να ξεκλειδώσετε τον λογαριασμό αυτού του χρήστη;";
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
</div>
<div id='main-inner' class='mainGeneral'>
    <div class='profileBlock profileClear'>
        <div id='myName' class='profileTitle'>
            <h2><?php echo $company; ?></h2>
        </div>
        <div id='myProfile' class='profileInfo'>
		    <?php
			    $emailUrl = $this->Html->link($email, 'mailto:'.$email);
			    echoDetail('Διεύθυνση', ($address) ? $address : '-');
			    echoDetail('ΤΚ', ($postal) ? $postal : '-');
			    echoDetail('Δήμος', isset($municipality) ? $municipality : '-');
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
</div>
