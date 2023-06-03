<?php
    $method = $_SERVER['REQUEST_METHOD'];


    if($method == "GET")
    {
        $sql = mysqli_connect("url", "username", "password", "database");
        if(isset($_GET["api_type"]))
        {
            $type = $_GET["api_type"];

            if($type == "watchapi")
            {
                $queryStr = "SELECT * FROM HeartRateData";
                if(isset($_GET["get_type"]))
                {
                    if($_GET["get_type"] == "id")
                    {
                        if(isset($_GET["id"]))
                        {
                            $queryStr .= " WHERE Uid = '" . $_GET["id"] . "'";
                        }
                        else
                        {
                            $queryStr .= "_ERRNOID";
                        }
                        
                    }
                    elseif($_GET["get_type"] == "b")
                    {
                        if(isset($_GET["bt"]))
                        {
                            $queryStr .= " WHERE Time < '" . $_GET["bt"] . "'";
                            if(isset($_GET["bd"]))
                            {
                                $queryStr .= " AND Date < '" . $_GET["bd"] . "'";
                            }
                            else{
                                $queryStr .= "_ERR_NO_BEFORE_DATE";
                            }
                        }
                        else
                        {
                            $queryStr .= "_ERR_NO_BEFORE_TIME";
                        }
                    }
                    elseif($_GET["get_type"] == "a")
                    {
                        if(isset($_GET["at"]))
                        {
                            $queryStr .= " WHERE Time > '" . $_GET["at"] . "'";
                            if(isset($_GET["ad"]))
                            {
                                $queryStr .= " AND Date > '" . $_GET["ad"] . "'";
                            }
                            else{
                                $queryStr .= "_ERR_NO_AFTER_DATE";
                            }
                        }
                        else
                        {
                            $queryStr .= "_ERR_NO_AFTER_TIME";
                        }
                    }
                    elseif($_GET["get_type"] == "ba")
                    {
                        if(isset($_GET["bt"]))
                        {
                            $queryStr .= " WHERE Time < '" . $_GET["bt"] . "'";
                            if(isset($_GET["bd"]))
                            {
                                $queryStr .= " AND Date < '" . $_GET["bd"] . "'";
                                if(isset($_GET["at"]))
                                {
                                    $queryStr .= " AND Time > '" . $_GET["at"] . "'";
                                    if(isset($_GET["ad"]))
                                    {
                                        $queryStr .= " AND Date > '" . $_GET["ad"] . "'";
                                    }
                                    else{
                                        $queryStr .= "_ERR_NO_AFTER_DATE";
                                    }
                                }
                                else
                                {
                                    $queryStr .= "_ERR_NO_AFTER_TIME";
                                }
                            }
                            else{
                                $queryStr .= "_ERR_NO_BEFORE_DATE";
                            }
                        }
                        else
                        {
                            $queryStr .= "_ERR_NO_BEFORE_TIME";
                        }
                    }
                    elseif($_GET["get_type"] == "gt")
                    {
                        if(isset($_GET["gt"]))
                        {
                            $queryStr .= " WHERE Value > '" . $_GET["gt"] . "'";
                        }
                        else
                        {
                            $queryStr .= "_ERR_NO_GREATER_VALUE";
                        }
                    }
                    elseif($_GET["get_type"] == "lt")
                    {
                        if(isset($_GET["lt"]))
                        {
                            $queryStr .= " WHERE Value < '" . $_GET["lt"] . "'";
                        }
                        else
                        {
                            $queryStr .= "_ERR_NO_LESS_VALUE";
                        }
                    }
                    elseif($_GET["get_type"] == "glt")
                    {
                        if(isset($_GET["gt"]))
                        {
                            $queryStr .= " WHERE Value > '" . $_GET["gt"] . "'";
                            if(isset($_GET["lt"]))
                            {
                                $queryStr .= " AND Value < '" . $_GET["lt"] . "'";
                            }
                            else
                            {
                                $queryStr .= "_ERR_NO_LESS_VALUE";
                            }
                        }
                        else
                        {
                            $queryStr .= "_ERR_NO_GREATER_VALUE";
                        }
                    }
                }


                $result = $sql -> query($queryStr);
                $content = "[\n";
                if (!$sql->error ) {
                    while ($row = $result->fetch_assoc())
                    {
                        $content .= "    " . json_encode($row) . ",\n";
                        // foreach ($row as $val) {
                        //     echo $val;
                        // }
                    }
                    if($result->num_rows > 0)
                    {
                        $content = substr($content,0,-2) . "\n]";
                    }
                    else
                    {
                        $content = substr($content,0,-1) . "]";
                    }
                    echo $queryStr;
                    echo '{"code":"200","message":"Succesfully Collected Data, "content" : "' . $content. '"}';
                  } else {
                    echo '{"code":"500","message":"SQL Error, ' . $sql->error . ',"query:"' . $queryStr.'"}';
                  }

            }
        }
        $sql -> close();
    }
    if($method == "POST")
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $sql = mysqli_connect("url", "username", "password", "database");
        if(isset($_GET["api_type"]))
        {
            $type = $_GET["api_type"];

            if($type == "watchapi")
            {
                $queryStr = "INSERT INTO HeartRateData (Time, Date, Value) VALUES ('";
                $queryStr .= $data -> Time . "','";
                $queryStr .= $data ->  Date . "','";
                $queryStr .= $data ->  Value . "')";


                if ($sql -> query($queryStr) === TRUE) {
                    echo '{"code":"200","message":"Succesfully Inserted Data"}';
                  } else {
                    echo '{"code":"500","message":"SQL Error, ' . $sql->error . ',"query:"' . $queryStr.'"}';
                  }


            }
        }




        $sql -> close();

        
    }
        echo "\n";



?>
