// Get the current URL parameters
const urlParams = new URLSearchParams(window.location.search);

// Get type and userId from URL or localStorage
const type = urlParams.get('type') || localStorage.getItem('type') || null;
const userId = urlParams.get('userId') || localStorage.getItem('userid') || null;


let notificationcount = document.getElementById("notification-count");
if (notificationcount.innerText === '0') {
    notificationcount.style.display = 'none';
} else {
    notificationcount.style.display = 'block';
}

// Ensure type is properly defined
if (typeof type === 'undefined') {
    console.error("Error: 'type' is undefined.");
} else {


    // Logic to show the correct profile link based on type (artist or customer)
    if (type === 'artist') {
        document.querySelector('.notification-container').style.display = 'block';
        const artistId = localStorage.getItem('artistId');
        if (artistId) {
            document.getElementById("artistid").innerText = artistId.toString();
            document.getElementById("artistprofilelink").style.display = 'block';
            document.getElementById("loginlink").style.display = 'none';
        } else {
            console.error("Error: 'artistId' not found in localStorage.");
        }

    } else if (type === 'customer') {
        // Apply a special class to change background color for customer page
        document.body.classList.add('customer-pink-theme');

        const customerId = localStorage.getItem('customerId');
        if (customerId) {
            document.getElementById("customerid").innerText = customerId.toString();
            document.getElementById("customerprofilelink").style.display = 'block';
            document.getElementById("loginlink").style.display = 'none';
            document.getElementById("artistprofilelink").style.display = 'none';
        } else {
            console.error("Error: 'customerId' not found in localStorage.");
        }

    } else {
        // If neither artist nor customer, show the login link
        document.getElementById("loginlink").style.display = 'block';
    }
}

// Save parameters in localStorage if they exist
if (type && userId) {
    localStorage.setItem('type', type);
    localStorage.setItem('userid', userId);
}
// const sortSelect = document.getElementById("sort");
const visual = document.getElementById("visualcategory");
const performing = document.getElementById("performingcategory");
const literary = document.getElementById("literarycategory");
const digital = document.getElementById("digitalcategory");
const other = document.getElementById("othercategory");

// Add an event listener for the change event
const sortSelect = $("#sort");

// Add an event listener for the change event
sortSelect.on("change", function () {
    const selectedValue = $(this).val();

    // Fade out all artist profiles first
    $(".category-grid").fadeOut(200, function () {
        // After fading out, we can show the relevant profiles
        switch (selectedValue) {
            case "all":
                // Code to display all artist profiles
                console.log("Showing all artist profiles");
                $(".category-grid").fadeIn(200); // Show all profiles
                break;
            case "visual":
                // Code to display only visual artists
                console.log("Showing visual artists");
                $("#visualcategory").fadeIn(200); // Show only visual profiles
                break;
            case "performing":
                // Code to display only performing artists
                console.log("Showing performing artists");
                $("#performingcategory").fadeIn(200); // Show only performing profiles
                break;
            case "literary": // Corrected from 'litrary' to 'literary'
                // Code to display only literary artists
                console.log("Showing literary artists");
                $("#literarycategory").fadeIn(200); // Show only literary profiles
                break;
            case "digital":
                // Code to display only digital artists
                console.log("Showing digital artists");
                $("#digitalcategory").fadeIn(200); // Show only digital profiles
                break;
            case "other":
                // Code to display other artists
                console.log("Showing other artists");
                $("#othercategory").fadeIn(200); // Show only other profiles
                break;
            default:
                // Handle any unexpected cases
                console.log("Unknown category selected");
        }
    });
});
