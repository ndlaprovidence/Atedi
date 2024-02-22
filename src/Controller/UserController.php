<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Security\LoginFormAuthentificatorAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

     /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Security $security): Response
    {
        // Récupération de l'utilisateur actuellement authentifié
        $currentUser = $security->getUser();

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encodez le mot de passe avant de le définir
            $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            // Persistez l'utilisateur dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Reconnecter l'utilisateur initialement authentifié
            $this->get('security.token_storage')->setToken(null);

            // Redirigez ici après la création du compte
            // (par exemple, vers une page de confirmation)
            $this->addFlash('success', 'Compte créé avec succès.');

            // Rétablir l'utilisateur connecté précédemment
            if ($currentUser !== null) {
                $token = new UsernamePasswordToken($currentUser, null, 'main', $currentUser->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));
            }

            return $this->redirectToRoute('user_index'); // Redirection vers la page d'accueil ou autre
        }

        return $this->render('user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
