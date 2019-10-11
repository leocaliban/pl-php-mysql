<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <title>Risk Jobs - Registro</title>
    <meta name="description" content="Risk Jobs" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/style.min.css" />
</head>

<body>


    <?php

    $first_name = '';
    $last_name = '';
    $email  = '';
    $phone = '';
    $job = '';
    $resume = '';

    if (isset($_POST['submit'])) {
        $first_name = $_POST['firstname'];
        $last_name = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $job = $_POST['job'];
        $resume = $_POST['resume'];
        $output_form = 'no';

        if (empty($first_name)) {
            // $first_name is blank
            echo '<p class="error">You forgot to enter your first name.</p>';
            $output_form = 'yes';
        }

        if (empty($last_name)) {
            // $last_name is blank
            echo '<p class="error">You forgot to enter your last name.</p>';
            $output_form = 'yes';
        }

        if (empty($email)) {
            // $email is blank
            echo '<p class="error">You forgot to enter your email address.</p>';
            $output_form = 'yes';
        }

        if (!preg_match('/^\(?[2-9]\d{2}\)?[-\s]\d{3}-\d{4}$/', $phone)) {
            // $phone is invalid
            echo '<p class="error">Número de telefone inválido.</p>';
            $output_form = 'yes';
        }

        if (empty($job)) {
            // $job is blank
            echo '<p class="error">You forgot to enter your desired job.</p>';
            $output_form = 'yes';
        }

        if (empty($resume)) {
            // $resume is blank
            echo '<p class="error">You forgot to enter your resume.</p>';
            $output_form = 'yes';
        }
    } else {
        $output_form = 'yes';
    }

    if ($output_form == 'yes') {
        ?>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <p>Register with Risky Jobs, and post your resume.</p>
            <table>
                <tr>
                    <td><label for="firstname">First Name:</label></td>
                    <td><input id="firstname" name="firstname" type="text" value="<?php echo $first_name; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="lastname">Last Name:</label></td>
                    <td><input id="lastname" name="lastname" type="text" value="<?php echo $last_name; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td><input id="email" name="email" type="text" value="<?php echo $email; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="phone">Phone:</label></td>
                    <td><input id="phone" name="phone" type="text" value="<?php echo $phone; ?>" /></td>
                </tr>
                <tr>
                    <td><label for="job">Desired Job:</label></td>
                    <td><input id="job" name="job" type="text" value="<?php echo $job; ?>" /></td>
                </tr>
            </table>
            <p>
                <label for="resume">Paste your resume here:</label><br />
                <textarea id="resume" name="resume" rows="4" cols="40"><?php echo $resume; ?></textarea><br />
                <input type="submit" name="submit" value="Submit" />
            </p>
        </form>

    <?php
    } else if ($output_form == 'no') {
        echo '<p>' . $first_name . ' ' . $last_name . ', thanks for registering with Risky Jobs!</p>';

        // code to insert data into the RiskyJobs database...
    }
    ?>
</body>

</html>