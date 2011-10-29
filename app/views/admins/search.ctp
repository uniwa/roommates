<div class = "admpanel"> 
<?php echo $this->Form->create( 'Admin' ,array( 'type' => 'get',  'controller' => 'admins', 'action' => 'search' ) );

echo $this->Form->label( 'Αναζήτηση Χρήστη: ' );
echo $this->Form->text( 'name', array(  'value' => isset( $this->params['url']['name'] )?$this->params['url']['name']:'' ) );
if( isset( $this->params['url']['banned'] ) && $this->params['url']['banned'] == 1 ){
    $check = 'checked';
} else {
    $check = 'unchecked';
}
echo $this->Form->checkbox( 'banned', array( 'checked' => $check ) );
echo $this->Form->label( 'Banned' );
echo $this->Form->button('Αναζήτηση', array('type'=>'submit'));
echo $this->Form->end();
?>
<table border="1">
    <tr>
        <th>   </th>  
        <th><?php echo $this->Paginator->sort('Username', 'User.username'); ?></th>
        <th><?php echo $this->Paginator->sort('Όνομα', 'Profile.firstname'); ?></th>
        <th><?php echo $this->Paginator->sort('Επίθετο', 'Profile.lastname'); ?></th>
        <th>email</th>
        <th class="banhead">Banned Χρήστες</th>
    </tr
<tbody>
<?php

echo $this->Session->flash();

if( $results != array() ){

    /*records per page*/
    $current_recs = $this->Paginator->counter( array( 'format' => '%count%' ) ); 
    /*change type from String to int*/
    settype( $current_recs, "integer");

    $page_num = $this->Paginator->counter( array( 'format' => '%pages%' ) );
    settype( $page_num, "integer" );

    $page = $this->Paginator->current();
    $count = ($page-1)*$limit;

    foreach( $results as $user ){

        $count++;
        echo '<tr>';
        echo "<td> {$count} </td>";
        echo "<td>".$this->Html->link( $user['User']['username'], 
             "/profiles/view/{$user['Profile']['id']}" )."</td>";

        echo "<td> {$user['Profile']['firstname']}</td>";
        echo "<td> {$user['Profile']['lastname']} </td>";
        echo "<td>" . $user["Profile"]["email"] . "</td>";

        echo "<td class= \"banned\">".( ($user['User']['banned'])?$this->Html->link('unban',
                                                    array("controller" => "profiles", "action" => "unban",
                                                    $user["Profile"]["id"])):
                                                    
                                                    $this->Html->link('ban',
                                                    array("controller" => "profiles", "action" => "ban",
                                                    $user["Profile"]["id"])) )."</td>";
        echo "</tr>";


    }?>
<div class = "admpaginator" >
<?php
    if( $page_num > 1 ){

        /*
         *Pass params in paginator options in case form is submited
         *so as to hold params in new page
         */
        if(isset( $this->params['url']['name'] ) || isset( $this->params['url']['banned'] ) ) {

            $queryString = "name={$this->params['url']['name']}&banned={$this->params['url']['banned']}";
            $options = array( 'url'=>array( 'controller' => 'admins', 'action' => 'search',
                    '?' => $queryString ) );
            $this->Paginator->options( $options );
       }
        /* pagination anv*/
        echo $paginator->prev('« Προηγούμενη ',null, null, array( 'class' => 'disabled' ) );

        /* show pages */
        echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' '));

        /* Shows the next link */
        echo $paginator->next(' Επόμενη » ', null, null, array('class' => 'disabled' ) );
   }

}
?>
</div>
</tbody>
</table>
</div>
