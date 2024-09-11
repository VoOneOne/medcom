<?php
declare(strict_types=1);

namespace App\Controller;

use App\Paste\Entity\Paste;
use App\Paste\Form\PastFormType;
use App\Paste\PasteRepository;
use App\Paste\Service\PasteLinkCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PasteController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paste = new Paste();

        $form = $this->createForm(PastFormType::class, $paste);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Paste $paste
             */
            $paste = $form->getData();
            $entityManager->persist($paste);
            $entityManager->flush();
            return $this->redirect('/' . $paste->getHash());
        }

        return $this->render('paste/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{pastHash}', name: 'past-page')]
    public function pastPage(Request $request, PasteRepository $repository, string $pastHash, PasteLinkCreator $linkCreator): Response
    {
        $paste = $repository->findByHash($pastHash);
        if (is_null($paste)) {
            return $this->render('not-found.html.twig');
        }
        $link = $linkCreator->getLink($paste);
        return $this->render('paste/paste.html.twig', [
            'paste' => $paste,
            'link' => $link
        ]);
    }
}
