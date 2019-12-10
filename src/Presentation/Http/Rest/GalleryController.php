<?php

namespace App\Presentation\Http\Rest;

use App\Application\Gallery\Query\ReadModel\Entity\AssetCollection;
use App\Application\Gallery\Query\ReadModel\Entity\GalleryCollection;
use App\Application\Gallery\Seek\AssetSearcher;
use App\Application\Gallery\Seek\GallerySearcher;
use App\Application\Gallery\Service\GalleryQueryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("gallery/")
 */
class GalleryController extends AbstractController
{
    /**
     * @Route("list/{source}", name="api-gallery-list", requirements={"source"=".+"}, methods={"GET"})
     *
     * @param string          $source
     * @param Request         $request
     * @param GallerySearcher $searcher
     *
     * @return JsonResponse
     */
    public function galleries(string $source, Request $request, GallerySearcher $searcher): JsonResponse
    {
        $searcher->setSource($source);
        $searcher->setPage($request->query->getInt('page', 1));
        $searcher->setOrderSort($request->query->get('sort'));
        $searcher->setOrderField($request->query->get('order'));
        $searcher->performSearch();

        /** @var GalleryCollection $collection */
        $collection = $searcher->getCollection();

        $data = ['galleries' => $collection->toArray()];
        $data['searcher'] = ['page' => $searcher->currentPage(), 'limit' => $searcher->defaultLimit()];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("assets/{uuid}", name="api-gallery-assets", requirements={"uuid"=".+"}, methods={"GET"})
     *
     * @param string              $uuid
     * @param Request             $request
     * @param GalleryQueryManager $manager
     * @param AssetSearcher       $searcher
     *
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

        /** @var AssetCollection $collection */
        $collection = $searcher->getCollection();

        $data = ['assets' => $collection->toArray()];
        $data['searcher'] = ['page' => $searcher->currentPage(), 'limit' => $searcher->defaultLimit()];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
