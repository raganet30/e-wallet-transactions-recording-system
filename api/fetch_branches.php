<?php
header('Content-Type: application/json');

require '../config/db.php'; // adjust path if needed

$response = ['data' => []];

$query = "
    SELECT 
        id,
        branch_name,
        address,
        current_coh,
        status,
        created_at
    FROM branches
    ORDER BY id DESC
";

$result = $con->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // Format data if needed
        $response['data'][] = [
            'id'            => $row['id'],
            'branch_name'   => $row['branch_name'],
            'address'       => $row['address'],
            'coh'           => $row['current_coh'],
            'status'        => $row['status'],
            'date_created'  => $row['created_at'],
        ];
    }
}

echo json_encode($response);
exit;
