<?php
// بيانات الاتصال بقاعدة البيانات
$host = "sqlXXX.infinityfree.com"; // استبدل XXX بالمعلومات من InfinityFree
$user = "your_db_user"; // اسم المستخدم
$pass = "your_db_password"; // كلمة المرور
$dbname = "your_db_name"; // اسم قاعدة البيانات

// الاتصال بقاعدة البيانات
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// معالجة رفع الملف
if (isset($_POST['submit'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];

    // تحديد مجلد الرفع
    $uploadDir = "uploads/";
    $fileDestination = $uploadDir . basename($fileName);

    // التحقق من نوع وحجم الملف
    $allowedTypes = ['jpg', 'png', 'pdf', 'zip', 'mp4'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    if (!in_array(strtolower($fileExt), $allowedTypes)) {
        die("تنسيق الملف غير مدعوم!");
    }

    if ($fileSize > 10000000) { // 10MB كحد أقصى
        die("حجم الملف كبير جدًا!");
    }

    // رفع الملف وتخزين بياناته في قاعدة البيانات
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        $sql = "INSERT INTO files (file_name, file_size, file_url) VALUES ('$fileName', '$fileSize', '$fileDestination')";
        if ($conn->query($sql) === TRUE) {
            echo "تم رفع الملف بنجاح! الرابط: <a href='$fileDestination'>$fileName</a>";
        } else {
            echo "خطأ في حفظ البيانات.";
        }
    } else {
        echo "حدث خطأ أثناء الرفع.";
    }
}
?>