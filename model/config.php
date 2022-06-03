<?php
//by: JOKO SANTOSA (0877 5829 3281) || mailto:jspos.info@gmail.com
//Config is the page for define any function to used in the systems.

//----------------------------configure the database-----------------------------//
DEFINE("BASE_URL", "https://jspos.my.id/");
DEFINE("SUB_URL", "");
DEFINE("FILE_LOG", "log.txt");
//----------------------------End of configure the database-----------------------------//

//----------------------------configure the default settings or variable-----------------------------//
//Start Session
session_start();

//set time zone
date_default_timezone_set('Asia/Jakarta');

define('DEFAULT_DATE', 'd M Y'); //define default date format

//Define USER data
if (isset($_SESSION['username']) || !empty($_SESSION['username'])) {
    DEFINE("USER_USERNAME", $_SESSION['username']);
    DEFINE("USER_FULLNAME", $_SESSION['fullname']);
    DEFINE("USER_ID", $_SESSION['userid']);
} else {
    DEFINE("USER_USERNAME", "");
    DEFINE("USER_FULLNAME", "");
    DEFINE("USER_ID", "");
}
class mConfig
{
    function htaccess()
    {
        //expands the url
        $url = $_SERVER['REQUEST_URI'];

        if (!empty(SUB_URL))
            $url = str_replace("/" . SUB_URL . "/", "", $url);
        $parse_url = explode("/", $url);
        $file_location = "";
        // $this->checklogin();
        if (isset($parse_url[1]) && !empty($parse_url[1])) {
            $file_location = "view";
            foreach ($parse_url as $i => $folder) {
                if ($i < count($parse_url) - 1)
                    $file_location .= "/" . $folder;
                else
                    $file_location .= '.php';
            }
        }

        if ($file_location !== "") {
            if($this->loadpage() == "userregister"){
                print "Register New User";
            } else if (file_exists($file_location)) {
                include($this->getModel("database"));
                include($this->getContoller("config"));
                //check user access
                $this->checklogin();

                if ($this->checkaccess($this->loadpage())) {
                    // $this->pageaccess($this->loadpage());

                    include($config->getContoller("modal"));
                    //require header pages
                    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
                        require $this->getView("header");
                    }
                    include($file_location);
                    //require footer page
                    require $this->getView("footer");
                    exit;
                } else {
                    require $this->getView("header");
                    require $this->getView("404notfound");
                    require $this->getView("footer");
                    exit;
                }
            
            } else {
                include($this->getModel("database"));
                include($this->getContoller("config"));
                //check user access
                $this->checklogin();
                require $this->getView("header");
                require $this->getView("404notfound");
                require $this->getView("footer");
                exit;
            }
        } else {
            include($this->getModel("database"));
            include($this->getContoller("config"));
            require $this->getView("header");
        }
    }

    /**
     * CallController file
     * @param page controller selected
     * @return controller file
     */

    function getContoller($page)
    {
        return "controller/" . $page . ".php";
    }

    /**
     * Call Model file
     * @param page model selected
     * @return model file
     */

    function getModel($page)
    {
        return "model/" . $page . ".php";
    }

    /**
     * Call View file
     * @param page view selected
     * @return view file
     */

    function getView($page)
    {
        return "view/" . $page . ".php";
    }

    /**
     * @return menu list from table menu
     */
    function getMenu()
    {
        $dbconfig = new mDatabase();
        $sql = $dbconfig->select_where(TB_MENU, "*", "Category = 'Menu' ORDER BY OrderMenu");
        if ($sql) {
            foreach ($sql as $data) {
                if ($this->checkaccess($data['MenuId'])) {
                    if ($data['TypePage'] == "modal") {
                        print '<li class="nav-item ' . $this->navbaractive($data['MenuId']) . '">
                        <a class="nav-link" href="' . $data['MenuId'] . '#" data-toggle="modal" data-target="#' . $data['MenuId'] . '">
                            <i class="fas fa-fw fa-list"></i>
                            <span>' . $data['Description'] . '</span></a>
                    </li>';
                    } else {
                        print '<li class="nav-item ' . $this->navbaractive($data['MenuId']) . '">
                        <a class="nav-link" href="' . BASE_URL . $data['MenuId'] . '/">
                            <i class="fas fa-fw fa-list"></i>
                            <span>' . $data['Description'] . '</span></a>
                    </li>';
                    }
                }
            }
        }
    }

    //default date format 
    function defaultdate($stringdate)
    {
        return date(DEFAULT_DATE, strtotime($stringdate));
    }

    //function to login to the system
    function login()
    {
        $dbconfig = new mDatabase();
        $username = mysqli_real_escape_string($dbconfig->conn(), $_POST["username"]);
        $password = mysqli_real_escape_string($dbconfig->conn(), md5($_POST["password"]));
        $sql = $dbconfig->select_where(TB_USER, "*", "`username` = '$username' AND `password` = '$password'");
        if ($sql) {
            $data = $sql[0];
            $_SESSION["username"] = $data['username'];
            $_SESSION["userid"] = $data['userid'];
            $_SESSION["fullname"] = $data['fullname'];
            //insert data user login
            $dbconfig->update(TB_USER, "status", "'online'", "userid = '" . $data['userid'] . "'");

            //insert log data to the log.txt
            // $this->createlog("<b>Login</b> to the system");

            //check user previous page access
            $previuspage = BASE_URL."summary/";
            if (isset($_SESSION['previouspage']) || !empty($_SESSION['previouspage'])) {
                $previuspage = $_SESSION['previouspage'];
                unset($_SESSION['previouspage']);
            }
            header("location:$previuspage");
        } else {
            print '<div class="alert alert-danger alert-dismissible fixed-center">';
            print '<button class="close" data-dismiss="alert">&times;</button>';
            print '<strong>username or password incorect.. </strong>Please <kbd><a class="text-light" href="#" data-toggle="modal" data-target="#loginModal">Login</a></kbd> again..';
            print '</div>';
        }
    }

    //function to logut from the system
    function logout()
    {
        //Update user to offline status
        $dbconfig = new mDatabase();
        $sql = $dbconfig->update(TB_USER, "status", "'offline'", "userid = '" . USER_ID . "'");

        //insert log data to the log.txt
        $this->createlog("<b>Logout</b> from the system");

        //clear all session login
        session_destroy();

        header("location:" . BASE_URL);
        exit;
    }

    function self_post()
    {
        return $_SERVER['REQUEST_URI'];
    }


    //Function Check Login for the user
    function checklogin()
    {
        if (empty(USER_USERNAME) || USER_USERNAME == "") {
            // $_SESSION["previouspage"] = implode("/", $this->page());
            require $this->getView("header");
            include($this->getView("login"));
            require $this->getView("footer");
            exit;
        }
    }

    //Function Check Page access for the user
    function checkaccess($page)
    {
        //Check user can access to 'User Setting menu'
        $dbconfig = new mDatabase();
        if ($dbconfig->check_column(TB_USERACCESS, $page)) {
            $sql = $dbconfig->select_where(TB_USERACCESS, $page, "`user_id` = " . USER_ID . " AND `$page` = 1");
            if ($sql)
                return true;
            else
                return false;
        } else {
            return true;
        }
    }

    //Function Check Page access for the user
    function pageaccess($page = null)
    {
        //Check user can access to 'User Setting menu'
        if ($page == null)
            $page = $this->loadpage();

        if (!$this->checkaccess($page)) {
            print '<p align="center">Sorry, you cant access this page. Please back to <b><a href="' . BASE_URL . '">Home</a></b> and login with another user or contact the Admin for give you access this page!</p>';
            exit;
        }
    }

    //function to expand or parsing the url loaded
    function page()
    {
        $page = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
        );
        return $page = explode("/", $page);
    }

    //function for load page
    function loadpage()
    {
        $page = $this->page();
        $x = count($page) - 2;
        return $page[$x];
    }

    //function for load navbar page
    function navbaractive($title)
    {
        if ($this->loadpage() == $title) {
            return "active";
        }
    }

    //function for create log (GENERAL)
    function createlog($description)
    {
        $file = fopen(FILE_LOG, "a+") or die("Unable to open file!");
        $txt = "\n<tr><td class='text-center'>" . date("Y/m/d H:i:s") . "</td><td class='text-center'>" . USER_FULLNAME . "( " . sprintf("%03s",USER_ID) . ")</td><td>" . $description . "</td></tr>";
        fwrite($file, $txt);
        fclose($file);
    }

    //function for create log page access
    function createlogpage()
    {
        global $url, $logfile, $firstname, $employee_number;
        $newurl =  sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
        );
        if ($newurl !== $url) {
            $page = explode("/", $newurl);
            $x = count($page) - 2;
            $file = fopen($logfile, "a+") or die("Unable to open file!");
            $txt = "\n<tr><td class='text-center'>" . date("Y/m/d H:i:s") . "</td><td class='text-center'>" . $firstname . "(GA " . sprintf("%03s", $employee_number) . ")</td><td>Access to <b>" . $page[$x] . "</b> page</td></tr>";
            fwrite($file, $txt);
            fclose($file);
            $_SESSION["url"] = $newurl;
        }
    }

    //Function for diplay menu to page can be access for the user
    function pagemenu($category)
    {
        $dbconfig = new mDatabase();
        $sql = $dbconfig->selectwhere(TB_MENU, "*", "`category` = '$category'");
        foreach ($sql as $data) {
            $sql2 = $dbconfig->selectwhere(TB_USERACCESS, $data['menuid'], "`userid` = '" . USER_ID . "'");
            if ($sql2 >= 1) {
                $folderpage = str_replace("_", "", $data['menuid']);
                echo '<a class="collapse-item" href="' . BASE_URL . 'menu/' . strtolower(str_replace(" ", "", $category)) . '/' . $folderpage . '/">' . $data['description'] . '</a>';
            } else {
                echo "";
            }
        }
    }

    //Function for display breadcumb (user location on the website)
    function breadcumb()
    {
        $dbconfig = new mDatabase();
        $page = $this->loadpage();
        $sql = $dbconfig->select_where(TB_MENU, "*", "`MenuId` = '$page'");
        $submenu = "";
        $data = $sql[0];
        $submenu .= '<li class="breadcrumb-item">' . $data['Category'] . '</li>';
        $submenu .= '<li class="breadcrumb-item active">' . $data['Description'] . '</li>';

        print '<ul class="breadcrumb">';
        print '<li class="breadcrumb-item"><a href="' . BASE_URL . '">Home </a></li>';
        print $submenu;
        print '</ul>';
    }

    //function to make ucwords a string or words
    function ucword($string)
    {
        return ucwords(strtolower($string));
    }

    //Function to check file exist in the directory or URL
    function does_url_exists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        print $code;
        if ($code == 200) {
            $status = true;
            print $url;
        } else {
            $status = false;
        }
        curl_close($ch);

        return $status;
    }

    //Function to display list of tool
    function displaylist($labels, $value)
    {
        foreach ($labels as $index => $label) {
            if ($index % 2 !== 0) {
                $bg = "bg-gray-100";
            } else {
                $bg = "";
            }
            print '
            <div class="row pt-2 border-bottom ' . $bg . '">
                <div class="col-4 font-weight-bold text-primary">' . $label . '</div>
                <div class="col-8">: ' . $value[$index] . '</div>
            </div>';
        }
    }

    //function to display image <img>
    function image($name, $css_width, $id)
    {
        $image = '<img src="' . BASE_URL . 'img/' . $name . '"class="' . $css_width . ' d-block img-fluid img-thumbnail" id="' . $id . '" />';
        return $image;
    }

    //convert date from database to display in the description or etx
    function dateformat($date)
    {
        if ($date != null) {
            $dateformat = date("d/m/Y", strtotime($date));
        } else {
            $dateformat = "";
        }
        return $dateformat;
    }

    //convert date from database to display in row table
    function datetable($date)
    {
        if ($date != null) {
            $dateformat = date("Y/m/d", strtotime($date));
        } else {
            $dateformat = "";
        }
        return $dateformat;
    }
    function uploadimage(){
        $newname = $_POST["filename"];
        $directory = "img/temp/";
        list($w, $h) = getimagesize($_FILES["imagetemp"]['tmp_name']);
        //define new size for image
        $nw         = 720;
        $nh         = 720 * $h / $w;
        $tumb       = imagecreatetruecolor($nw, $nh);
        if ($_FILES["imagetemp"]['type'] == 'image/jpeg')
            $image = imagecreatefromjpeg($_FILES["imagetemp"]['tmp_name']);
        elseif ($_FILES["imagetemp"]['type'] == 'image/gif')
            $image = imagecreatefromgif($_FILES["imagetemp"]['tmp_name']);
        elseif ($_FILES["imagetemp"]['type'] == 'image/png')
            $image = imagecreatefrompng($_FILES["imagetemp"]['tmp_name']);

        imagecopyresampled($tumb, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagejpeg($tumb, $directory . $_FILES["imagetemp"]['name'], 240);
        rename($directory . $_FILES["imagetemp"]['name'], $directory . $newname);
        $results = array('status'=>'success');
        echo json_encode($results);
        exit;
    }
}
