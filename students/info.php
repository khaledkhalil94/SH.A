<?php

function displayHTML($student){

	$output = "";
    $output = $output . "<li>";
    $output = $output .  "name: " . $student["name"] . "<br>";
    $output = $output .  "ID: " . $student["sku"] . "<br>";
    $output = $output .  "address:" . " " . $student["address"] . "<br>";
    $output = $output .  "Full Page:" . " " . "<a href=" . BASE_URL . "students/" . $student["sku"] . '/' . ">Details</a>";
    $output = $output .  "</li>";

    return $output;

}

function get_search ($s){
  $results = array();
}

function get_students_search($s){

  require(ROOT_PATH . "inc/database.php");

  if (is_numeric($s) == true){
    try {
      $results = $db->prepare("
        SELECT name, sku, address, img
        FROM students
        WHERE sku LIKE ?
        ORDER BY sku
        ");
      
      $results->bindValue(1, "%" . $s . "%");
      $results->execute();
    } catch (Exception $e) {
      echo "Error!";
      exit;
    }

    $matches = $results->fetchAll(PDO::FETCH_ASSOC);
    return $matches;

} else  if (gettype($s) === "string"){
    try {
      $results = $db->prepare("
        SELECT name, sku, address, img
        FROM students
        WHERE name LIKE ?
        ORDER BY sku
        ");
      
      $results->bindValue(1, "%" . $s . "%");
      $results->execute();
    } catch (Exception $e) {
      echo "Error!";
      exit;
    }

    $matches = $results->fetchAll(PDO::FETCH_ASSOC);
    return $matches;
}
}

function all_count(){
  require(ROOT_PATH . "inc/database.php");

  try {
    $results = $db->query("
      SELECT COUNT(sku)
      FROM students");
  } catch (Exception $e) {
    echo "Error!";
    exit;
  }
   return intval($results->fetchColumn(0));
}

function get_students_subset ($positionStart, $positionEnd){
  require(ROOT_PATH . "inc/database.php");

  $offset = $positionStart - 1;
  $rows = ($positionEnd - $positionStart) + 1;

  try {
    $results = $db->prepare("
      SELECT name, sku, img, address
      FROM students
      ORDER BY sku
      LIMIT ?, ?
      ");
    $results->bindParam(1, $offset, PDO::PARAM_INT);
    $results->bindParam(2, $rows, PDO::PARAM_INT);
    $results->execute();
  } catch (Exception $e) {
    echo "Error!";
    exit;
  }

  $subset = $results->fetchAll(PDO::FETCH_ASSOC);
  return $subset;
}

function get_students_all(){


require(ROOT_PATH . "inc/database.php");

try {
  $results = $db->query("SELECT name, sku, address, img FROM students ORDER BY sku ASC");
} catch (Exception $e) {
  echo "Couldn't connect to database!";
  exit;
}

$students = $results->fetchAll(PDO::FETCH_ASSOC);
    return $students;
  }


  function get_student_single($sku){
    require (ROOT_PATH . "inc/database.php");

    try {
      $results = $db->prepare("SELECT name, sku, address, img FROM students WHERE sku = ?");
      $results->bindParam(1, $sku);
      $results->execute();
    } catch (Exception $e){
      echo "PROBLEM!";
      exit;
    }

    $student = $results->fetch(PDO::FETCH_ASSOC);
    return $student;

  }

?>