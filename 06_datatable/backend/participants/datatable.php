<?php
session_start();

// Redirect user to login page if not logged in or not an admin
if (!isset($_SESSION["username"])) {
  // Permission Denied
  http_response_code(403);
  echo "Permission Denied";
  exit();
}

require_once __DIR__ . "/../../config.php";

// Get all participants from the database
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$searchValue = "%" . $_POST['search']['value'] . "%";
$orderByColumn = $_POST['order'][0]['column'];
$orderByDirection = $_POST['order'][0]['dir'];

$query = "SELECT * FROM participants WHERE name LIKE ? OR email LIKE ?";

if ($orderByColumn == 2) {
  $query .= " ORDER BY name";
} elseif ($orderByColumn == 3) {
  $query .= " ORDER BY email";
} elseif ($orderByColumn == 4) {
  $query .= " ORDER BY register_at";
}
if (!empty($orderByDirection)) {
  $query .= " " . $orderByDirection;
}

$query .= " LIMIT ?, ?";

// var_dump($query);
// die;

if ($stmt = $mysqli->prepare($query)) {
  if ($stmt->bind_param("ssii", $searchValue, $searchValue, $start, $length)) {
    if ($stmt->execute) {
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $stmt->close();
      $data = array();
      while ($row = mysqli_fetch_assoc($result)) {
        $row['photo'] = md5($row['participant_id'] . "_PHYSICS_");
        $data[] = $row;
      }
    }
  } else {
    echo "Error bind: " . $stmt->error;
    die;
  }
} else {
  var_dump($orderByColumn);
  echo "Error prepare: " . $mysqli->error;
  die;
}

$queryCount = "SELECT COUNT(*) AS total FROM participants";
$resultCount = mysqli_query($mysqli, $queryCount);
$rowCount = mysqli_fetch_assoc($resultCount)['total'];
$mysqli->close();

$response = array(
  "draw" => intval($draw),
  "recordsTotal" => $rowCount,
  "recordsFiltered" => count($data),
  "data" => $data
);

echo json_encode($response);