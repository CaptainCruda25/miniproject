<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance System</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div id="preloader" style="display: none;">
        <div class="loader"></div>
    </div>
    <header>
        <div class="container">
            <h1>Attendance System</h1>
        </div>
    </header>

    <main>
        <section class="login-section">
            <form id="login">
                <div id="login-container">
                    <h1>Login</h1>
                    <p id="error"></p>
                    <div id="login-box">
                        <div id="user">
                            <input type="text" name="uname" placeholder="Username">
                        </div>
                        <div id="pass">
                            <input type="password" name="pword" placeholder="Password">
                        </div>
                        <div id="btn">
                            <input type="submit" value="Login">
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About the Developer</h3>
                <p>I am a student at Cavite State University Naic, studying Computer Science. 
                    Passionate about software development, I’m building skills in programming 
                    and problem-solving to create innovative solutions in tech.</p>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Address: Ciudad Nuevo Phase 3A Timalan Balsahan, Naic, Cavite</p>
                <p>Email: crudacarlemmanuelt25@gmail.com</p>
                <p>Phone: (123) 456-7890</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Services</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-copyright">
            &copy; 2024 Attendance System. All rights reserved.
        </div>
    </footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).on('submit','#login', function (e) {
            e.preventDefault();

            var loginData = new FormData(this);
            loginData.append('open', true);
            
            $.ajax({
                url: 'function.php',
                method: 'POST',
                data: loginData,
                processData: false,
                contentType: false,
                beforeSend:function(response){
                    $('#preloader').show();
                },
                success: function(response){
                    var res = jQuery.parseJSON(response);
                    
                    if(res.status === 422){
                        $('#error').text(res.message);
                    }
                    else if(res.status === 200){
                        setTimeout(function(){
                            alert(res.message);
                            $('#preloader').show();
                            $('#dashboardPreloader').hide();
                            window.location.assign('dashboard/');
                            
                        }, 2000);
                        
                    }
                },

            });
        });
    </script>
</body>
</html>
