<?php

namespace App\Controller;

use App\Form\Model\UserRegistrationFormModel;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastPhone = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_phone' => $lastPhone, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }


    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        Mailer $mailer
    ): Response {
        $form = $this->createForm(UserRegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UserRegistrationFormModel $userModel */
            $userModel = $form->getData();

            $user = new User();

            $user
                ->setEmail($userModel->email)
                ->setPhone($userModel->phone)
                ->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $userModel->plainPassword
                    )
                )
                ->setRoles(['ROLE_USER']);;

            $entityManager->persist($user);
            $entityManager->flush();

            $mailer->sendWelcomeMail($user, $userModel->plainPassword);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/get_new_password', name: 'app_get_new_password')]
    public function getNewPassword(
        Request $request,
        UserRepository $userRepository,
        Mailer $mailer,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): Response {
        $phone = $request->request->get('phone');
        if ($phone[0] === '+') {
            $phone = User::clearPhone($phone);
        }

        $email = $request->request->get('email');

        if (empty($phone) && empty($email)) {
            return new RedirectResponse($urlGenerator->generate('app_main'));
        }
        $user = $userRepository->findOneBy(['phone' => $phone]);
        if (!isset($user)) {
            $user = $userRepository->findOneBy(['email' => $email]);
        }

        if (isset($user)) {
            $password = str_replace(['+', '-', '_', '=', '|'], '', base64_encode(random_bytes(15)));
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            $entityManager->persist($user);
            $entityManager->flush();

            $mailer->sendNewPassword($user, $password);
        }

        return $this->render('security/newpassword.html.twig');
    }
}
