<div class="admin banned-table">
    <table>
    <thead>
    <th><?php echo $this->Paginator->sort('Username', 'User.username'); ?></th>
    <th><?php echo $this->Paginator->sort('Όνομα', 'Profile.firstname'); ?></th>
    <th><?php echo $this->Paginator->sort('Επίθετο', 'Profile.lastname'); ?></th>
        <th>email</th>
        <th>#σπίτια</th>
        <th>Σύνδεσμος Προφίλ</th>
        <th>Unban</th>
    </thead>
    <tbody>
    <?php
        foreach ($banned as $user):
            echo "<tr>";
                echo "<td>" . $user["User"]["username"] . "</td>";
                echo "<td>" . $user["Profile"]["firstname"] . "</td>";
                echo "<td>" . $user["Profile"]["lastname"] . "</td>";
                echo "<td>" . $user["Profile"]["email"] . "</td>";
                echo "<td>" . count($user["House"]) . "</td>";
                echo "<td>" . $this->Html->link('profile', 
                                array("controller" => "profiles", "action" => "view", 
                                    $user["Profile"]["id"])) . "</td>";
                echo "<td>" . $html->link('unban',
                                array("controller" => "profiles", "action" => "unban",
                                    $user["Profile"]["id"])) . "</td>";
            echo "</tr>";
        endforeach;
    ?>
    </tbody>
    </table>
</div>
