<?php
echo $this->Form->create( 'Admin' ,array( 'type' => 'get',  'url' => '/admin/search' ) );
<<<<<<< HEAD
echo $this->Form->label( 'Αναζήτηση Χρήστη: ' );
echo $this->Form->text( 'name', array(  'value' => isset( $this->params['url']['name'] )?$this->params['url']['name']:'' ) );
if( isset( $this->params['url']['banned'] ) && $this->params['url']['banned'] == 1 ){
    $check = 'checked';
} else {
    $check = 'unchecked';
}
echo $this->Form->checkbox( 'banned', array( 'checked' => $check ) );
echo $this->Form->label( 'Banned' );
=======
echo $this->Form->label( 'Αναζήτηση Χρήστη' );
echo $this->Form->text( 'name' );
echo $this->Form->checkbox( 'banned' );
>>>>>>> 7468dcc24fabf76fe6d0ab70b01384e88a9ace82
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
        <th>Banned Χρήστες </th>
    </tr
<tbody>
<?php

echo $this->Session->flash();
if( isset($results) ){
    $count = 0;

    foreach( $results as $user ){
        ++$count;
        echo '<tr>';
        echo "<td> {$count} </td>";
        echo "<td>".$this->Html->link( $user['User']['username'], 
             "/profiles/view/{$user['Profile']['id']}" )."</td>";

        echo "<td> {$user['Profile']['firstname']}</td>";
        echo "<td> {$user['Profile']['lastname']} </td>";
        echo "<td>" . $user["Profile"]["email"] . "</td>";

        echo "<td>".( ($user['User']['banned'])?$this->Html->link('unban',
                                                    array("controller" => "profiles", "action" => "unban",
                                                    $user["Profile"]["id"])):
                                                    
                                                    $this->Html->link('ban',
                                                    array("controller" => "profiles", "action" => "ban",
                                                    $user["Profile"]["id"])) )."</td>";
        echo "</tr>";


    }
    /* pagination anv*/
    echo $paginator->prev('« Προηγούμενη ',null, null, array( 'class' => 'disabled' ) );

        /* show pages */
    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' '));

        /* Shows the next link */
    echo $paginator->next(' Επόμενη » ', null, null, array('class' => 'disabled' ) );

}
?>
</tbody>
</table>
