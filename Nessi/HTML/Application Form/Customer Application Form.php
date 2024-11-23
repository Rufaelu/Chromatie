<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Application Form</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@40,400,0,0" />
    <link rel="stylesheet" href="../../CSS/Application Form/ACF.css">
</head>
<body>
    <header id="header">
        <h1>Customer Application Form</h1>
        <span class="material-symbols-outlined">
            arrow_back
            </span>
    </header>

    <div class="container">
        <div class="info_section">
            <form id="custForm" name="custForm" method="POST" enctype="multipart/form-data">
                <label for="fName" class="label">First Name</label>
                <input type="text" name="fName" id="fName" placeholder="Enter First Name">
                <label for="lName" class="label">Last Name</label>
                <input type="text" name="lName" id="lName" placeholder="Enter Last Name">

                <label for="email" class="label">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter Email">

                <label for="passW" class="label">Password</label>
                <input type="password" name="passW" id="passW" placeholder="Enter password">
                <p class="info_text">Your password is between 4 and 12 characters</p>

                <label for="confirmPassW" class="label">Confirm Password</label>
                <input type="password" name="confirmPassW" id="confirmPassW" placeholder="Enter password">
                <span id="passwordMatchMessage" style="display: none"></span>

                <label for="DOB" class="label">Date of Birth</label>
                <input type="date" name="DOB" id="DOB" placeholder="M/D/Y">

                <label for="gender" class="label">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="">Gender:</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>

                <button id="cancelBtn" type="button">Cancel</button>
                <input id="nextBtn" type="submit" value="submit">
            </form>
        </div>
    </div>
    <script src="../../JS/Application Form/ACF.js" defer></script>
<script>
    confirmpass=document.getElementById("confirmPass");
    confirmpass.addEventListener('input',function() {


    const message = document.getElementById('passwordMatchMessage2');

    // Check if the passwords match
    if (password.value === confirmPassword.value) {
    message.style.display='block';
    message.textContent = 'Passwords match';
    message.style.color = 'green';
    } else {
    message.style.display='block';

    message.textContent = 'Passwords do not match';
    message.style.color = 'red';
    }
    });</script>
</body>
</html>
<!-- Change Username to Email(then artist verification will be checked using email, showing information details) -->

<?php
require_once '../../../PHP/insert.php';
ob_start(); // Start output buffering

// Check if the form has been submitted using POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if all required fields are set
    if (isset($_POST['fName'], $_POST['lName'], $_POST['email'], $_POST['passW'], $_POST['confirmPassW'], $_POST['DOB'], $_POST['gender'])) {


        $fName = htmlspecialchars(trim($_POST['fName']));
        $lName = htmlspecialchars(trim($_POST['lName']));
        $email = htmlspecialchars(trim($_POST['email']));
        $password = trim($_POST['passW']);
        $confirmPassword = trim($_POST['confirmPassW']);
        $DOB = $_POST['DOB']; // Assuming the date format is correct
        $gender = htmlspecialchars(trim($_POST['gender']));

        // Validate password length
        if (strlen($password) < 4 || strlen($password) > 12) {
            die("Password must be between 4 and 12 characters.");
        }

        // Check if password and confirm password match
        if ($password !== $confirmPassword) {
            die("Passwords do not match.");
        }
        $customerdata=[
            'firstname'=> $fName ,
            'lastname'=>$lName,
            'dob'=>$DOB,
            'gender'=>$gender,
            'password'=> passwordToBinary( $password),
            'bio'=>null,
            'picture'=> '../../uploads/media/Default.jpg',
            'phone'=>null,
            'email'=> $email
            

        ];

        $insert=new insert();
        $id=$insert->intocustomer($customerdata);
        if(is_numeric($id)){
            
            header("Location: ../../../fi's/home.php?type=customer&userId=" . urlencode($id));
        }

    } else {
        // Form data is incomplete
        die("Please fill in all required fields.");
    }
} else {
    // If the form is not submitted via POST
    die("Invalid request.");
}


function passwordToBinary($password) {
    $binaryString = '';

    // Loop through each character of the password
    for ($i = 0; $i < strlen($password); $i++) {
        // Get ASCII value of each character, add 1, and convert back to a character
        $asciiValue = ord($password[$i]) + 1;
        $binaryString .= chr($asciiValue);
    }

    // Encode binary as Base64
    return base64_encode($binaryString);
}
ob_end_flush(); // End output buffering and flush the output

?>
