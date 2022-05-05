<?php
$db = new mDatabase();
$config = new mConfig();
include($config->getModel("setting"));
//if already post from javascript to this page
if (isset($_POST["post"])) {
    //posted from menu_idcolumn form in add or update menu
    switch ($_POST["post"]) {
        case "menu_idcolumn_change":
            $menuid = $_POST["value"];
            $setting = new Setting();
            $setting->form_menuid($menuid);
            break;

        case "menu_delete":
            $menu_number = $_POST["value"];
            $sql = $db->select_where(TB_MENU, "*", "No ='" . $menu_number . "'");
            foreach ($sql as $data) {
                $query = "ALTER TABLE " . TB_USERACCESS . " DROP " . $data['MenuId'];
                $delmenu = $data['Description'];
            }
            $sql = $db->delete(TB_MENU, "`no` = '" . $menu_number . "'");
            if ($sql) {
                //insert log data to the log.txt
                $config->createlog("<b>Delete or Remove</b> menu:<b>" . $delmenu . "</b>");
            }
            break;
        default:
            echo "";
    }
    exit;
}

if (isset($_POST["auto_fill"])) {
    extract($_POST);
    switch ($case) {
        case "1":
            $like = "%" . strtolower(checkinput($auto_fill) . "%");
            $sql = selectwhere($conn, "category", "listofmenu", "category like '$like' GROUP BY category");
            $results = array();
            foreach ($sql as $data) {
                $results[] = array('id' => $data[0], 'label' => $data[0], 'value' => $data[0]);
            }
            echo json_encode($results);
            break;
        default:
            echo "";
    }
    exit;
}

//posted from submit add menu
if (isset($_POST["menu_submit"])) {
    $columnmenu = array("MenuId", "Description", "Category", "OrderMenu");
    $column = $db->make_columns($columnmenu);
    unset($_POST["menu_submit"]);
    $values = $db->make_values($_POST);
    $sql = $db->insert(TB_MENU, $column, $values);
    $results = array();
    if ($sql) {
        $query = "ALTER TABLE useraccess
        ADD " . $_POST['menu_idcolumn'] . " integer(11)";
        $sql = $db->get_query($query);
        //insert log data to the log.txt
        if($sql){
            $results[] = array('status' => 'success', 'data' => $_POST['menu_idcolumn']);
            $config->createlog("<b>Adding</b> New Menu : " . "<b>" . $_POST["menu_description"] . "</b> to List of Menu");
        }else{
            $results[] = array('status' => 'failed');
        }
    }
    echo json_encode($results);
    exit;
}

//posted from submit update menu
if (isset($_POST["menu_update"])) {
    $sql = selectwhere($conn, "*", "listofmenu", "no ='" . $_POST["menu_number"] . "'");
    checkinput($_POST);
    foreach ($sql as $data) {
        if ($data['column'] !== $_POST['menu_idcolumn']) {
            $query = "ALTER TABLE useraccess
        CHANGE " . $data['column'] . " " . $_POST['menu_idcolumn'] . " integer(11)";
            mysqli_query($conn, $query);
        }
    }
    $columnmenu = array("column", "description", "category", "no");
    $column = makecolumn($columnmenu);
    $values = substr(makevalues($_POST), 0, -3);
    $sql = update($conn, "listofmenu", $column, $values, "no =" . $_POST["menu_number"]);
    if ($sql) {
        //insert log data to the log.txt
        createlog("<b>Updating </b> Menu : " . "<b>" . $_POST['menu_idcolumn'] . "</b>");
    }
    exit;
}
