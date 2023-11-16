<?php

require_once '../vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;
//use Spipu\Html2Pdf\Exception\Html2PdfException;
//use Spipu\Html2Pdf\Exception\ExceptionFormatter;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "Méthode non autorisée";
    exit;
}

$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$genre = isset($_POST['genre']) ? $_POST['genre'] : '';
$cityDistrict = isset($_POST['cityDistrict']) ? $_POST['cityDistrict'] : '';
$learning = isset($_POST['get_learning']) ? $_POST['get_learning'] : '';
$ticket = isset($_POST['ticket_number']) ? $_POST['ticket_number'] : '';



// Validation
if (empty($name) || empty($email) || empty($phone)) {
    echo "Tous les champs obligatoires sont requis.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Adresse email invalide.";
    exit;
}

if (!preg_match("/^(\+225|00225)?[ -]?(\d{2}[ -]?){4}\d{2}$/", $phone)) {
    echo "Numéro de téléphone invalide.";
    exit;
}

// Envoi des données à AIRTABLE
$api_url = "https://api.airtable.com/v0/appHQucHvSDAOanyW/Pass-IDtombola-STAM23";
$api_key = "keySC50HbEpJT9R3U";
$headers = array(
    "Authorization: Bearer " . $api_key,
    "Content-Type: application/json"
);

$data = array(
    "fields" => array(
        "Nom & Prénoms" => $name,
        "Adresse email" => $email,
        "Téléphone" => $phone,
        "Ville-Quartier" => $cityDistrict,
        "Sexe" => $genre,
        "Masterclass" => $learning,
        "Numero-Tombola" => $ticket
    )
);

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$err = curl_error($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$payload = array("status" => "", "message" => "", "url" => ""); 

if ($err) {

    $payload["status"] = 400; 
    $payload["message"] = "Erreur lors de l'envoi des données à AIRTABLE: $err";

} elseif ($httpcode != 200) {

    $payload["status"] = 400;
    $payload["message"] = "Erreur lors de l'envoi des données à AIRTABLE: Réponse HTTP $httpcode";

} else {
  
    //$payload["status"] = 200;
    //$payload["message"] = "Soumission complète";

  $pdf_content ="
    <!DOCTYPE html>
    <html lang=\"fr\">

    <head>
    <meta charset=\"UTF-8\" />
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0\" />
    <title>ID Tombola et PASS</title>
    <style>
        *,
        ::before,
        ::after {
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        }

        html,
        body {
        max-width: 100%;
        overflow-x: hidden;
        }

        .bigcontainer {
        display: grid;
        grid-template-columns: 1fr;
        justify-items: center;
        padding: 20px 0 20px 20px;
        }

        .flexcontainer {
        background-image: url(images/fond-invitation.jpg);
        display: grid;
        grid-template-rows: repeat(4, auto);
        justify-items: center;
        width: 95%;

        max-width: 600px;

        margin: auto;
        padding: 0px 20px;
        border-radius: 10px;
        gap: 20px;
        }

        .flexcontainer img {
        max-width: 100%;
        height: auto;
        }

        .invitation {
        text-align: center;
        width: 60%;
        margin: 40px 10px auto;
        }

        .invitation img {
        width: 100%;
        }

        .flexlogotext {
        display: flex;
        padding: 30px 40px;
        }

        .flexlogotext div {
        flex-basis: 50%;
        font-size: 0.9em;
        }

        strong {
        text-transform: uppercase;
        }

        .footer-img {
        margin-top: 5px;
        }

        .footer-img img {
        max-width: 100%;
        height: auto;
        }

        h1 {
        color: #d1850a;
        font-family: sans-serif;
        text-align: center;
        }

        section p {
        font-size: 0.8em;
        }

        .notice {
        width: 80%;
    
        max-width: 600px;
    
        padding: 20px;
        margin: auto;
        
        }

        .userfull {
        text-transform: uppercase;
        color: #ba1628;
        font-size: 1.5em;
        font-family: sans-serif;
        text-align: center;
        }

        .userfull .numberID {
        font-size: 2.5em;
        }

        .frize {
        width: 80%;
        max-width: 500px;
        }

        .frize img {
        display: block;
        }

        @media screen and (max-width: 600px) {
        .bigcontainer {
            display: grid;
            grid-template-columns: 1fr;
            justify-items: center;
            padding: 0;
        }

        .flexcontainer {
            background-image: url(https://topdigitalevel.site/stam2023/webPassID/images/fond-invitation.jpg);
            display: grid;
            grid-template-rows: repeat(4, auto);
            justify-items: center;
            width: 100%;
            max-width: 600px;
    
            margin: auto;
            padding: 0px 10px;
            border-radius: none;
            gap: 12px;
        }

        .notice {
            width: 100%;
            
            padding: 10px 20px;
            font-size: 0.6em;
        }

        .flexcontainer img {
            max-width: 100%;
            height: auto;
        }

        .invitation {
            text-align: center;
            width: 80%;
            margin: 40px 10px auto;
        }

        .flexlogotext {
            display: flex;
            padding: 20px 10px;
        }

        .flexlogotext div {
            font-size: 0.7em;
        }

        .flexlogotext img {
            width: 90%;
        }

        section p {
            font-size: 0.7em;
        }

        .userfull {
            font-size: 1em;
        }

        .userfull .numberID {
            font-size: 2em;
        }

        .footer-img img {
            max-width: 98%;
            height: auto;
        }

        .frize {
            width: 80%;
        }
        }
    </style>
    </head>

    <body>
    <div class=\"bigcontainer\">
        <div class=\"flexcontainer\">
        <div class=\"invitation\">
            <img src=\"https://topdigitalevel.site/stam2023/webPassID/images/invitation-text.png\" alt=\"invitation\">
        </div>
        <section class=\"flexlogotext\">
            <div>
            <img src=\"https://topdigitalevel.site/stam2023/webPassID/images/logo-dorer.png\" alt=\"logo du stam\" />
            </div>
            <div>
            <p>Nous sommes honorés de vous convier à la Cérémonie d’Ouverture du Salon des Téléphones et Applications
                Mobiles 2ème édition sur le thème : <strong> « jeunesse et employabilité, digitalisation de
                l’administration, écosystème zlecaf : Piliers de la croissance Africaine »</strong>.</p> <br>
            <p class=\"parrainnage\">Sous le HAUT PARRAINAGE et la Présence effective de son Excellence Monsieur le Ministre
                de la Promotion de la Jeunesse, de l’Insertion Professionnelle et du Service Civique, Porte-Parole Adjoint
                du Gouvernement, la cérémonie se tiendra ce <strong style=\"color: #ba1628;\">Vendredi 17 Novembre 2023, à
                9h</strong> ; à la salle Kodjo Ébouclé du Palais de la Culture de Treichville.
            </p>
            </div>
        </section>
        <section>
            <h1>PASS VISITEUR ET ID TOMBOLA</h1>
        </section>
        <section>
            <p style=\"padding: 0 20px;\">
            <strong> Votre identifiant unique pour la tombola</strong> à la deuxième édition du Salon des Téléphones et
            Applications Mobiles les 17 et 18 Novembre au Palais de la Culture :
            </p>
        </section>
        <section class=\"userfull\">
            <h2>$name</h2>
            <h2 class=\"numberID\">$ticket</h2>
        </section>
        <div class=\"frize\">
            <img src=\"https://topdigitalevel.site/stam2023/webPassID/images/frize-dorer.png\" alt=\"\">
        </div>
        </div>
        <div class=\"notice\">
        <p>
            * Une copie de ce PASS identifiant vous a été transmis par email, vous
            en aurez besoin lors du tirage au sort de la Tombola pendant le Salon.
        </p>
        </div>
        </section>
        <div class=\"footer-img\">
        <img src=\"https://topdigitalevel.site/stam2023/webPassID/images/footer-contacts.jpg\" alt=\"contacts\" />
        </div>
    </div>
    </body>

    </html>"; 

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->isSMTP();                                           
    $mail->Host       = 'mail.topdigitalevel.site';                    
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = 'info@topdigitalevel.site';                     
    $mail->Password   = 'undPzZ3x3U';                             
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
    $mail->Port       = 465;                                   

    //Recipients
    $mail->setFrom('infoprochmedia@prochmedia.com', 'STAM 2023');
    $mail->addAddress($email, $name);                    
    $mail->addReplyTo('logans@ns2po.ci', 'Logan SERY');
    $mail->addCC('infoprochmedia@gmail.com');
    $mail->addCC('logans@ns2po.ci');

    //Content
    $mail->isHTML(true);  
    $mail->Subject = 'Votre identifiant et Pass Visiteur unique pour le STAM 2023';
    $mail->Body    = $pdf_content;
    $mail->AltBody = 'Pass STAM 2023';
    $mail->CharSet = 'UTF-8';
    if($mail->send())
    {
        //$pdf = new Html2Pdf('P', 'A4', 'fr');
        //$pdf->writeHTML($htmlContent);
        #$pdf->output("https://topdigitalevel.site/stam2023/webPassID/Pdfs/$ticket.pdf", 'F');
        //$pdf->output("../Pdfs/$ticket.pdf", 'F'); //home/cp1970737p23/public_html/stam2023/webPassID/Pdfs
        //$url = "https://topdigitalevel.site/stam2023/webPassID/Pdfs/$ticket.pdf";
        //$payload["url"] = trim($url);
        $payload["status"] = 200;
        $payload["message"] = "Soumission complète";
        $payload["url"] = "https://topdigitalevel.site/stam2023/webPassID/presentation.php?name=$name&ticket=$ticket";
    }else{

        $payload["status"] = 400;
        $payload["message"] = "Email and Pdf not completed !";
    }   
}


echo json_encode($payload);
?>