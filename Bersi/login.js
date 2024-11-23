

document.getElementById('loginbtn').addEventListener('click', checkpassword);
document.getElementById('showpassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    if (this.checked) {
        passwordInput.type = 'text'; // Change to text to show password
    } else {
        passwordInput.type = 'password'; // Change back to password to hide it
    }
});

function checkpassword(){
    let password=document.getElementById('password')
    let email=document.getElementById('email').value;
    let passwordEncrypted = passwordToBinaryData(password.value);
    // alert(passwordEncrypted)
    fetch('../PHP/check.php', { // Replace with your actual PHP file path
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            email: email,
            password: passwordEncrypted
        })
    })
        .then(response => {
            if (!response.ok) {
                alert('Network response was not ok')
                throw new Error('Network response was not ok');
            }
            else{

                return response.json();
            }
        })
        .then(data => {
           let dvalues=Object.values(data)

            if (data.account_type !== 'artist' &&data.account_type !== 'customer') {
                alert("Invalid email or password");
            } else {

                if(dvalues[0] === 'artist'){

                    window.location.href = "../fi's/home.php?type=" + 'artist'+ "&userId=" + dvalues[1];}
                if(data.account_type === 'customer'){

                    window.location.href = "../fi's/home.php?type=" + 'customer'+ "&userId=" + dvalues[1];}
                
            }
        })
        .catch(error => console.error('Error:', error));
}