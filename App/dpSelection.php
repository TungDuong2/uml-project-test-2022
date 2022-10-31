<?php
if(isset($_POST['department_option_selected'])) {  
        $dpselected = $_POST['department_option_selected'];
        // get the database connection
        include_once 'config/Database.php';    
        $database = new Database();
        $connection = $database->getConnection();   

        // read all row from database table
        // $sql = 'SELECT *, staff.id AS id, staff.sort AS staff_sort FROM staff 
        // INNER JOIN departments ON departments.id = staff.department_id 
        // order by departments.sort, supervisor_id, last_name';
        $sql = "SELECT * FROM departments ORDER BY sort";
        $result = $connection->query($sql);

        if (!$result) {
            die('Invalid query: ' . $connection->error);
        }        
        // read data of each row
        // mysqli_fetch_row() [numeric only]
        // mysqli_fetch_assoc() [keyed only]
        // mysqli_fetch_array($result, $fetch_mode);
        $departments = array();
        while($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        
        
        echo "
            <ul id='myUL'>
                <li><span class='caret fs-3'>University of Miami Libraries</span>   
                    <ul class='nested'>
                        <li><span class='caret fs-4'>Departments</span>   
                            <ul class='nested'>
        ";
        foreach ($departments as $dp) {
            $department_id = $dp['id'];
            $department = $dp['department'];
            echo "
                <li><span class='caret fs-5'>$department</span>   
                    <ul class='nested' id='abc'>";
                        
            
            $sql = "SELECT * FROM staff 
            WHERE department_id = $department_id
            ORDER BY supervisor_id, last_name";
            $result = $connection->query($sql);   
            if (!$result) {
                die('Invalid query: ' . $connection->error);
            }
            $staffs = array();
            while($row = $result->fetch_assoc()) {
                $staffs[] = $row;
            }
            if (($dpselected == 0) || ($department_id == $dpselected)) {
                foreach ($staffs as $st) {
                    $first_name = $st['first_name'];
                    $last_name = $st['last_name'];
                    $title = $st['title'];
                    $email = $st['email'];
                    echo "<li><small><b>$last_name, $first_name</b> | <i>$title</i> | <a href='mailto:$email'>$email</a></small></li>";
                }       
                echo 
                "<script>
                  var toggler = document.getElementsByClassName('caret');
                  for (var i = 2; i < toggler.length; i++) {
                    toggler[i].parentElement.querySelector('.nested').classList.toggle('active');
                    toggler[i].classList.toggle('caret-down');
                  }
                </script>";                     
            }


            echo "</ul></li>";
        }
        echo "</ul></li></ul></li></ul>";
        
        // while($row = $result->fetch_assoc()) {
        //     $first_name = $row['first_name'];
        //     $last_name = $row['last_name'];
        //     $title = $row['title'];
        //     $email = $row['email'];
        //     $supervisor_id = $row['supervisor_id'];
        //     $department_id = $row['department_id'];
        //     $department = $row['department'];

        //     if (($dpselected == 0) || ($department_id == $dpselected)) {
        //         if ($department_id != $is_same) {
        //             echo "
        //             </ul></li></ul><ul id='myUL'>
        //                 <li><span class='caret fs-5'>$department</span>   
        //                 <ul class='nested'>                  
        //             ";
        //         }    
        //         echo "<li><small><b>$last_name, $first_name</b> | <i>$title</i> | <a href='mailto:$email'>$email</a></small></li>";         
        //         $is_same = $department_id; 
        //     }    
           
        // }        

        $connection->close();
}
?>

<!-- Tree View - credit: https://www.w3schools.com/howto/howto_js_treeview.asp -->
<script>
var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
  toggler[i].addEventListener("click", function() {
    this.parentElement.querySelector(".nested").classList.toggle("active");
    this.classList.toggle("caret-down");
  });
}
for (i = 0; i < 2; i++) {
  toggler[i].parentElement.querySelector(".nested").classList.toggle("active");
  toggler[i].classList.toggle("caret-down");
}
</script>
<style>
ul, #myUL {
  list-style-type: none;
}

#myUL {
  margin: 0;
  padding: 0;
}

.caret {
  cursor: pointer;
  -webkit-user-select: none; /* Safari 3.1+ */
  -moz-user-select: none; /* Firefox 2+ */
  -ms-user-select: none; /* IE 10+ */
  user-select: none;
}

.caret::before {
  content: "\276F";
  color: black;
  display: inline-block;
  margin-right: 6px;
}

.caret-down::before {
  -ms-transform: rotate(90deg); /* IE 9 */
  -webkit-transform: rotate(90deg); /* Safari */'
  transform: rotate(90deg);  
}

.nested {
  display: none;
  /* display: block; */
}

.active {
  display: block;
  /* display: none; */
}
</style>