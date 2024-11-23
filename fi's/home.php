
<?php
require '../PHP/select.php';
//$type='customer';
$type='artist';
if (isset($_GET['type'])&&isset($_GET['userId']) ) {

    $type = $_GET['type'];
    $userId = $_GET['userId'];

} else {
    // Handle the case when the artist ID is missing or invalid
   Die('Id not provided');
}

$select=new select();
$data= $select->artistMiniProfiles();
$visitorCount=0;
$visitorData=null;

if($type=='artist'){
$visitorData =($type==='artist')? $select->VisitorInfo($userId):null;
$visitorCount = $visitorData? count($visitorData):0;
}


$visual = [];
$performing = [];
$literary = [];
$digital = [];
$other = [];

    // Iterate through the artistProfiles to categorize them
foreach ($data as $artist) {
    // Normalize the first service category
    $firstServiceCategory = strtolower(trim($artist['FirstServiceCategory']));

    // Categorize based on the first service category
    if (strpos($firstServiceCategory, 'visual arts') !== false) {
        $visual[] = $artist;
    } elseif (strpos($firstServiceCategory, 'performing arts') !== false) {
        $performing[] = $artist;
    } elseif (strpos($firstServiceCategory, 'literary arts') !== false) {
        $literary[] = $artist;
    } elseif (strpos($firstServiceCategory, 'digital arts') !== false) {
        $digital[] = $artist;
    } else {
        $other[] = $artist; // Categorize as other if it doesn't match any category
    }
}






function displaynotification($visitorData,$userid,$usertype) {
    // Call the getVisitorInfo function to retrieve data from the 'visitor_info' view

    if (!empty($visitorData)) {
        foreach ($visitorData as $visitor) {
            // Check if the string starts with '../' and remove only the first occurrence

            $page='';
            if($visitor['visitortype']=='artist')
                $page='../Rufa/artistprofile.php?artistId='.$visitor['visitor_id'] .'&type='.$_GET['type'].'&userId='.$_GET['userId'];
            else {

                $page = '../Nati/Profile/customer profile.php?profileId=' . $visitor['visitor_id'] ;
            }
            echo '<div class="notification-item">';
            echo '<a href="' .$page . '" class="visitor-link">';
            echo '<img src="' .$visitor['visitor_picture'] . '" alt="'.$visitor['visitor_fullname'].'" class="profile-pic">';
            echo htmlspecialchars($visitor['visitor_fullname']);
            echo ' visited your profile'; // Added the "visited your profile" text
            echo '</a>';

            $visitTime = new DateTime($visitor['when']);
            $currentTime = new DateTime(); // Current time

// Get the difference in seconds between the two DateTime objects
            $elapsedTimeInSeconds = $currentTime->getTimestamp() - $visitTime->getTimestamp();

// Determine the format to show (minutes, hours, days, etc.)
            if ($elapsedTimeInSeconds >= 31536000) { // More than a year (365 * 24 * 60 * 60)
                $timeAgo = floor($elapsedTimeInSeconds / 31536000) . ' year' . (floor($elapsedTimeInSeconds / 31536000) > 1 ? 's' : '') . ' ago';
            } elseif ($elapsedTimeInSeconds >= 2592000) { // More than a month (30 * 24 * 60 * 60)
                $timeAgo = floor($elapsedTimeInSeconds / 2592000) . ' month' . (floor($elapsedTimeInSeconds / 2592000) > 1 ? 's' : '') . ' ago';
            } elseif ($elapsedTimeInSeconds >= 86400) { // More than a day (24 * 60 * 60)
                $timeAgo = floor($elapsedTimeInSeconds / 86400) . ' day' . (floor($elapsedTimeInSeconds / 86400) > 1 ? 's' : '') . ' ago';
            } elseif ($elapsedTimeInSeconds >= 3600) { // More than an hour (60 * 60)
                $timeAgo = floor($elapsedTimeInSeconds / 3600) . ' hour' . (floor($elapsedTimeInSeconds / 3600) > 1 ? 's' : '') . ' ago';
            } elseif ($elapsedTimeInSeconds >= 240 && $elapsedTimeInSeconds < 3600) { // Between 4 minutes and less than 1 hour
                $timeAgo = 'less than an hour ago';
            } elseif ($elapsedTimeInSeconds >= 60) { // More than a minute (60 seconds)
                $timeAgo = floor($elapsedTimeInSeconds / 60) . ' minute' . (floor($elapsedTimeInSeconds / 60) > 1 ? 's' : '') . ' ago';
            }  else {
                $timeAgo = 'just now';
            }

            echo '<span class="visitor-time">' . $timeAgo . '</span>';



            echo '</div>';

        }
    } else {
        echo '<div class="notification-item">No visitors found for this artist.</div>';
    }

    // Check if data is returned

}
?><!DOCTYPE html>
<html lang="en">

<head>
  <title>Chromatie</title>
  <link rel="stylesheet" href="home.css">
</head>

<body>
<header>

  <div class="pages">
    <h6 id="customerid" style="display: none"></h6>
    <h6 id="artistid" style="display: none"></h6>
    <nav>
        <div class="logo-container">
            <a href="#" class="chromatie-logo">
                <img src="../fi's/assets/chromatie.jpg" alt="Chromatie">
            </a>
        </div>

        <div class="notification-container" style="display: none" >

            <span class="notification-icon"></span>
            <div class="notification-menu">
                <div class="notification-dropdown">

                    <?php

                    displaynotification($visitorData? $visitorData:null,$userId,$type);
                    // Close the connection

                    ?>

                </div>
            </div>
            <span class="notification-count" id="notification-count"> <?php echo $visitorCount  ?></span>

        </div>

      <a href="../Bersi/about%20chromatie.html" style="font-size: large;">About us</a>
      <a href="../Nati/Contact%20Us/Artist%20def.html">Contact</a>
      <a href="../Bersi/login.html" id="loginlink" style="display: none">Login/Sign up</a>
      <a href="../Nati/Profile/customer dashboard.php?<?php echo 'profileId=' .$userId?>" id="customerprofilelink" style="display: none" >My Profile</a>
      <a href="../Rufa/artistdashboard.php?<?php echo 'profileId=' .$userId?>" id="artistprofilelink" style="display: block" >My Profile</a>
      <a href="../Bersi/login.html?" >Log out</a>


    </nav>



  </div>
</header>

<div class="logo">


  <svg viewBox="0 0 1427 1000" fill="none" xmlns="http://www.w3.org/2000/svg" class="chromatie-logo" style="width:100%; height:100vh;">
    <path class="svg-path"
            d="M-37.6126 140.944C-33.6378 115.277 -16.0064 93.6147 8.60112 84.1638L213.794 5.35688C238.279 -4.04689 265.955 0.0686834 286.426 16.1578L458.857 151.679C479.328 167.768 489.495 193.395 485.54 218.934L452.395 432.962C448.421 458.629 430.789 480.292 406.182 489.743L200.988 568.55C176.503 577.953 148.828 573.838 128.357 557.749L-44.0738 422.227C-64.5449 406.138 -74.7123 380.511 -70.7573 354.972L-37.6126 140.944Z"
            fill="url(#paint0_linear_48_1745)" />
    <path class="svg-path"
            d="M194.384 525.123C179.796 503.005 178.315 474.745 190.514 451.271L290.445 258.977C302.371 236.028 325.572 221.131 351.422 219.823L573.064 208.608C598.914 207.3 623.549 219.777 637.81 241.401L757.312 422.592C771.9 444.71 773.38 472.97 761.182 496.444L661.25 688.738C649.324 711.687 626.124 726.584 600.274 727.892L378.632 739.107C352.782 740.415 328.147 727.938 313.885 706.314L194.384 525.123Z"
            fill="url(#paint1_linear_48_1745)" />
    <path class="svg-path"
            d="M614.98 477.435C611.732 451.426 622.898 425.579 644.195 409.81L819.863 279.743C840.826 264.221 868.431 260.819 892.374 270.808L1096.26 355.865C1120.2 365.853 1136.89 387.731 1140.08 413.332L1166.88 627.864C1170.12 653.872 1158.96 679.719 1137.66 695.488L961.994 825.556C941.03 841.078 913.425 844.48 889.482 834.491L685.598 749.434C661.655 739.446 644.971 717.568 641.774 691.966L614.98 477.435Z"
            fill="url(#paint2_linear_48_1745)" />
    <path class="svg-path"
            d="M920.177 461.111C898.815 445.433 887.539 419.637 890.674 393.618L916.54 178.973C919.627 153.357 936.218 131.409 960.12 121.319L1163.63 35.4151C1187.53 25.3256 1215.15 28.6113 1236.18 44.0459L1412.41 173.382C1433.78 189.06 1445.05 214.856 1441.92 240.875L1416.05 455.52C1412.96 481.136 1396.37 503.084 1372.47 513.174L1168.96 599.078C1145.06 609.167 1117.44 605.882 1096.41 590.447L920.177 461.111Z"
            fill="url(#paint3_linear_48_1745)" />

    <!-- Centered Text -->

      <defs>
          <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
              <feDropShadow dx="4" dy="4" stdDeviation="6" flood-color="white"/>
          </filter>
      </defs>

      <text x="50%" y="30%" style="width: 50vw;" font-size="30" font-weight="bolder" fill="black" text-anchor="middle" dominant-baseline="hanging" font-family="system-ui" filter="url(#shadow)">
          Discover and connect with talented artists to commission unique, custom artwork.
          <tspan x="50%" dy="1.2em">With diverse profiles, and easy search filters, Chromatie</tspan>
          <tspan x="50%" dy="1.2em">brings your creative vision to life effortlessly. Join us and explore a world of</tspan>
          <tspan x="50%" dy="1.2em">artistic possibilities!</tspan>
      </text>


      <defs>
        <?php

        if ($type === 'artist') {
            // Echo gradient for customers
            echo '<linearGradient id="paint0_linear_48_1745" x1="398.206" y1="52.5088" x2="24.6795" y2="527.766" gradientUnits="userSpaceOnUse">';
            echo '    <stop offset="0.45" stop-color="#CB997E" />';
            echo '    <stop offset="1" stop-color="#B7B7A4" />';
            echo '</linearGradient>';
            echo '<linearGradient id="paint1_linear_48_1745" x1="616.525" y1="228.098" x2="378.649" y2="729.852" gradientUnits="userSpaceOnUse">';
            echo '    <stop stop-color="#B7B7A4" />';
            echo '    <stop offset="1" stop-color="#DDBEA9" />';
            echo '</linearGradient>';
            echo '<linearGradient id="paint2_linear_48_1745" x1="1126.86" y1="392.163" x2="690.767" y2="742.572" gradientUnits="userSpaceOnUse">';
            echo '    <stop stop-color="#CB997E" />';
            echo '    <stop offset="1" stop-color="#FFE8D6" />';
            echo '</linearGradient>';
            echo '<linearGradient id="paint3_linear_48_1745" x1="1211.48" y1="38.7918" x2="1159.74" y2="589.938" gradientUnits="userSpaceOnUse">';
            echo '    <stop stop-color="#6B705C" />';
            echo '    <stop offset="1" stop-color="#FFE8D6" />';
            echo '</linearGradient>';
        } elseif ($type === 'customer') {
        // Linear gradient for the first path
        echo '<linearGradient id="paint0_linear_48_1745" x1="398.206" y1="52.5088" x2="24.6795" y2="527.766" gradientUnits="userSpaceOnUse">';
        echo '    <stop offset="0" stop-color="#FF69B4" />';  // Start with pink
        echo '    <stop offset="1" stop-color="#000000" />';  // End with black
        echo '</linearGradient>';

        // Linear gradient for the second path
        echo '<linearGradient id="paint1_linear_48_1745" x1="616.525" y1="228.098" x2="378.649" y2="729.852" gradientUnits="userSpaceOnUse">';
        echo '    <stop offset="0" stop-color="#FF69B4" />';  // Start with pink
        echo '    <stop offset="1" stop-color="#000000" />';  // End with black
        echo '</linearGradient>';

        // Linear gradient for the third path
        echo '<linearGradient id="paint2_linear_48_1745" x1="1126.86" y1="392.163" x2="690.767" y2="742.572" gradientUnits="userSpaceOnUse">';
        echo '    <stop offset="0" stop-color="#FF69B4" />';  // Start with pink
        echo '    <stop offset="1" stop-color="#000000" />';  // End with black
        echo '</linearGradient>';

        // Linear gradient for the fourth path
        echo '<linearGradient id="paint3_linear_48_1745" x1="1211.48" y1="38.7918" x2="1159.74" y2="589.938" gradientUnits="userSpaceOnUse">';
        echo '    <stop offset="0" stop-color="#FF69B4"/>';  // Start with pink
        echo '    <stop offset="1" stop-color="#000000" />';  // End with black
        echo '</linearGradient>';}
        ?>

    </defs>
  </svg>

</div>


<section class="talent">
  <div class="container">
    <h2>Top 3 Popular Artists!</h2>
    <div class="talent-grid">

      <?php


      usort($data, function ($a, $b) {
          return $b['VisitCount'] - $a['VisitCount']; // Sort by VisitCount in descending order
      });
      // Only keep the top 3 profiles
      $top3Profiles = array_slice($data, 0, 3);

      appendartists($top3Profiles,$userId,$type);




      ?>

    </div>
  </div>
</section>

<section class="category" >
  <div class="container" >

    <div class="sort-container">
        <select name="sort" id="sort">
            <option value="all" selected>All</option>
            <option value="visual">Visual Art</option>
            <option value="performing">Performing Art</option>
            <option value="literary">Litrary Art</option>
            <option value="digital">Digital Art</option>
            <option value="other">other</option>
        </select>
    
    </div>
    <div class="category-grid artist-profile" style="border:1px solid; border-radius: 2vh;" id="visualcategory">
        <h2>Visual Art</h2>
        <div class="category-cards" >
            <?php

            appendartists($visual,$userId,$type);
            ?>
        </div>

        
    </div>
      <div class="category-grid artist-profile" style="border:1px solid; border-radius: 2vh;" id="performingcategory">
          <h2>Performing Art</h2>
          <div class="category-cards">
              <?php

              appendartists($performing,$userId,$type);
              ?>
          </div>
      </div>
      <div class="category-grid artist-profile" style="border:1px solid; border-radius: 2vh;" id="literarycategory">
          <h2>Literary Art</h2>
            <div class="category-cards">   <?php

                appendartists($literary,$userId,$type);
                ?>
            </div>
      </div>
      <div class="category-grid artist-profile" style="border:1px solid; border-radius: 2vh;" id="digitalcategory">
          <h2>Digital Art</h2>
<div class="category-cards">
    <?php

    appendartists($digital,$userId,$type);
    ?>
</div>
      </div>
      <div class="category-grid artist-profile" style="border:1px solid; border-radius: 2vh; " id="othercategory">
          <h2>Other</h2>
<div class="category-cards">
    <?php

    appendartists($other,$userId,$type);
    ?>
</div>
      </div>
</section>


</body>

<script src="../Resources/jquery.js"></script>

<script src="home.js"></script>



</html>


<?php
function appendartists($artistProfiles, $userId,$type) {
    // Loop through each artist's profile data
    if($artistProfiles == null) {
        echo ' <h3> There are no Artists in this category</h3>';
    }

    foreach ($artistProfiles as $artist) {
        $pic = !empty($artist['Picture']) ? $artist['Picture'] : '../uploads/media/Default.jpg';

        // Collect services from the result, e.g., AllServices
        $services = $artist['AllServices']; // This should be a comma-separated list of services

        // Reset $otherservices array for each artist
        $otherservices = [];

        // Break services into an array, skipping the first one (since it's the main service)
        $serviceArray = explode(',', $services);
        for ($i = 1; $i < count($serviceArray); $i++) {
            $otherservices[$i - 1] = $serviceArray[$i];
        }

        // First service
        $firstService = $artist['FirstService']; // The first service is the main service

        // Generate other services as a string for display
        $serviceList = implode(', ', $otherservices); // Create a comma-separated list for other services

        // Check if $serviceList is empty and set a default message or hide the section
        $otherServicesDisplay = !empty($serviceList) ? $serviceList : "No other services available";

        // Break the text into lines of a specific length to simulate text wrapping
        $wrappedText = wrapSvgText($otherServicesDisplay, 25); // Wrap after 25 characters (adjust for your SVG size)

        // Create the div for each artist using the data from the $artist array
        echo "<a href='../Rufa/artistprofile.php?artistId={$artist['ArtistID']}&userId={$userId}&type={$type}' class='artist-link' onclick='' >
    <div class='card'>
      <h6 id='aid' style='display:none'>{$artist['ArtistID']}</h6>
      <div class='profile'>
        <img src='$pic' alt='{$artist['FullName']}'.'Picture' title='{$artist['FullName']}' />
      </div>
      <h3>{$artist['FullName']}</h3>
      <h4>$firstService</h4>

      <svg id='svgContainer' style='margin-left: 5.4vw; max-width: 16vw; min-width: 10vw;' viewBox='0 0 184 207' fill='none' xmlns='http://www.w3.org/2000/svg'>
        <path d='M0.306297 39.7994C-1.28733 18.2716 15.8122 -0.0519895 37.3996 0.0509344L147.016 0.573563C167.45 0.670989 183.929 17.3152 183.822 37.7495L183.129 169.706C183.011 192.282 162.877 209.499 140.559 206.111L40.3148 190.891C23.2577 188.302 10.2603 174.266 8.98663 157.06L0.306297 39.7994Z' fill='#8F8B8B' style='overflow-x: hidden;' fill-opacity='0.27'/>

        <text id='serviceText' x='15' y='40' font-size='13' fill='black'>
            <tspan x='15' dy='0'>Other Services:</tspan>
            $wrappedText
        </text>
      </svg>
    </div>
    </a>";
    }
}

// Function to wrap text for SVG
function wrapSvgText($text, $maxCharsPerLine) {
    $words = explode(' ', $text);
    $lines = [];
    $currentLine = '';

    foreach ($words as $word) {
        // Check if adding the next word exceeds the max length per line
        if (strlen($currentLine . ' ' . $word) <= $maxCharsPerLine) {
            $currentLine .= ($currentLine === '' ? '' : ' ') . $word;
        } else {
            // If the current line is full, push it to the lines array
            $lines[] = $currentLine;
            // Start a new line with the current word
            $currentLine = $word;
        }
    }

    // Add the last line
    if (!empty($currentLine)) {
        $lines[] = $currentLine;
    }

    // Generate the SVG <tspan> elements for each line
    $svgText = '';
    $dy = 20; // Set the line height (you can adjust it)
    foreach ($lines as $i => $line) {
        $svgText .= "<tspan x='15' dy='{$dy}'>$line</tspan>";
        $dy = 20; // Keep the line height consistent
    }

    return $svgText;
}


?>




