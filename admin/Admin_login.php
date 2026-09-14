<?php

session_start();

/* =====================================================
   ALREADY LOGGED IN
===================================================== */

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header("Location: dashboard.php");
    exit;
}


/* =====================================================
   DATABASE
===================================================== */

require_once __DIR__ . "/../includes/db.php";


$error = "";
$username = "";


/* =====================================================
   LOGIN
===================================================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {

        $error = "Please enter your username and password.";

    } elseif (!$conn) {

        $error = "Database connection failed.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password
             FROM admins
             WHERE username = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $error = "Unable to process login.";

        } else {

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {

                $admin = $result->fetch_assoc();

                /*
                 * Password is stored using password_hash()
                 */

                if (password_verify($password, $admin["password"])) {

                    /* Create a new session ID */
                    session_regenerate_id(true);

                    /* Admin session */
                    $_SESSION["is_admin"] = true;

                    $_SESSION["admin_id"] = (int)$admin["id"];

                    $_SESSION["admin_username"] =
                        $admin["username"];

                    $stmt->close();

                    /* Go to dashboard */
                    header("Location: dashboard.php");
                    exit;

                } else {

                    $error = "Incorrect username or password.";

                }

            } else {

                $error = "Incorrect username or password.";

            }

            $stmt->close();
        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Admin Login | Athmananda Art Gallery</title>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =====================================================
   BODY
===================================================== */

body {

    min-height: 100vh;

    font-family: Arial, Helvetica, sans-serif;

    background:
        radial-gradient(
            circle at top left,
            #5b3925 0%,
            #2b1b13 40%,
            #160d09 100%
        );

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 20px;

}


/* =====================================================
   LOGIN CONTAINER
===================================================== */

.login-container {

    width: 420px;

    max-width: 100%;

}


/* =====================================================
   BRAND
===================================================== */

.brand {

    text-align: center;

    margin-bottom: 25px;

    color: white;
}

.brand h1 {

    font-size: 30px;

    letter-spacing: 3px;

    font-weight: 600;

}

.brand p {

    margin-top: 8px;

    font-size: 12px;

    letter-spacing: 4px;

    color: #d5ad78;

    text-transform: uppercase;

}


/* =====================================================
   LOGIN CARD
===================================================== */

.login-card {

    background: #ffffff;

    padding: 42px;

    border-radius: 12px;

    box-shadow:
        0 25px 70px rgba(0,0,0,.45);

}


/* =====================================================
   TITLE
===================================================== */

.login-card h2 {

    color: #2b1b13;

    font-size: 24px;

    margin-bottom: 8px;

}

.login-description {

    color: #777;

    font-size: 13px;

    line-height: 1.6;

    margin-bottom: 28px;

}


/* =====================================================
   ERROR
===================================================== */

.error {

    background: #fff0f0;

    border: 1px solid #e4a5a5;

    color: #a52828;

    padding: 12px 14px;

    border-radius: 6px;

    font-size: 13px;

    margin-bottom: 20px;

}


/* =====================================================
   FORM
===================================================== */

.form-group {

    margin-bottom: 20px;

}


.form-group label {

    display: block;

    color: #2b1b13;

    font-size: 13px;

    font-weight: 600;

    margin-bottom: 8px;

}


.form-group input {

    width: 100%;

    height: 48px;

    padding: 0 14px;

    border: 1px solid #d8d2cc;

    border-radius: 6px;

    background: #faf9f7;

    color: #2b1b13;

    font-size: 14px;

    outline: none;

    transition: .25s;

}


.form-group input:focus {

    border-color: #704527;

    background: #ffffff;

    box-shadow:
        0 0 0 3px rgba(112,69,39,.12);

}


.form-group input::placeholder {

    color: #aaa;

}


/* =====================================================
   LOGIN BUTTON
===================================================== */

.login-button {

    width: 100%;

    height: 50px;

    border: none;

    border-radius: 6px;

    background: #704527;

    color: white;

    font-size: 14px;

    font-weight: 600;

    letter-spacing: .5px;

    cursor: pointer;

    transition: .25s;

}


.login-button:hover {

    background: #55341e;

    transform: translateY(-1px);

}


.login-button:active {

    transform: translateY(0);

}


/* =====================================================
   FOOTER
===================================================== */

.login-footer {

    text-align: center;

    margin-top: 25px;

    color: #999;

    font-size: 11px;

}


.security-text {

    margin-top: 12px;

    color: #777;

    font-size: 11px;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 500px) {

    .login-card {

        padding: 30px 22px;

    }

    .brand h1 {

        font-size: 25px;

    }

}

</style>

</head>


<body>


<div class="login-container">


    <!-- =================================================
         BRAND
    ================================================== -->

    <div class="brand">

        <h1>ATHMANANDA</h1>

        <p>Art Gallery</p>

    </div>


    <!-- =================================================
         LOGIN CARD
    ================================================== -->

    <div class="login-card">


        <h2>Admin Login</h2>


        <p class="login-description">

            Sign in to access your Athmananda
            Art Gallery administration panel.

        </p>


        <!-- ERROR -->

        <?php if ($error !== "") { ?>

            <div class="error">

                <?php
                echo htmlspecialchars($error);
                ?>

            </div>

        <?php } ?>


        <!-- =================================================
             LOGIN FORM
        ================================================== -->

        <form
            method="POST"
            action=""
            autocomplete="on"
        >


            <!-- USERNAME -->

            <div class="form-group">

                <label for="username">

                    Username

                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter admin username"
                    value="<?php
                        echo htmlspecialchars($username);
                    ?>"
                    autocomplete="username"
                    required
                    autofocus
                >

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label for="password">

                    Password

                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter admin password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <!-- LOGIN -->

            <button
                type="submit"
                class="login-button"
            >

                Login to Dashboard

            </button>


        </form>


        <div class="login-footer">

            Athmananda Art Gallery © <?php echo date("Y"); ?>

            <div class="security-text">

                🔒 Authorized admin access only

            </div>

        </div>


    </div>

</div>


</body>

</html>