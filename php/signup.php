<?php
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        //check user email is valid or not
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){//if email is valid
            //to check that email already exist in the database or not
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
            if (mysqli_num_rows($sql) > 0){//if email already exist
                echo "$email - This email already exist!";
            }else{
                //check user upload file or not
                if (isset($_FILES['image'])){//if file is uploaded
                    $img_name = $_FILES['image']['name'];
                    $tmp_name = $_FILES['image']['tmp_name'];

                    //exploding image
                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode); //getting the extension of an user uploaded image

                    $extensions = ['png', 'jpeg', 'jpg']; //these are valid image extensions

                    if (in_array($img_ext, $extensions) === true){
                        $time = time(); //We use current time to name images so that every uploaded image have unique name
                        $new_img_name = $time.$img_name;

                        if (move_uploaded_file($tmp_name, "images/".$new_img_name)){
                            $status = "Active now";
                            $random_id = rand(time(), 10000000);//creating random id for user

                            $sql2 = mysqli_query($conn,"INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                                VALUES ('{$random_id}', '{$fname}', '{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}')");
                            if ($sql2){
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                if (mysqli_num_rows($sql3) > 0){
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id'];
                                    echo "success";
                                }
                            }else{
                                echo "Something went wrong!";
                            }
                        }
                    }else{
                        echo "Please select an Image file - jpeg, jpg, png!";
                    }

                }else{
                    echo "Please select an Image file!";
                }
            }
        }else{
            echo "$email - This is not a valid email!";
        }
}else{
        echo "All input fields are required!";
    }
?>