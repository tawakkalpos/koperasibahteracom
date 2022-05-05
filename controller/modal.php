<?php
$db = new mDatabase();
$config = new mConfig();
include($config->getModel('modal'));
//if already post from javascript to this page
if (isset($_POST["post_modal"])) {
    switch ($_POST["post_modal"]) {
        case "getdata_product_bycode":
            $barcode = $_POST["value"];
            $sql = $db->select_where('product', "*", "Barcode = '$barcode' LIMIT 1");
            $results = array();
            if ($sql) {
                $data = $sql[0];
                $photo = "img/product/" . $data['Barcode']. ".jpg";
                if (!file_exists($photo))
                    $photo = "img/takephoto.png";
                $results[] = array('status' => 'success', 'code' => $data['Barcode'], 'name' => $data['ProductName'], 'category' => $data['Category'], 'stock' => $data['Stock'], 'price' => $data['SalesPrice'], 'purchase' => $data['PurchasePrice'], 'imgurl' => BASE_URL.$photo);
            } else {
                $results[] = array('status' => 'failed');
            }
            echo json_encode($results);
            exit;
            break;
        case "getdata_member_byid":
            $id = $_POST["value"];
            $sql = $db->select_where('member', "*", "MemberId = '$id' LIMIT 1");
            $results = array();
            if ($sql) {
                $data = $sql[0];
                $results[] = array('status' => 'success', 'id' => $data['MemberId'], 'name' => $data['Name'], 'gender' => $data['Gender'], 'status_member' => $data['Status']);
            } else {
                $results[] = array('status' => 'failed');
            }
            echo json_encode($results);
            exit;
            break;
        case "print_transaction":
            $id = $_POST["value"];
            $modal = new mModal();
            foreach ($modal->printtransaction($id) as $print) {
                if (strlen($print) > 3)
                    print $print;
            }
            exit;
            break;
        case "getMonthlyTransaction":
            $results = array();
            $labels = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
            for($i = 1; $i < 13; $i++){
                $date = date('Y') . "/" . $i . "/01";
                $sql = $db->select_where('transaction_history', "SUM(GrandTotal) AS Total", "YEAR(Date) = ". date('Y') . " AND MONTH(Date) = " . $i);
                if ($sql) {
                    $data = $sql[0];
                    if($data['Total'] < 1){
                        $data['Total'] = 0;
                    }
                    $results[] = array('value' => $data['Total'], 'label' => $labels[$i-1]);
                }
            }
            echo json_encode($results);
            exit;
            break;
        case "getDailyTransaction":
            $results = array();
            $labels = range(1,date('t'));
            foreach($labels as $label){
                $sql = $db->select_where('transaction_history', "SUM(GrandTotal) AS Total", "DATE(Date) = DATE('" . date('Y-m-') . $label . "')");
                if ($sql) {
                    $data = $sql[0];
                    if($data['Total'] < 1){
                        $data['Total'] = 0;
                    }
                    $results[] = array('value' => $data['Total'], 'label' => $label);
                }
            }
            echo json_encode($results);
            exit;
            break;
        default:
            echo "";
    }
}

if (isset($_POST["post"])) {
    //posted from menu_idcolumn form in add or update menu
    switch ($_POST["post"]) {
        case "dailytransaction":
            $date = $_POST["value"];
            $modal = new mModal();
            print $modal->dailytransaction(true,$date);
            exit;
            break;
        case "dailytransactionmember":
            $data = explode(";",$_POST["value"]);
            $modal = new mModal();
            print $modal->dailytransaction(true,$data[0],$data[1],$data[2]);
            exit;
            break;
        case "delete_product":
            $id = $_POST["value"];
            $sql = $db->update('product', "Status","'deleted'", "Barcode = '$id'");
            exit;
            break;
        case "delete_member":
            $id = $_POST["value"];
            $sql = $db->update('member', "Status","'deleted'", "MemberId = '$id'");
            exit;
            break;
        default:
            echo "";
    }
}
if (isset($_POST["auto_fill"])) {
    switch ($_POST["case"]) {
        case "getProductName":
            $like = "%" . strtolower($db->check_input($_POST["auto_fill"]) . "%");
            $sql = $db->select_where("product", "*", "ProductName LIKE '$like' AND (Status IS NULL OR Status = '') ORDER BY ProductName");
            $results = array();
            if ($sql) {
                foreach ($sql as $data) {
                    $results[] = array('id' => $data['Barcode'], 'label' => $data['ProductName'], 'value' => $data['Barcode']);
                }
                echo json_encode($results);
            }
            exit;
            break;
        case "getMemberName":
            $like = "%" . strtolower($db->check_input($_POST["auto_fill"]) . "%");
            $sql = $db->select_where("member", "*", "Name LIKE '$like' AND Status <> 'deleted' ORDER BY Name");
            $results = array();
            if ($sql) {
                foreach ($sql as $data) {
                    $results[] = array('id' => $data['MemberId'], 'label' => $data['Name'], 'value' => $data['MemberId']);
                }
                echo json_encode($results);
            }
            exit;
            break;
        case "getCategory":
            $like = "%" . strtolower($db->check_input($_POST["auto_fill"]) . "%");
            $sql = $db->select_where("product", "DISTINCT(Category) AS Category", "Category LIKE '$like' ORDER BY Category");
            $results = array();
            if ($sql) {
                foreach ($sql as $data) {
                    $results[] = array('id' => $data['Category'], 'label' => $data['Category'], 'value' => $data['Category']);
                }
                echo json_encode($results);
            }
            exit;
            break;
        default:
            echo "";
    }
}

/**
 * Form Transaction Process data
 * @return JSON status data
 */
if (isset($_POST["tr_submit"])) {
    $results = array();
    if (count($_POST['code']) > 0) {
        $tbcolumns = array('Date', 'Time', 'TransactionId', 'UserId', 'MemberId', 'PaymentMethod', 'PaymentReference', 'TotalPayment', 'GrandTotal', 'MoneyChanges');
        $getdata = array();
        $getdata['date'] = date('Y-m-d');
        $getdata['time'] = date('H:i:s');
        $sql = $db->select_where('transaction_history', 'MAX(TransactionId)', 'DATE(Date) = DATE(CURDATE())');
        if ($sql)
            $transactionid = date('ymd') . sprintf("%03d", substr($sql[0][0], 6) + 1);
        else
            $transactionid = date('ymd') . '0001';
        $getdata['transactionid'] = $transactionid;
        $getdata['userid'] = USER_ID;
        $getdata['memberid'] = $_POST['tr_memberid'];
        $getdata['payment_method'] = $_POST['tr_payment_method'];
        $getdata['payment_referense'] = $_POST['tr_payment_referense'];
        $getdata['payment'] = $_POST['tr_payment'];
        $getdata['grandtotal'] = $_POST['grandtotal'];
        $getdata['moneychanges'] = $_POST['moneychanges'];

        $columns = $db->make_columns($tbcolumns);
        $values = $db->make_values($getdata);
        $sql = $db->insert('transaction_history', $columns, $values);
        if ($sql) {
            $tbcolumns = array('TransactionId', 'Barcode', 'Price', 'Qty', 'TotalPrice', 'Capital', 'Profit', 'Date');
            $status = true;
            $totalcapital = 0;
            $totalitems = 0;
            foreach ($_POST['code'] as $i => $code) {

                $sql2 = $db->select_where('product', 'Stock, SalesQty, TotalSales, SalesPrice, PurchasePrice', "Barcode = '" . $code . "'");
                $data = $sql2[0];
                $capital = $data['PurchasePrice'] * $_POST['qty'][$i];
                $profit = $_POST['total'][$i] - $capital;
                $totalcapital = $totalcapital + $capital;
                $totalitems = $totalitems + $_POST['qty'][$i];
                $tbvalues = array($transactionid, $code, $_POST['price'][$i], $_POST['qty'][$i], $_POST['total'][$i], $capital, $profit, $getdata['date']);
                $sql = $db->insert('transaction', $db->make_columns($tbcolumns), $db->make_values($tbvalues));
                if ($sql) {
                    $newstock = $data['Stock'] - $_POST['qty'][$i];
                    $newsalesqty = $data['SalesQty'] + $_POST['qty'][$i];
                    $newsalestotal = $data['TotalSales'] + $_POST['total'][$i];
                    $sql = $db->update('product', 'Stock, SalesQty, TotalSales', $newstock . ',' . $newsalesqty . ',' . $newsalestotal, "Barcode = '" . $code . "'");
                    if (!$sql) {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
            }
            $totalprofit = $getdata['grandtotal'] - $totalcapital;
            $sql = $db->update('transaction_history', 'TotalCapital,TotalProfit,TotalItems', "'" . $totalcapital . "','" . $totalprofit . "','" . $totalitems . "'", "TransactionId = '" . $transactionid . "'");
            if ($status) {
                $results[] = array('status' => 'success', 'transactionid' => $transactionid);
                $config->createlog("Create transaction with Transaction Ref = #" . $transactionid);
            } else {
                $results[] = array('status' => 'failed[03]');
            }
        } else {
            $results[] = array('status' => 'failed[02]');
        }
    } else {
        $results[] = array('status' => 'failed[01]');
    }
    echo json_encode($results);
    exit;
}


//Register or Update new Member
if (isset($_POST["mb_submit"])) {
    $results = array();
    $sql = $db->select_where('member', 'MemberId', "MemberId='" . $_POST["mb_id"] . "'");
    $tbcolumns = array('MemberId', 'Name', 'Gender', 'Status', 'Date');
    $_POST['Date'] = date('Y-m-d');
    unset($_POST['mb_submit']);
    $columns = $db->make_columns($tbcolumns);
    $values = $db->make_values($_POST);
    if ($sql) {
        $sql = $db->update('member', $columns, $values, "MemberId = '" . $_POST["mb_id"] . "'");
        if ($sql) {
            $sql = $db->update('transaction_history', 'MemberId', "'" . $_POST["mb_id"] . "'", "MemberId = '" . $_POST["mb_id"] . "'");
        }
    } else {
        $sql = $db->insert('member', $columns, $values);
    }
    if ($sql) {
        $results[] = array('status' => 'success', 'member_id' => $_POST["mb_id"]);
        $config->createlog("Register or Update Member #" . $_POST["mb_id"]);
    } else {
        print $db->get_error();
        $results[] = array('status' => 'failed[01]');
    }
    echo json_encode($results);
    exit;
}

//Register or Update new Member
if (isset($_POST["pr_submit"])) {
    unset($_POST['pr_submit']);
    $results = array();
    $directory = "img/product/";
    $filename = $_POST["imgtmp"];
    $newname = $_POST["pr_barcode"] . ".jpg";
    if (file_exists("img/temp/" . $filename))
        rename("img/temp/" . $filename, $directory . $newname);
    $tbcolumns = array('PurchaseDate', 'Barcode', 'PurchasePrice', 'SalesPrice', 'Qty');
    $getdata = array();
    $getdata['purchasedate'] = date('Y-m-d');
    $getdata['barcode'] = $_POST['pr_barcode'];
    if ($_POST['pr_purchase_category'] == "allitem")
        $getdata['purchaseprice'] = intval($_POST['pr_purchase'] / $_POST['pr_qty']);
    else
        $getdata['purchaseprice'] = intval($_POST['pr_purchase']);
    $getdata['salesprice'] = intval($_POST['pr_sales']);
    $getdata['qty'] = intval($_POST['pr_qty']);
    $sql = $db->insert('product_history', $db->make_columns($tbcolumns), $db->make_values($getdata));
    if ($sql) {
        $tbcolumns = array('PurchaseDate', 'Barcode', 'PurchasePrice', 'SalesPrice', 'Stock', 'ProductName', 'Category','Status');
        $getdata['productname'] = $_POST['pr_name'];
        $getdata['category'] = $_POST['pr_category'];
        $getdata['status'] = '';
        $sql = $db->select_where('product', 'Barcode,Stock', "Barcode = '" . $getdata['barcode'] . "'");
        if ($sql) {
            $getdata['qty'] = intval($sql[0]['Stock']) + $getdata['qty'];
            $sql = $db->update('product', $db->make_columns($tbcolumns), $db->make_values($getdata), "Barcode = '" . $getdata['barcode'] . "'");
            if ($sql) {
                $results[] = array('status' => 'success', 'barcode' => $getdata['barcode']);
                $config->createlog("Update Product #" . $getdata['barcode']);
            } else {
                $results[] = array('status' => 'failed[02]');
            }
        } else {
            $sql = $db->insert('product', $db->make_columns($tbcolumns), $db->make_values($getdata));
            if ($sql) {
                $results[] = array('status' => 'success', 'barcode' => $getdata['barcode']);
                $config->createlog("Add Product #" . $getdata['barcode']);
            } else {
                $results[] = array('status' => 'failed[03]');
            }
        }
    } else {
        $results[] = array('status' => 'failed[01]');
    }
    echo json_encode($results);
    exit;
}
