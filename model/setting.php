<?php
class Setting
{
    //function to display form id
    function form_menuid($menuid = "")
    {
        $db = new mDatabase();
        $sql = $db->select_where(TB_MENU, "*", "`Menuid`= '" . $menuid . "'");
        if (!$sql) {
            $menu_description = $menu_category = $menu_number = "";
        } else {
            foreach ($sql as $data) {
                $menu_description = $data["Description"];
                $menu_category = $data["Category"];
                $menu_number = $data["No"];
            }
        }
        ?>
        <!-- Print form for add or update user or employee    -->
        <div class="input-group input-group-sm">
            <label for="menu_description" class="mr-sm-2 col-md-3 text-md-right">Description:</label>
            <input type="text" class="form-control mb-2 mr-sm-2 col-md-7" name="menu_description" id="menu_description" placeholder="Description..." onchange="makeid(this,'#menu_idcolumn')" value="<?= $menu_description; ?>" required>
        </div>
        <div class="input-group input-group-sm">
            <label for="menu_category" class="mr-sm-2 col-md-3 text-md-right">Category:</label>
            <input type="text" class="form-control mr-sm-2 col-md-7" onkeyup="autofill(this,'#category_result',1)" name="menu_category" id="menu_category" placeholder="Category..." value="<?= $menu_category; ?>" required>
            <ul id="category_result" class="dropdown-menu animated-fade-in col-11 no-gutters">
            <input type="hidden" name="menu_number" id="menu_number" value="<?= $menu_number; ?>">
        </div>
<?php
    }
}
?>