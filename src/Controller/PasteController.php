<?php
declare(strict_types=1);

namespace App\Controller;

use App\Paste\Entity\Paste;
use App\Paste\Form\PastFormType;
use App\Paste\Query\MyPastePageQuery;
use App\Paste\Repository\PasteRepository;
use App\Paste\Service\HashService;
use App\Paste\Service\PanelService;
use App\Paste\Service\PasteLinkCreator;
use App\Paste\Service\UserService;
use App\Share\ObjectValue\Limit;
use App\Share\ObjectValue\Page;
use App\Share\ObjectValue\Range;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

class PasteController extends AbstractController
{
    public function __construct(private PanelService $panelService)
    {
    }

    #[Route('/', name: 'home')]
    public function index(
        Request                $request,
        EntityManagerInterface $entityManager,
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
            'panelPastes' => $this->panelService->getLastPastes(new \DateTimeImmutable())
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
    #[Route('/my-pastes/{page}', name: 'my-pastes', priority: 1)]
    public function myPastes(Request $request, int $page, MyPastePageQuery $query, PasteLinkCreator $linkCreator): Response
    {
        $this->isGranted(['ROLE_USER']);
        $pange = Range::createFromPageAndLimit(
            new Page($page),
            new Limit(10)
        );
        $authUserUuid = UUid::fromString($this->getUser()->getUserIdentifier());
        $pastes = $query->getMyPastes($authUserUuid, $pange, new \DateTimeImmutable());
        foreach ($pastes['data'] as &$paste) {
            $paste['link'] = $linkCreator->getFromHash($paste['hash']);
        }
        return $this->render('paste/my-pastes.html.twig', [
            'pastes' => $pastes['data'],
            'hasMore' => $pastes['hasMore'],
            'panelPastes' => $this->panelService->getLastPastes(new \DateTimeImmutable())
        ]);
    }
}
