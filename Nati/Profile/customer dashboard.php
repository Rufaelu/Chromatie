<?php
require '../../PHP/select.php';
require '../../PHP/update.php';
if (isset($_GET['profileId'])) {
    $profileid = $_GET['profileId'];  // Cast the value to an integer
} else {
//    echo($_GET['profileId']."damn");
    // Handle the case when the artist ID is missing or invalid
    $profileid = 1;
}
$select=new select();
$phone = 'N/A';  // Default value for phone
$email = 'N/A';
$customerdata=$select->customerDetails($profileid);
 if (is_array($customerdata) && !empty($customerdata)) {
     // Store the data in variables
     $customerView = $customerdata['customerview'];
     $customerAddress = $customerdata['customeraddress'];
     // Now, you can use the $customerView and $customerAddress variables as needed
     // For example, printing out the customer view
     foreach ($customerView as $view) {
         $fullname= $view['FullName'] ;
         $dob=htmlspecialchars($view['dob']) ;
         $gender=htmlspecialchars($view['Gender']) ;
        $bio=$view['Bio'] ;
         $pic=null;
        if($view['Picture'])
        $pic=  $view['Picture'];
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


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo$fullname?> Dashboard</title>
  <link rel="stylesheet" href="profile.css">
</head>
<body>
<div class="profile-container">
    <!-- Header Section -->
    <div class="header-bar">
      <a href="../../fi's/home.php" class="homepage-link">Chromatie</a>

    </div>
    <button id="deleteaccount" onclick="deletecustomer(<?php echo $profileid ?>);" style="position: absolute; top:2vh; left: 80vw; background-color: transparent; width: fit-content; height: 3vh; margin-right: -20vw;">Delete Account</button>

    <!-- Profile Header -->
    <div class="profile-header">
      <div class="profile-image-container">
        <?php
          echo '<img src=" ../'. $pic . '" alt="'. $fullname .'" title="'.$fullname.'" class="profile-image" id="profile-image"><br>';
?>
          <input type="file" id="profile-image-upload" style="display:none; width: 5vw;" accept="image/*">
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
          <p><strong>Gender:</strong> <span id="gender"><?php

                  echo $gender;



                      ?></span></p>
          <p><strong>Date of Birth:</strong> <span id="dob"> <?php echo $dob?></span></p>
          <p ><strong> Email:</strong> <a href="mailto:. <?php echo $email?>"><span id="email"> <?php echo $email?></span></a></p>
          <p ><strong>Phone:</strong> <a href="tel: .<?php echo $phone ?>"><span id="phone"><?php echo $phone?></span></a></p>
        </div>
        <button class="edit-button">Edit</button>
      </div>

      <!-- Bio Section (Right Side) -->
      <div class="profile-bio">
        <h3>Bio</h3>
        <p><span id="bio"><?php echo $bio ?></span></p>
      </div>
    </div>
  </div>
  <script src="customerdashboard.js"></script>

</body>
</html>


