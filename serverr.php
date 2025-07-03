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

    if(isset($_POST['submit']))
    {
        $first_name=$_POST['first_name'];
        $last_name=$_POST['last_name'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $Message=$_POST['Message'];
    
    $sql_query= "INSERT INTO contact (first_name,last_name,email,phone,Message) 
    VALUES ('$first_name','$last_name','$email','$phone','$Message')";

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