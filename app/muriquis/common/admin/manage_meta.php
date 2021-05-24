<?php
$ajax = false;
$need_auth = true;
$want_menu = true;
require_once(ROOT_DIR . "/common/init.php");

// Table autoriser sur lesquels boucler
$allowTable = ["record", "production","speaker"];

// Start loop
for($i = 0; $i <= count($allowTable)-1; $i++){
    $table_name = $allowTable[$i];
    $required_fields = [];
    $meta_fields = [];

    $SQL = "SELECT `COLUMN_NAME`, `DATA_TYPE` , `COLUMN_TYPE`, `EXTRA`
        FROM `INFORMATION_SCHEMA`.`COLUMNS` 
        WHERE `TABLE_SCHEMA`='cri_singe' 
            AND `TABLE_NAME`='" . $table_name . "';";
    $res = $dbh->executeQuery($SQL);

    while ($row = $res->fetch_assoc()) {
        $required_fields[] = $row["COLUMN_NAME"];
    }

    $SQL = "select * from `meta` where table_name = '" . $table_name . "'";
    $res = $dbh->executeQuery($SQL);
    while ($row = $res->fetch_assoc()) {
        $meta_fields[] = $row;
    }

    $datas[] = [
        'table_name' => $table_name,
        'required_fields' => $required_fields,
        'meta_fields' => $meta_fields];

}

echo $twig->render('admin/manage_meta.twig', array(
    "datas" => $datas,
    "projectName" => _PROJECT_NAME_
));
