<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserUpdateFormModel;
use App\Form\UserUpdateFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function account(): Response
    {
        return $this->render('profile/account.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher,
    ): Response {

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserUpdateFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if (!empty($user->getPlainPassword())) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $user->getPlainPassword()
                    )
                );
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash('success_profile_change', 'Профиль успешно сохранен');
        }


        return $this->render('profile/profile.html.twig', [
            'updateForm' => $form->createView(),
        ]);
    }

    #[Route('/historyorder', name: 'app_historyorder')]
    public function historyorder(): Response
    {
        return $this->render('profile/historyorder.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/historyview', name: 'app_historyview')]
    public function historyview(): Response
    {
        return $this->render('profile/historyview.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
