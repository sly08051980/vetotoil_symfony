<?php
namespace App\Controller;

use App\Form\ContactType;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            
           
            $mail = new PHPMailer(true);
            $secretKeyGoogle = $_ENV['SECRET_KEY_GOOGLE'];
            $secretEmailGoogle = $_ENV['SECRET_EMAIL_GOOGLE'];

            try {
                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = $secretEmailGoogle;
                $mail->Password = $secretKeyGoogle;

                
                $mail->setFrom($secretEmailGoogle);
                $mail->addAddress('regnier.sylvain@yahoo.fr');

                $mail->isHTML(true);
                $mail->Subject = $formData['sujet']; 
                $mail->Body    = 'Email: ' . $formData['email'] . '<br>Nom: ' . $formData['nom'] . '<br>Prénom: ' . $formData['prenom'] . '<br>Téléphone: ' . $formData['telephone'] . '<br>Message: ' . $formData['message'];
                $mail->AltBody = strip_tags($mail->Body);

                $mail->send();

                
                $this->addFlash('info', 'Votre message a été envoyé.');
return $this->redirectToRoute('app_home');
            } catch (Exception $e) {
                $this->addFlash('error', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
