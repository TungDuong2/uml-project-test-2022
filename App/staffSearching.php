<?php
include_once 'config/Database.php'; 

if(isset($_POST['search'])) {
    $search = $_POST['search'];

    if(strlen($search) > 0) {
        $database = new Database();
        $connection = $database->getConnection();   
        // $sql ="SELECT * FROM staff WHERE first_name LIKE '" . $search . "%' OR last_name LIKE '" . $search . "%' ORDER BY first_name LIMIT 0,5";
        $sql = "SELECT *, staff.id AS id, staff.sort AS staff_sort FROM staff 
        INNER JOIN departments ON departments.id = staff.department_id 
        WHERE staff.first_name LIKE '" . $search . "%' OR staff.last_name LIKE '" . $search . "%' ORDER BY staff.first_name LIMIT 0,9";
        $result = $connection->query($sql);
        if (!$result) {
            die('Invalid query: ' . $connection->error);
        }
        if(!empty($result)) {
            echo "<div class='justify-content-center row' style='display:flex'>";
            while($row = $result->fetch_assoc()) {
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $title = $row['title'];
                $email = $row['email'];
                $supervisor_id = $row['supervisor_id'];
                $department_id = $row['department_id'];
                $department = $row['department'];

                echo "
                <div class='col-sm-4'>
                    <div class='card'>
                        <img class='card-img-top' src='css/Unknown_person.jpg' alt='Card image cap'>
                        <div class='card-body'>
                            <h5 class='card-title'><b>$first_name $last_name</b></h5>
                            <h6 class='card-subtitle mb-2 text-muted'><b>$title</b></h6>
                            <p class='card-text'><a href='mailto:$email'>$email</a>
                            <br><i>$department Department</i></p>
                        </div>
                        <div class='card-footer'>
                            <small class='text-muted'>University of Miami Libraries</small>
                        </div>
                    </div>
                    <p>
                </div>";
            }        
            echo "</div>";
    
            $connection->close();
        }
    }
}
?>
