<?php
require '../../PHP/select.php';
if (isset($_GET['profileId'])) {
    $profileid = $_GET['profileId'];  // Cast the value to an integer
} else {
    // Handle the case when the artist ID is missing or invalid
    $profileid = 14;
}
$select=new select();

$phone = 'N/A';  // Default value for phone
$email = 'N/A';
 // Default value for email
$fullname = 'N/A';  // Default value for fullname
$pic = '';  // Default value for picture
$bio = 'N/A';  // Default value for bio
$gender = 'N/A';  // Default value for gender
$dob = 'N/A';  // Default value for dob
$customerdata=$select->customerDetails($profileid);

if (is_array($customerdata) && !empty($customerdata)) {
    // Store the data in variables
    $customerView = $customerdata['customerview'];
    $customerAddress = $customerdata['customeraddress'];

    // Now, you can use the $customerView and $customerAddress variables as needed
    // For example, printing out the customer view
    foreach ($customerView as $view) {
        $fullname= $view['FullName'] ;
        $dob=$view['dob'] ;
        $gender=$view['Gender'] ;
        $bio=($view['Bio']) ;
        $pic=null;
        if($view['Picture'])
        $pic= $view['Picture'];
    }

    // Print out the customer address details

    foreach ($customerAddress as $address) {
        if($address['class']=='phone'){
            $phone=$address['href'];}
        else if($address['class']=='email'){
            $email=$address['href'];}
    }
} else {
    // Handle the case when no data is returned
    echo 'No customer data found or error in retrieving data.';
}
if($fullname=='N/A'&&$email=='N/A'){
    die('<body style=" height:50%;width: 100%; background-color: #444444;margin-top: -1vh;margin-left: -1vw;position: absolute; text-align: center;padding-top: 40vh; color:red"><h1 >Customer Profile Not Found.</h1></body>');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo$fullname?> profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
<div class="profile-container">
    <!-- Header Section -->
    <div class="header-bar">
        <a href="homepage.html" class="homepage-link">Chromatie</a>
    </div>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-image-container">
            <?php
            echo '<img src="../' . $pic . '" alt="'. $fullname .'" title="'.$fullname.'" class="profile-image" id="profile-image"><br>';
            ?>
            <input type="file" id="profile-image-upload" style="display:none;" accept="image/*">
        </div>
        <div>
            <h2 class="profile-name" id="profile-name"><?php echo  $fullname ?></h2>
        </div>
    </div>




    <!-- Main Content Section -->
    <div class="main-content">
        <!-- About Section (Left Side) -->
        <div class="profile-about">
            <div>
                <h3>About</h3>
                <p><strong>Gender:</strong> <span><?php

                        if($gender=='M')
                            echo 'Male';
                        elseif($gender=='F')
                            echo 'Female';
                        else
                            echo 'Other';

                        ?></span></p>
                <p><strong>Date of Birth:</strong> <span> <?php
                        echo $dob?></span></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo $email ?>"><span><?php echo $email ?></span></a></p>
                <p><strong>Phone:</strong> <a href="tel: .<?php echo $phone ?>"><span><?php echo $phone?></span></a></p>
            </div>
        </div>

        <!-- Bio Section (Right Side) -->
        <div class="profile-bio">
            <h3>Bio</h3>
            <p><span><?php echo $bio ?></span></p>
        </div>
    </div>
</div>
<script>
    start();
</script>
</body>
</html>
