<?php
include 'connectdatabase.php';

$course_name = $_POST['courseName'];
$course_description = $_POST['description'];
$section = $_POST['section'];
$credits = $_POST['credits'];
$term = $_POST['term'];
$year = $_POST['year'];

if (is_uploaded_file($_FILES['courseimage']['tmp_name'])) {
    $courseimage = 'course_' . uniqid() . "." . pathinfo(basename($_FILES['courseimage']['name']), PATHINFO_EXTENSION);
    $image_upload_path = "../system/imagecourse/" . $courseimage;
    move_uploaded_file($_FILES['courseimage']['tmp_name'], $image_upload_path);
} else {
    $courseimage = "";
}

$sql = "INSERT INTO `course` (course_name, course_description, course_image, sec_id, credits, semester, year)
        VALUES ('$course_name', '$course_description', '$courseimage', '$section', '$credits', '$term', '$year')";

$result = mysqli_query($conn, $sql);

if ($result) {
    $course_id = mysqli_insert_id($conn);

    $material_names = ['Lesson', 'Material', 'Assignment', 'Quiz', 'Anouncement'];

    foreach ($material_names as $material_name) {
        $material_sql = "INSERT INTO `material` (material_name, course_id) 
                         VALUES ('$material_name', '$course_id')";
        $material_result = mysqli_query($conn, $material_sql);

        if (!$material_result) {
            echo "<script>alert('Failed to save material data.');</script>";
            echo mysqli_error($conn);
            exit;
        }
    }

    echo "<script>alert('Data saved successfully.');</script>";
    echo "<script>window.location = '../regcourse.php';</script>";
} else {
    echo "<script>alert('Failed to save data.');</script>";
    echo mysqli_error($conn);
}
