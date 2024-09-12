<?php
declare(strict_types=1);

namespace App\Controller;

use App\Paste\Entity\Paste;
use App\Paste\Form\PastFormType;
use App\Paste\Repository\PasteRepository;
use App\Paste\Service\HashService;
use App\Paste\Service\PanelService;
use App\Paste\Service\PasteLinkCreator;
use App\Paste\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class PasteController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(
        Request                $request,
        EntityManagerInterface $entityManager,
        PanelService $panelService,
        HashService $hashService,
        UserService $userService
    ): Response
    {

        $paste = new Paste(Uuid::v4());
        $form = $this->createForm(PastFormType::class, $paste);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Paste $paste
             */
            $paste = $form->getData();
            $paste->setCreatedAt(new \DateTimeImmutable());
            $paste->setHash($hashService->getHash(8));
            $userService->connect($this->getUser(), $paste);
            $entityManager->persist($paste);
            $entityManager->flush();
            return $this->redirect('/' . $paste->getHash());
        }
        return $this->render('paste/index.html.twig', [
            'form' => $form,
            'panelPastes' => $panelService->getLastPastes(new \DateTimeImmutable())
        ]);
    }

    #[Route('/{pastHash}', name: 'past-page')]
    public function pastPage(Request $request, PasteRepository $repository, string $pastHash, PasteLinkCreator $linkCreator): Response
    {
        $paste = $repository->findOneBy(['hash' => $pastHash]);
        if (is_null($paste) || $paste->isExpired(new \DateTimeImmutable())) {
            return $this->render('not-found.html.twig');
        }
        $link = $linkCreator->getLink($paste);
        return $this->render('paste/paste.html.twig', [
            'paste' => $paste,
            'link' => $link
        ]);
    }
}
