<?php
// Turn on error reporting just in case
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $First_Name = $_POST["First_Name"] ?? '';
    $Last_Name = $_POST["Last_Name"] ?? '';
    $DOB = $_POST["DOB"] ?? '';
    $Gender = $_POST["Gender"] ?? '';
    $Email_id = $_POST["Email_id"] ?? '';
    $Password = $_POST["Password"] ?? '';

    $Hashed_Password = password_hash($Password, PASSWORD_DEFAULT);

    $con = new mysqli('localhost', 'root', '', 'Login');

    // 1. First, check if the database connected successfully
    if ($con->connect_error) {
        die("Connection Failed: " . $con->connect_error);
    } else {
        // 2. Prepare the statement
        $stmt = $con->prepare("INSERT INTO Data (First_Name, Last_Name, DOB, Gender, Email_id, Password) VALUES (?, ?, ?, ?, ?, ?)");
        
        // 3. Bind the parameters
        $stmt->bind_param("ssssss", $First_Name, $Last_Name, $DOB, $Gender, $Email_id, $Hashed_Password);
        
        // 4. Execute and check if successful. IF successful, send to Google Sheets.
        if ($stmt->execute()) {
            
            // --- GOOGLE SHEETS WEBHOOK INTEGRATION ---
            // Paste your Google Web App URL inside the quotes below:
            $google_url = "https://script.google.com/macros/s/AKfycbwOlGYJAWTWi4oi1uHtfW0WHEjdoGEwql6fl18sEXCYtjRfc-lt7suAgCVE28g2kVI1/exec"; 
            
            $data_to_send = [
                'First_Name' => $First_Name,
                'Last_Name'  => $Last_Name,
                'DOB'        => $DOB,
                'Gender'     => $Gender,
                'Email_id'   => $Email_id
            ];
            
            $ch = curl_init($google_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_to_send));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
            curl_exec($ch);
            curl_close($ch);
            // --- END GOOGLE SHEETS INTEGRATION ---
            
            echo "<script>
                localStorage.setItem('anuvadh_logged_in', 'true');
                window.location.href = '../index.html';
            </script>";
            
        } else {
            // If the database fails, show the error
            echo "<h3 style='color: red; text-align: center;'>Error: " . $stmt->error . "</h3>";
            echo "<p style='text-align: center;'><a href='../signup.html'>Go back to signup</a></p>";
        }
        
        // 5. Close connections
        $stmt->close();
        $con->close();
    }
}
?>