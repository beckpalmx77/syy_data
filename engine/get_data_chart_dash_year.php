<?php
// NOTE: $conn (PDO connection) และ $year ถูกสมมติว่ามีการกำหนดค่าไว้แล้ว

// 1. ตัวแปร Array สำหรับเก็บผลลัพธ์ทั้งหมด
$labels = [];      // สำหรับเก็บชื่อสาขา (branch_name)
$data_arrays = []; // สำหรับเก็บข้อมูลยอดขาย (TRD_G_KEYIN) ในรูปแบบ string array

// 2. ดึงข้อมูลสาขา (branch) และชื่อสาขา (branch_name) ทั้งหมดจากตาราง ims_branch
$sql_branches = "SELECT branch, branch_name FROM ims_branch ORDER BY branch";
$stmt_branches = $conn->query($sql_branches);
$branches = $stmt_branches->fetchAll(PDO::FETCH_ASSOC);

// 3. วนลูปตามจำนวนสาขาที่ดึงมาได้
foreach ($branches as $br) {
    $current_branch_code = $br['branch'];
    $current_branch_name = $br['branch_name'];

    // เก็บชื่อสาขาไว้ใน array สำหรับใช้เป็น label
    $labels[] = $current_branch_name;

    // 4. SQL Query เพื่อดึงยอดขายรายเดือนของสาขาปัจจุบัน
    // ******************************************************************************
    // * ข้อควรทราบ: แก้ไขตรรกะ WHERE clause จาก OR เป็น NOT IN เพื่อกรองเอกสารออกอย่างถูกต้อง
    // ******************************************************************************
    $sql_get = "
        SELECT 
            DI_MONTH,
            SUM(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) AS TRD_G_KEYIN
        FROM 
            ims_product_sale_cockpit 
        WHERE 
            DI_YEAR = :year 
            AND BRANCH = :branch 
            AND ICCAT_CODE <> '6SAC08'
            AND DT_DOCCODE NOT IN ('IS', 'IIS', 'IC') 
        GROUP BY 
            DI_MONTH
        ORDER BY 
            CAST(DI_MONTH AS UNSIGNED)
    ";

    // ใช้ Prepared Statement เพื่อความปลอดภัย
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->execute([
        ':year' => $year,
        ':branch' => $current_branch_code
    ]);
    $results = $stmt_get->fetchAll(PDO::FETCH_ASSOC);

    // 5. จัดรูปแบบข้อมูลยอดขายให้เป็น String Array (e.g., "[100.00, 200.00, ...]")
    $monthly_data_values = [];
    foreach ($results as $result) {
        $monthly_data_values[] = $result['TRD_G_KEYIN'];
    }

    // ใช้ implode() เพื่อรวมค่าทั้งหมดเข้าด้วยกัน
    $str_return = "[" . implode(",", $monthly_data_values) . "]";

    // 6. เก็บ String Array ของยอดขายไว้ใน array หลัก
    $data_arrays[] = $str_return;
}

// *** ผลลัพธ์สุดท้ายจะอยู่ในตัวแปร $labels และ $data_arrays ***
// $labels      : Array ของ branch_name ทั้งหมด
// $data_arrays : Array ของ String Array ยอดขายรายเดือนของแต่ละสาขา

/*
ตัวอย่างการแสดงผลลัพธ์ (ตามจำนวนสาขาที่ดึงมาได้)
foreach ($labels as $index => $label) {
    echo "Label " . ($index + 1) . ": " . $label . "<br>";
    echo "Data " . ($index + 1) . ": " . $data_arrays[$index] . "<br>";
}
*/
?>