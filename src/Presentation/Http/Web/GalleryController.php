<?php

namespace App\Presentation\Http\Web;

use App\Application\Gallery\Fetch\FetcherRegistry;
use App\Application\Gallery\Seek\AssetSearcher;
use App\Application\Gallery\Seek\GallerySearcher;
use App\Application\Gallery\Service\GalleryQueryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("gallery/")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("source", name="gallery-source")
     *
     * @param FetcherRegistry $fetcherRegistry
     * @return Response
     */
    public function sources(FetcherRegistry $fetcherRegistry): Response
    {
        return $this->render('gallery/sources.html.twig', [
            'galleryFetchers' => $fetcherRegistry->getFetchers()
        ]);
    }

    /**
     * @Route("list/{source}", name="gallery-list", requirements={"source"=".+"})
     *
     * @param string $source
     * @param Request $request
     * @param GallerySearcher $searcher
     * @return Response
     */
    public function galleries(string $source, Request $request, GallerySearcher $searcher): Response
    {
        $searcher->setSource($source);
        $searcher->setPage($request->query->getInt('page', 1));
        $searcher->setOrderSort($request->query->get('sort'));
        $searcher->setOrderField($request->query->get('order'));
        $searcher->performSearch();

        return $this->render('gallery/list.html.twig', ['searcher' => $searcher]);
    }

    /**
     * @Route("assets/{uuid}", name="gallery-assets", requirements={"uuid"=".+"})
     *
     * @param string $uuid
     * @param Request $request
     * @param GalleryQueryManager $manager
     * @param AssetSearcher $searcher
     * @return Response
     */
    public function assets(string $uuid, Request $request, GalleryQueryManager $manager, AssetSearcher $searcher): Response
    {
        $gallery = $manager->findOneGalleryByUuid($uuid);

        $searcher->setGallery($gallery->identifier());
        $searcher->setPage($request->query->getInt('page', 1));
        $searcher->setOrderSort($request->query->get('sort'));
        $searcher->setOrderField($request->query->get('order'));
        $searcher->performSearch();

        return $this->render('gallery/assets.html.twig', [
            'gallery' => $gallery,
            'searcher' => $searcher
        ]);
    }
}