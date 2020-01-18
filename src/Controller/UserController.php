<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\TemporaryFileManager;
use App\Service\UploadedFileManager;

class UserController extends AbstractController
{
    /**
     * @Route("/member/profile-details", name="profil_show")
     */
    public function show()
    {
        return $this->render('user/profile.html.twig', [
      
        ]);
    }

    /**
     * @Route("/member/profile/{id}", name="profil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, TemporaryFileManager $temporaryStorage, UploadedFileManager $uploadedFile)
    {
        if (null !== $user->getPicture()) {
            $temporaryStorage->setTempImg($user->getPicture());
            $user->setPicture($temporaryStorage->getTempImg());
        }


        $form = $this->createForm(ProfileType::class, $user);

        if ($request->isMethod('post')) {
            $storedImage = $temporaryStorage->getTempImg();
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('photo')->getData() == null && isset($storedImage)) {
                $file2Save = $uploadedFile->docsInputManager($storedImage);
                $user->setPicture($file2Save);
            } else {
                $attachements = [];
                $attachements[] = $form->get('photo')->getData();

                $file2Save = $uploadedFile->docsInputManager($attachements);

                $user->setPicture($file2Save);
            }



            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a été mis à jour.');
            return $this->redirectToRoute('profil_show');
        }

        return $this->render('user/profile_edit.html.twig', [
          'user' => $user,
          'form' => $form->createView(),
    
      ]);
    }
}
