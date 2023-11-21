<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Cache\FilesystemCache;

class VideoGameDataCachedrawGApiStore extends AbstractController implements SearchGamesInterface
{
    public $apiRawg;
    public function __construct(public HttpClientInterface $client, ParameterBagInterface $parameterBag,
        VideoGameService $rawgApiClientDataStore,
        CacheInterface $cache
    )
    {
        $this->apiRawg = $parameterBag->get('API_RAWG');

    }

    public function searchByName(string $name): array{
        $datas = $this->rawgApiClientDataStore->searchByName($name);
    }

    public function searchById(int $id): array{
        $datas = $this->rawgApiClientDataStore->searchById($id);
    }

    public function saveInCache(){

    }
}
