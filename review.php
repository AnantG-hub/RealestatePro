<?php
    $servername ='localhost';
    $username = 'root';
    $password = '';
    $database_name = 'project';

    $conn = mysqli_connect($servername,$username,$password, $database_name);

    if(!$conn)
    {
        die("Connection Failed:" . mysqli_connect_error());
    }

    if(isset($_POST['send']))
    {
        $Message=$_POST['Message'];
        $sql_query= "INSERT INTO review (Message) 
        VALUES ('$Message')";
        
    if(mysqli_query($conn, $sql_query))
    {
        echo "New Details entry inserted sucessfully !";
    }
    else
    {
        echo "Error: " . $sql . "" . mysqli_error($conn);
    }
    mysqli_close($conn);
    }
 ?>