<?php
$id_business = '';
$type_manager = '';

if (isset($_REQUEST['id_business']) && $_REQUEST['id_business'] != '') {
    $id_business = $_REQUEST['id_business'];

    if (isset($_REQUEST['type_manager']) && $_REQUEST['type_manager'] != '') {
        $type_manager = $_REQUEST['type_manager'];

        //chon type_mânger
        switch ($type_manager){
            case "list_level":
                $sql = "SELECT  tbl_customer_point.id as id_level,
                        tbl_customer_point.customer_level as customer_level,
                        tbl_customer_point.customer_point as customer_point, 
                        tbl_customer_point.customer_discount as customer_discount, 
                        COUNT(tbl_customer_point.customer_point) as count_customer
                        FROM tbl_customer_point 
                        LEFT JOIN tbl_customer_customer 
                        ON tbl_customer_point.customer_point = tbl_customer_customer.customer_point
                        WHERE tbl_customer_point.id_business = '$id_business'
                        GROUP BY tbl_customer_point.customer_point";

                $result = $conn->query($sql);
                // Get row count
                $num = mysqli_num_rows($result);

                $level_arr['success'] = 'true';
                $level_arr['data'] = array();
                if ($num>0){
                    while ($row = $result->fetch_assoc()){
                        $level_item= array(
                            'id_level'=>$row['id_level'],
                            'customer_level'=>$row['customer_level'],
                            'customer_point'=>$row['customer_point'],
                            'customer_discount'=>$row['customer_discount'],
                            'count_customer'=>$row['count_customer']
                        );

                        // Push to "data"
                        array_push($level_arr['data'], $level_item);
                    }
                    echo json_encode($level_arr);
                }else{
                    returnError("Không tìm thấy level");
                }

                break;
        }
    } else {
        returnError("Chọn type_manager");
    }
} else {
    returnError("Chọn cửa hàng");
}