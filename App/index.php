<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <!-- <meta http-equiv='X-UA-Compatible' content='IE=edge'> -->
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>University of Miami Libraries | Tung Duong Project</title>

    <!-- bootstrap -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>   -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css">     -->
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    

</head>
<body style='margin: 50px;'>
    <h1>Programmer Demonstration Project, UM Libraries</h1>
    
    <div class="row">
        <div class="col-sm-6" id="searchByDepartment">
            <h3>Hierarchical Staff List Organized By Department</h3>
            <br>
            <form  method="POST">
                <label>Department Options:</label>
                
                <select style='width:300px' name="department_options" id="department_options" >
                <!-- onchange="this.form.submit()"; -->
                    <option selected="selected" style='text-align: center' value=0>--- Select a Department ---</option>
                    <?php 
                        include_once 'config/Database.php';    
                        $database = new Database();
                        $connection = $database->getConnection();
                        foreach ($connection->query("SELECT * FROM departments ORDER BY sort") as $row){
                        echo "<option value=".$row['id'].">".$row['department']."</option>"; 
                    }
                    $connection->close();
                    ?>
                </select>
            </form> 
            <br>
            <span id="department_selected_result"></span>
        </div>
        <div class="col-sm-6" id="searchByName" >   
            <h3 class="d-flex justify-content-center">Search the Staff Profile</h3>          
            <section class="w-100 p-4 d-flex justify-content-center pb-4">
                <div class="form-outline" style="width: 22rem;">
                <input type="text" id="search" class="form-control" placeholder="Enter a name...">
                <label class="form-label text-muted small" for="search" style="margin-left: 0px; font-weight:normal;">Example: Abby</label>
                <div class="form-notch"><div class="form-notch-leading" style="width: 9px;"></div><div class="form-notch-middle" style="width: 87.2px;"></div><div class="form-notch-trailing"></div></div></div>
            </section>
            <span id="result"></span>
        </div>        
    </div>
       
    <script>
        $(document).ready(function() {
            $("#department_options").change(function() {
                $.ajax({
                    url:'dpSelection.php',
                    method: 'POST',
                    data: {department_option_selected: $(this).val() },
                    datatype: 'text',
                    success:function(result) {
                        $("#department_selected_result").html(result);
                    }
                });
            }).trigger('change'); // start at begin
        });

        $(document).ready(function(){
            $("#search").keyup(function() {
                $.ajax({
                    url: 'staffSearching.php',
                    type: 'post',
                    data: {search: $(this).val()},
                    datatype: 'text',
                    success:function(result) {
                        $("#result").html(result);
                    }
                });
            });
        }); 
    </script>
</body>
</html>
