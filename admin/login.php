<?php
// Start the session to persist user login status
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    include_once 'db_connection.php'; // You need to create this file

    // Get email and password from the form
    $email = $_POST['email']; // Changed from 'mail' to 'email'
    $password = $_POST['password'];

    // Perform SQL query to check if the user exists
    $sql = "SELECT * FROM admins WHERE admin_email = '$email' AND admin_password = '$password'";
    $result = mysqli_query($conn, $sql);

    // Check if query returned any rows
    if (mysqli_num_rows($result) == 1) {
        // User exists, set session variables and redirect to admin dashboard
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit(); // Exit to prevent further execution
    } else {
        // Invalid email or password, set error message
        $error_message = "Invalid email or password"; // Changed from username to email
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Animated Login Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script> 
    <style>*{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body{
        font-family: 'Poppins', sans-serif;
        overflow: hidden;
    }
    .wave{
        position: fixed;
        bottom: 0;
        left: 0;
        height: 100%;
        z-index: -1;
    }
    .container{
        width: 100vw;
        height: 100vh;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap :7rem;
        padding: 0 2rem;
    }
    .img{
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    .login-content{
        display: flex;
        justify-content: flex-start;
        align-items: center;
        text-align: center;
    }
    .img img{
        width: 500px;
    }
    form{
        width: 360px;
    }
    .login-content img{
        height: 100px;
    }
    .login-content h2{
        margin: 15px 0;
        color: #333;
        text-transform: uppercase;
        font-size: 2.9rem;
    }
    .login-content .input-div{
        position: relative;
        display: grid;
        grid-template-columns: 7% 93%;
        margin: 25px 0;
        padding: 5px 0;
        border-bottom: 2px solid #d9d9d9;
    }
    .login-content .input-div.one{
        margin-top: 0;
    }
    .i{
        color: #d9d9d9;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .i i{
        transition: .3s;
    }
    .input-div > div{
        position: relative;
        height: 45px;
    }
    .input-div > div > h5{
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 18px;
        transition: .3s;
    }
    .input-div:before, .input-div:after{
        content: '';
        position: absolute;
        bottom: -2px;
        width: 0%;
        height: 2px;
        background-color: #625bff;
        transition: .4s;
    }
    .input-div:before{
        right: 50%;
    }
    .input-div:after{
        left: 50%;
    }
    .input-div.focus:before, .input-div.focus:after{
        width: 50%;
    }
    .input-div.focus > div > h5{
        top: -5px;
        font-size: 15px;
    }
    .input-div.focus > .i > i{
        color: #625bff;
    }
    .input-div > div > input{
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        border: none;
        outline: none;
        background: none;
        padding: 0.5rem 0.7rem;
        font-size: 1.2rem;
        color: #555;
        font-family: 'poppins', sans-serif;
    }
    .input-div.pass{
        margin-bottom: 4px;
    }
    a{
        display: block;
        text-align: right;
        text-decoration: none;
        color: #999;
        font-size: 0.9rem;
        transition: .3s;
    }
    a:hover{
        color: #625bff;
    }
    .btn{
        display: block;
        width: 100%;
        height: 50px;
        border-radius: 25px;
        outline: none;
        border: none;
        background-image: linear-gradient(to right, #625bff, #625bff, #625bff);
        background-size: 200%;
        font-size: 1.2rem;
        color: #fff;
        font-family: 'Poppins', sans-serif;
        text-transform: uppercase;
        margin: 1rem 0;
        cursor: pointer;
        transition: .5s;
    }
    .btn:hover{
        background-position: right;
    }
    
    
    @media screen and (max-width: 1050px){
        .container{
            grid-gap: 5rem;
        }
    }
    
    @media screen and (max-width: 1000px){
        form{
            width: 290px;
        }
    
        .login-content h2{
            font-size: 2.4rem;
            margin: 8px 0;
        }
    
        .img img{
            width: 400px;
        }
    }
    
    @media screen and (max-width: 900px){
        .container{
            grid-template-columns: 1fr;
        }
    
        .img{
            display: none;
        }
    
        .wave{
            display: none;
        }
    
        .login-content{
            justify-content: center;
        }
    }
    
    </style>
</head>

<body>
    <div class="container">
        <div class="img">
            <img src="img/login.svg" alt="Login Image">
        </div>
        <div class="login-content">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <img src="img/user.svg" alt="User Icon">
                <h2 class="title">Admin Login</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-envelope"></i> <!-- Changed icon to envelope for email -->
                    </div>
                    <div class="div">
                        <h5>Email</h5> <!-- Changed from Username to Email -->
                        <input type="text" class="form-control" name="email"> <!-- Changed name to 'email' -->
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" class="form-control" name="password">
                    </div>
                </div>
                <input type="submit" class="btn" value="Login">
                <?php if (isset($error_message)) { ?>
                    <p><?php echo $error_message; ?></p>
                <?php } ?>
            </form>
        </div>
    </div>
</body>
<script>
    const inputs = document.querySelectorAll(".form-control");

function AddClass(){
   let parent = this.parentNode.parentNode;
   parent.classList.add("focus");
}

function RemoveClass(){
  let parent = this.parentNode.parentNode;
  if(this.value == ''){
      parent.classList.remove("focus");
  }
}

inputs.forEach(input => {
  input.addEventListener("focus", AddClass);
  input.addEventListener("blur", RemoveClass);
});


</script>
</html>

