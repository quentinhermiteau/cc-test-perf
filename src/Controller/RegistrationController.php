<?php


namespace App\Controller;

use App\Form\UserType;
use App\Form\EmailResetType;
use App\Form\ResetPasswordType;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * handle all requests related:
 *    - user registration
 *    - password recovery
 *    - setting up a new password
 */
class RegistrationController extends AbstractController
{
    /**
     * Action : handle user registration
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            //on active par défaut
            $user->setIsActive(true);
            //$user->addRole("ROLE_ADMIN");
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash('success', 'Votre compte à bien été enregistré.');
            return $this->redirectToRoute('login');
        }
        return $this->render('registration/register.html.twig', ['form' => $form->createView(), 'mainNavRegistration' => true, 'title' => 'Inscription']);
    }

    /**
     * Action : handle password recovery
     * @Route("/forgotten_pass", name="forgotten_password")
     */
    public function forgottenPassword(Request $request, \Swift_Mailer $mailer)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(EmailResetType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


        //$email = $request->request->get('email');
            $user  = $entityManager->getRepository(User::class)->findOneByEmail($form->getData()['email']);


            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('trick_index');
            }
            $token = uniqid();
            try {
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('trick_index');
            }



            $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);


            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('thabti.moez@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Cliquez sur ce lien pour redéfinir un nouveau mot de passe : " . $url,
                    'text/html'
                );
 
            $mailer->send($message);

 
            $this->addFlash('notice', 'Mail envoyé');
 
            return $this->redirectToRoute('trick_index');
        }
        return $this->render('security/forgotten_password.html.twig', array('form' => $form->createView(), 'title' => 'Recupérer mot de passe'));
    }
    /**
     * Action : handle setting up new password
     * @Route("/reset_pass", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $token = $request->query->get('token');
        if ($token !== null) {
            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneByResetToken($token);
            if ($user !== null) {
                $form = $this->createForm(ResetPasswordType::class, $user);
                $form->handleRequest($request);
      
                if ($form->isSubmitted() && $form->isValid()) {
                    $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($encoded);
                    $entityManager->persist($user);
                    $entityManager->flush();
           
                    $this->addFlash('notice', 'Mot de passe mis à jour');
                    return $this->redirectToRoute('login');
                }
           
                return $this->render('security/reset_password.html.twig', array('form' => $form->createView()));
            }
        }

        $this->addFlash('danger', 'Token Inconnu');
        return $this->redirectToRoute('trick_index');
    }
}
