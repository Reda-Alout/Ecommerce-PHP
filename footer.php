<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $footer_about = $row['footer_about'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $contact_address = $row['contact_address'];
    $footer_copyright = $row['footer_copyright'];
    $total_recent_post_footer = $row['total_recent_post_footer'];
    $total_popular_post_footer = $row['total_popular_post_footer'];
    $newsletter_on_off = $row['newsletter_on_off'];
    $before_body = $row['before_body'];
}
?>

<?php if ($newsletter_on_off == 1): ?>
<section class="home-newsletter">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="single">
                    <?php
                    if (isset($_POST['form_subscribe'])) {
                        $valid = 1;
                        $error_message1 = '';
                        $success_message1 = '';

                        if (empty($_POST['email_subscribe'])) {
                            $valid = 0;
                            $error_message1 .= LANG_VALUE_131;
                        } else {
                            if (filter_var($_POST['email_subscribe'], FILTER_VALIDATE_EMAIL) === false) {
                                $valid = 0;
                                $error_message1 .= LANG_VALUE_134;
                            } else {
                                $statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_email=?");
                                $statement->execute(array($_POST['email_subscribe']));
                                $total = $statement->rowCount();
                                if ($total) {
                                    $valid = 0;
                                    $error_message1 .= LANG_VALUE_147;
                                } else {
                                    // Compter le nombre total d'abonnés
                                    $statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_subscriber");
                                    $statement->execute();
                                    $total_subscribers = $statement->fetchColumn();

                                    // Calculer le nouvel ID
                                    $new_id = $total_subscribers + 1;

                                    // Générer une clé
                                    $key = md5(uniqid(rand(), true));

                                    // Obtenir la date actuelle
                                    $current_date = date('Y-m-d');
                                    $current_date_time = date('Y-m-d H:i:s');

                                    // Insertion des données dans la base de données
                                    $statement = $pdo->prepare("INSERT INTO tbl_subscriber (subs_id, subs_email, subs_date, subs_date_time, subs_hash, subs_active) VALUES (?, ?, ?, ?, ?, ?)");
                                    $statement->execute(array($new_id, $_POST['email_subscribe'], $current_date, $current_date_time, $key, 0));


                                    // Sending Confirmation Email
                                    $to = $_POST['email_subscribe'];
                                    $subject = 'Subscriber Email Confirmation';


                                    $message = '<p>Thanks for your interest in subscribing to our newsletter!<br><br>Please click this link to confirm your subscription:<br> 
                                    <a href="' . $_ENV['BASE_URL'] . 'verify.php?email=' . $to . '&key=' . $key . '">This link will be active only for 24 hours.</a>';

                                    $mail = new PHPMailer(true); // Create a new PHPMailer instance

                                    try {
                                        $mail->isSMTP(); // Send using SMTP
                                        $mail->Host = $_ENV['SMTP_HOST']; // Set the SMTP server to send through
                                        $mail->SMTPAuth = true; // Enable SMTP authentication
                                        $mail->Username = $_ENV['SMTP_USERNAME']; // SMTP username
                                        $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP password
                                        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION']; // Enable implicit TLS encryption
                                        $mail->Port = $_ENV['SMTP_PORT']; // TCP port to connect to
                                        
                                        // Recipients
                                        $mail->setFrom($_ENV['SMTP_USERNAME']);                                        
                                        $mail->addAddress($to); // Add a recipient

                                        // Content
                                        $mail->isHTML(true); // Set email format to HTML
                                        $mail->Subject = $subject;
                                        $mail->Body = $message;

                                        $mail->send();
                                        $success_message1 = LANG_VALUE_136;
                                    } catch (Exception $e) {
                                        $error_message1 .= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    }
                                }
                            }
                        }

                        if ($error_message1 != '') {
                            echo "<script>alert('" . $error_message1 . "')</script>";
                        }
                        if ($success_message1 != '') {
                            echo "<script>alert('" . $success_message1 . "')</script>";
                        }
                    }
                    ?>
                    <form action="" method="post">
                        <?php $csrf->echoInputField(); ?>
                        <h2><?php echo LANG_VALUE_93; ?></h2>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="<?php echo LANG_VALUE_95; ?>"
                                name="email_subscribe">
                            <span class="input-group-btn">
                                <button class="btn btn-theme" type="submit"
                                    name="form_subscribe"><?php echo LANG_VALUE_92; ?></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-12 copyright">
            &copy; Copyright 2024. Made by
        <a rel="noreferrer" target="_blank" href="https://redaalout-portfolio.web.app">REDA ALOUT</a>
        All rights reserved.
            </div>
        </div>
    </div>
</div>

<a href="#" class="scrollup">
    <i class="fa fa-angle-up"></i>
</a>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $stripe_public_key = $row['stripe_public_key'];
    $stripe_secret_key = $row['stripe_secret_key'];
}
?>

<script src="assets/js/jquery-2.2.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>
<script src="assets/js/megamenu.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/owl.animate.js"></script>
<script src="assets/js/jquery.bxslider.min.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/rating.js"></script>
<script src="assets/js/jquery.touchSwipe.min.js"></script>
<script src="assets/js/bootstrap-touch-slider.js"></script>
<script src="assets/js/select2.full.min.js"></script>
<script src="assets/js/custom.js"></script>
<script>
function confirmDelete() {
    return confirm("Sure you want to delete this data?");
}
$(document).ready(function() {
    advFieldsStatus = $('#advFieldsStatus').val();

    $('#paypal_form').hide();
    $('#stripe_form').hide();
    $('#bank_form').hide();

    $('#advFieldsStatus').on('change', function() {
        advFieldsStatus = $('#advFieldsStatus').val();
        if (advFieldsStatus == '') {
            $('#paypal_form').hide();
            $('#stripe_form').hide();
            $('#bank_form').hide();
        } else if (advFieldsStatus == 'PayPal') {
            $('#paypal_form').show();
            $('#stripe_form').hide();
            $('#bank_form').hide();
        } else if (advFieldsStatus == 'Stripe') {
            $('#paypal_form').hide();
            $('#stripe_form').show();
            $('#bank_form').hide();
        } else if (advFieldsStatus == 'Bank Deposit') {
            $('#paypal_form').hide();
            $('#stripe_form').hide();
            $('#bank_form').show();
        }
    });
});

$(document).on('submit', '#stripe_form', function() {
    // createToken returns immediately - the supplied callback submits the form if there are no errors
    $('#submit-button').prop("disabled", true);
    $("#msg-container").hide();
    Stripe.card.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
        // name: $('.card-holder-name').val()
    }, stripeResponseHandler);
    return false;
});
Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');

function stripeResponseHandler(status, response) {
    if (response.error) {
        $('#submit-button').prop("disabled", false);
        $("#msg-container").html(
            '<div style="color: red; border: 1px solid; margin: 10px 0px; padding: 5px;"><strong>Error:</strong> ' +
            response.error.message + '</div>');
        $("#msg-container").show();
    } else {
        var form$ = $("#stripe_form");
        var token = response['id'];
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        form$.get(0).submit();
    }
}
</script>
<?php echo $before_body; ?>
</body>

</html>