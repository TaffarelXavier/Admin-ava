<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../../autoload.php';

    $path = dirname(getcwd());

    $file_name = date('d-m-Y H-i-s') . ' ' . DB_NAME . '.sql';

    $dbname = val_input::sani_string('dbname');
    
    $connection->query("use $dbname;");
    
    $Backup_Class = new Backup_DB($connection, $dbname);

    $Backup_Class->export(BACKUP_PASTA . $file_name);
    
    ?>
    <a href="backup/<?php echo $file_name; ?>" class="btn" download="">
        <i class="icon-download"></i>
        <?php echo $file_name; ?></a>
        <?php
}
