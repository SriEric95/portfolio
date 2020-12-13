<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;

class PortfolioController extends AbstractController
{
    /**
     * @Route("/portfolio", name="portfolio")
     */
    public function index(): Response
    {
        return $this->render('portfolio/index.html.twig', [
            'controller_name' => 'PortfolioController',
        ]);
    }

    /**
     *  @Route("/", name="home")
     */
    public function home(Request $request, \Swift_Mailer $mailer){

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();


            //envoi de mail
            $message = (new \Swift_Message('Nouveau Contact'))
                
                //Attribuer l'expediteur
                ->setFrom($contact['email'])

                //On attribue le destinataire
                ->setTo('pouroucheric@gmail.com')

                //On crée le message avec la vue Twig
                ->setBody(
                    $this->renderView(
                        'emails/contact.html.twig', compact('contact')

                    ),
                    'text/html'
                )
            ;
            //On envoie le message
            $mailer->send($message);

            $this->addFlash('message', 'Le message a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('portfolio/home.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
