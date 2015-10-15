<?php
    #
    # Cap7 Login
    # Copyright © 2015 Jonathan Hasbun
    #


    # Start session.
    session_start();

    # Grab the variables from POST.
    $user=$_POST['user'];
    $pass=$_POST['pass'];


    # Initialize error reporting.
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);

    # Initialize DB variables.
    $host        = "host=localhost";
    $port        = "port=5432";
    $dbname      = "dbname=cap7";
    $credentials = "user=admin password=^^y_PG_d@t@";

    # Connect to the Database.
    $db=pg_connect("$host $port $dbname $credentials");

    # Connect to the specified database and check the connection.
    if (!$GLOBALS['db']) {
        echo "Error: Unable to open database\n";
    } else {
        echo "Opened database successfully\n";
    }

    # Find the specified user and pass, otherwise throw an error.
    $sql=sprintf(
        "SELECT username, password
         FROM members
         WHERE username='%s' AND password='%s'",
         pg_escape_string($user),
         pg_escape_string($pass));
    $result=pg_query($GLOBALS['db'], $sql);

    if (!$result) {
        echo pg_last_error($GLOBALS['db']);
        exit;
    } else {
        $test=pg_fetch_row($result);

        # Login successful.
        if ($user===$test[0] && $pass===$test[1]) {
            # Initialize session data.
            $_SESSION['login_user']=$test[0];
            #$_SESSION['team']=$test[2];
            #$_SESSION['role']=$test[3];
            #$_SESSION['access']=$test[4];

            # Redirect to the correct menu.
            switch ($_SESSION['access']) {
                case "guest":
                    header("location: menu.html");
                    break;
                case "member":
                    header("location: memberMenu.html");
                    break;
                default:
                    header("location: menu.html");
            }
        }
        # Login failed.
        else {
            $error="Username or Password was incorrect";

            # Redirect back to the Login page.
            header("location: login.html");
        }
    }

    pq_close($GLOBALS['db']);
?>
