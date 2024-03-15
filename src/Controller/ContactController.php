<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailerServicesTest;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
   /**
     * @Route("/send-email", name="send_email")
     */
 
        $mail = new PHPMailer(true);
$secretKeyGoogle=$_ENV['SECRET_KEY_GOOGLE'];
$secretEmailGoogle=$_ENV['SECRET_EMAIL_GOOGLE'];
        try {
            //Configuration de PHPMailer
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = $secretEmailGoogle;
            $mail->Password = $secretKeyGoogle;

            //Destinataires
            $mail->setFrom($secretEmailGoogle);
            $mail->addAddress('regnier.sylvain@yahoo.fr');

            //Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'test';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return new Response('Message has been sent');
        } catch (Exception $e) {
            return new Response("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    


        // return $this->render('contact/index.html.twig', [
        //     'controller_name' => 'ContactController',
           
        // ]);
    }
}
