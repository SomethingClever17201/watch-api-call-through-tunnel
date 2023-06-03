<html>
        <?php
            error_reporting(E_ALL);
            $sql_ins = mysqli_connect("url", "username", "password", "database");


            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL: " . mysqli_connect_error();
                echo "<br/>" . mysqli_connect_errno();
                exit();
            }

            $sql_ins -> query("INSERT 1234")



            if($result == True)
            {
                echo 'Data Inserted Successfully';
            }
            else
            {
                printf("Errorcode: %d\n", $sql_ins->errno);
            }
        ?> 
</html>
