<?php
echo $this->Form->create( 'Admin' ,array(  'url' => '/admin/search' ) );
echo $this->Form->label( 'Αναζήτηση Χρήστη' );
echo $this->Form->text( 'name' );
echo $this->Form->button('Αναζήτηση', array('type'=>'submit'));
echo $this->Form->end();
?>
<table border="1">
    <tr>
        <th>Username</th>
        <th>FirstName</th>
        <th>Lastname</th>
        <th>Banned</th>
    </tr>
<?php
if( isset($results) ){
    $count = 0;

    foreach( $results as $result ){
        echo '<tr>';
        echo "<td>";
        echo $this->Html->link( $result['User']['username'], "/profiles/view/{$result['Profile']['id']}" );
        echo "</td>";
        echo "<td> {$result['Profile']['firstname']}</td>";
        echo "<td> {$result['Profile']['lastname']} </td>";
        echo "<td>".( ($result['User']['banned'])?'Yes':'No' )."</td>";
        echo "</tr>";
    }

}
?>
</table>
