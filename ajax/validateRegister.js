console.log("validateRegister.js loaded!");
function validateRegister()
{
    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let phone = document.getElementById("phone").value;
    let nationality = document.getElementById("nationality").value;
    let password = document.getElementById("password").value;
    let confirm = document.getElementById("confirm_password").value;

    let xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function ()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            let data = JSON.parse(this.responseText);

            document.getElementById("nameError").innerHTML = "";
            document.getElementById("emailError").innerHTML = "";
            document.getElementById("phoneError").innerHTML = "";
            document.getElementById("nationalityError").innerHTML = "";
            document.getElementById("passwordError").innerHTML = "";
            document.getElementById("confirmError").innerHTML = "";
            if (data.success)
            {
                alert("Validation Successful");
                // window.location.href = "/Web_Tech_Final/Assignmet/views/auth/login.php";

            }

            else
            {
                if (data.errors.name)
                {
                    document.getElementById("nameError").innerHTML = data.errors.name;
                }

                if (data.errors.email)
                {
                    document.getElementById("emailError").innerHTML = data.errors.email;
                }

                if (data.errors.phone)
                {
                    document.getElementById("phoneError").innerHTML = data.errors.phone;
                }

                if (data.errors.nationality)
                {
                    document.getElementById("nationalityError").innerHTML = data.errors.nationality;
                }

                if (data.errors.password)
                {
                    document.getElementById("passwordError").innerHTML = data.errors.password;
                }

                if (data.errors.confirm)
                {
                    document.getElementById("confirmError").innerHTML = data.errors.confirm;
                }
            }
        }
    };

  xhttp.open("POST", "/Web_Tech_Final/Assignmet/controllers/registerController.php", true);  
  xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

    xhttp.send("name=" + encodeURIComponent(name) +"&email=" + encodeURIComponent(email) +"&phone=" + encodeURIComponent(phone) +"&nationality=" + encodeURIComponent(nationality) +
        "&password=" + encodeURIComponent(password) +"&confirm_password=" + encodeURIComponent(confirm));

    return false;
    
}

document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();
    validateRegister();
});