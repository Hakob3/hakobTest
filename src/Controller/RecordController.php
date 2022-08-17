<?php

namespace App\Controller;

use App\Entity\Record;
use App\Form\Type\RecordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecordController extends AbstractController
{

    #[Route('/new_record', name: 'task_success')]
    public function new(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator, FileUploader $fileUploader): Response
    {
        $record = new Record();
        $record->setText('Write a blog post');
        $record->setEmail('myemail@example.com');

        $form = $this->createForm(RecordType::class, $record);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $recordData = $form->getData();

            $images = $form->get('images')->getData();

            $record->setText($recordData->getText());
            $record->setEmail($recordData->getEmail());
            $record->setColor($recordData->getColor());
            $record->setGeometry($recordData->getGeometry());
            foreach ($images as $image) {
                $newFilename = $fileUploader->upload($image);
                $record->setImages($newFilename);
            }
            $errors = $validator->validate($record);
            if (count($errors) > 0) {
                return new Response((string)$errors, 400);
            }
            $entityManager = $doctrine->getManager();

            $entityManager->persist($record);
            $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->renderForm('record/new.html.twig', [
            'form' => $form,
        ]);
    }

//    #[Route('/task_success', name: 'task_success')]
//    public function task_success(Request $request, ManagerRegistry $doctrine): Response
//    {
//       return $this->new($request, $doctrine);
//    }
}