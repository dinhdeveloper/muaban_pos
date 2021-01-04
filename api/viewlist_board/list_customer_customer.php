<?php
$sql = "SELECT * FROM `tbl_customer_customer` WHERE 1=1";

$error = array();
if (isset($_REQUEST['id_business'])) {
    if ($_REQUEST['id_business'] == '') {
        unset($_REQUEST['id_business']);
        $error['id_business'] = "Nhập id_business";
    } else {
        $id_business = $_REQUEST['id_business'];
        $sql .= " AND `id_business` = '{$id_business}'";


        if (isset($_REQUEST['id_account'])) {
            if ($_REQUEST['id_account'] == '') {
                unset($_REQUEST['id_account']);
            } else {
                $id_account = $_REQUEST['id_account'];
                $sql .= " AND `id_account` = '{$id_account}'";
            }
        }

        if (isset($_REQUEST['id_customer'])) {
            if ($_REQUEST['id_customer'] == '') {
                unset($_REQUEST['id_customer']);
            } else {
                $id_customer = $_REQUEST['id_customer'];
                $sql .= " AND `id` = '{$id_customer}'";
            }
        }


        if (isset($_REQUEST['filter'])) {
            if ($_REQUEST['filter'] == '') {
                unset($_REQUEST['filter']);
            } else {
                $filter = htmlspecialchars($_REQUEST['filter']);
                $sql .= " AND `customer_code` LIKE '%{$filter}%'";
                $sql .= " OR `customer_name` LIKE '%{$filter}%'";
                $sql .= " OR `customer_phone` LIKE '%{$filter}%'";
            }
        }
    }
} else {
    $error['id_business'] = "Nhập id_business";
}

$customer_arr = array();

$total = count(db_fetch_array($sql));
$limit = 20;
$page = 1;

if (isset($_REQUEST['limit']) && !empty($_REQUEST['limit'])) {
    $limit = $_REQUEST['limit'];
}
if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
    $page = $_REQUEST['page'];
}


$total_page = ceil($total / $limit);
$start = ($page - 1) * $limit;
$sql .= " ORDER BY `tbl_customer_customer`.`id` LIMIT {$start},{$limit}";

if (empty($error)) {
    $customer_arr['success'] = 'true';
    $customer_arr['total'] = strval($total);
    $customer_arr['total_page'] = strval($total_page);
    $customer_arr['limit'] = strval($limit);
    $customer_arr['page'] = strval($page);
    $customer_arr['data'] = array();
    $result = db_qr($sql);
    $nums = db_nums($result);
    if ($nums > 0) {
        while ($row = db_assoc($result)) {
            $customer_item = array(
                'id_customer' => $row['id'],
                'customer_name' => htmlspecialchars_decode($row['customer_name']),
                'customer_code' => htmlspecialchars_decode($row['customer_code']),
                'customer_phone' => htmlspecialchars_decode($row['customer_phone']),
                'customer_address' => htmlspecialchars_decode($row['customer_address']),
                'customer_email' => htmlspecialchars_decode($row['customer_email']),
                'customer_birthday' => htmlspecialchars_decode($row['customer_birthday']),
                'customer_sex' => htmlspecialchars_decode($row['customer_sex']),
                'customer_point' => htmlspecialchars_decode($row['customer_point']),
                'customer_level' => "",
                'customer_taxcode' => htmlspecialchars_decode($row['customer_taxcode']),
            );


            $sql_level = "SELECT * FROM `tbl_customer_point`";
            $result_level = db_qr($sql_level);
            $nums_level = db_nums($result_level);
            if ($nums_level > 0) {
                while ($row_level = db_assoc($result_level)) {
                    if ($row['customer_point'] >= $row_level['customer_point']) {
                        $customer_item['customer_level'] = $row_level['customer_level'];
                    }
                }
            }


            array_push($customer_arr['data'], $customer_item);
        }
        reJson($customer_arr);
    } else {
        returnError("Không có khách hàng");
    }
} else {
    $error['success'] = "false";
    $error['message'] = "Lấy danh sách không thành công";
    reJson($error);
}
