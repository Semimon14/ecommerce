<?php
session_start();
include 'config.php';

$title = "Contact Us";
ob_start();
?>

<div class="container mt-5 contact-page">
    <h1 class="text-center">ติดต่อเรา</h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <h3>ข้อมูล</h3>
            <p><strong>ชื่อสมาชิก:</strong> นางสาวพัทธมน แก้วนิมิตรชัย</p>
            <p><strong>Facebook:</strong> <a href="https://www.facebook.com/na.mn.k.w.nimitr.chay?mibextid=ZbWKwL" target="_blank">facebook.com/พัทธมน</a></p>
            <p><strong>ชื่อสมาชิก:</strong> นางสาวกัญญารัตน์ หมื่นท้าว</p>
            <p><strong>Facebook:</strong> <a href="https://web.facebook.com/profile.php?id=61564753286098" target="_blank">facebook.com/กัญญารัตน์</a></p>
            <p><strong>ชื่อสมาชิก:</strong> นางสาวดรินภัทร เพาะพืช</p>
            <p><strong>Facebook:</strong> <a href="https://web.facebook.com/arinphati.2024" target="_blank">facebook.com/ดรินภัทร</a></p>
            <p><strong>สถานศึกษา:</strong> วิทยาลัยเทคนิคนครอุบลราชธานี</p>
            <p><strong>สาขา:</strong> แผนกคอมพิวเตอร์ธุรกิจ ปวช.3</p>
        </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'master_template.php';
?>
