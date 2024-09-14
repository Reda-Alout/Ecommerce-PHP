<?php require_once('header.php'); ?>

<?php
if (isset($_REQUEST['email']) && isset($_REQUEST['key'])) {
    $email = $_REQUEST['email'];
    $key = $_REQUEST['key'];
    $var = 1;

    // Check if the token is correct and matches with the database
    $statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_email=?");
    $statement->execute(array($email));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        if ($key != $row['subs_hash']) {
            header('location: ' . BASE_URL);
            exit;
        }
    }

    // Everything is correct. Now activate the user by updating the database
    if ($var != 0) {
        $statement = $pdo->prepare("UPDATE tbl_subscriber SET subs_active=? WHERE subs_email=?");
        $statement->execute(array(1, $email));

        $success_message = '<p style="color:green;">Your email is verified successfully.</p><p><a href="' . BASE_URL . 'login.php" style="color:#167ac6;font-weight:bold;">Click here to login</a></p>';
    }
} else {
    header('location: ' . BASE_URL);
    exit;
}
?>

<div class="page-banner" style="background-color:#444;">
    <div class="inner">
        <h1>Subscribe Successful</h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">
                    <?php 
                        if (isset($error_message)) {
                            echo $error_message;
                        }
                        if (isset($success_message)) {
                            echo $success_message;
                        }
                    ?>
                </div>                
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
