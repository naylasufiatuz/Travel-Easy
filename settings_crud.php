<?php

    require('admin/db_config.php');
    require('admin/essentials.php');
    adminLogin();

    if(isset($_POST['get_general']))
    {
        $q = "SELECT * FROM settings WHERE sr_no =?";
        $values = [1];
        $res = select($q, $values,"i");
        $data = mysqli_fetch_assoc($res);
        $json_data= json_encode($data);
        echo $json_data;
    }

    if(isset($_POST['upd_general']))
    {
        $frm_data = filteration($_POST);

        $q = "UPDATE `settings` SET `site_title`= '?',`site_about`= ? WHERE 1 'sr_no'=?";
        $values = [$frm_data['site_title'],$frm_data['site_about'],1];
        $res = update($q,$values,'ssi');
        echo $res;
    }
?>