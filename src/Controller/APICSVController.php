<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Entity\VideoGame;
use App\Form\SearchType;
use App\Repository\PlatformRepository;
use App\Repository\UserRepository;
use App\Repository\VideoGameRepository;
use App\Service\VideoGameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

#[Route('//video-game')]
#[IsGranted('ROLE_USER')]
class APICSVController extends AbstractController
{
    
    #[Route('/api/hello', name: 'api_hello')]
    public function returnHello(){
        $tabHello = ['Hello' => 'world'];
        return new JsonResponse($tabHello);
    }

    #[Route('/api/game', name: 'api_gameJson')]
    public function returnGame(VideoGameRepository $videoGameRepository){
        $games = $videoGameRepository->findAll();
        foreach($games as $game){
            $gamesTab[$game->getId()] = $game->getName();
        }

        // dd(json_encode($gamesTab));
        return new JsonResponse($gamesTab);
    }

    // #[Route('/api/convertcsv', name: 'api_convertCSV')]
    // public function convertCSVToJSON(){
    //     $file = '../var/CSV/games.csv';
    //     $csv= file_get_contents($file);
    //     $array = array_map("str_getcsv", explode("\n", $csv));
    //     $json = json_encode($array);
    //     dd($json);
    //     return new JsonResponse($array);
    // }

    public function converterCSV(Request $request, VideoGameRepository $videoGameRepository): Response{
        $file = '../var/CSV/games.csv';
        $csv = file_get_contents($file);
        $final = [];
        $array = array_map("str_getcsv", explode("\n", $csv));   
        // dd($array);
        foreach($array[0] as $key => $value){
            $nameCol[$key]= "$value";
        }
        foreach($array as $keyInt => $valueGame){
            if($keyInt === 0){
                continue;
            }
            foreach($nameCol as $key => $value){
                if(isset($valueGame[$key])){
                    $final[($keyInt-1)][$value] = $valueGame[$key];
                }              
            }
        }
        $routeName = $request->attributes->get('_route');
        switch($routeName){
            case 'api_json' : 
                                // $result = new JsonResponse($final);
                                $result = json_encode($final);
                                dd($result);
                                $format = 'json';
                                break;
            case 'api_gateway' :
                                $games = $videoGameRepository->findAll();
                                $test = array_merge($games, $final);
                                dd($test);
                                $result = json_encode($final);
                                $format = 'json';
                                break;

            case 'api_csv'  :   $result = $final;
                                $format = 'csv';
                                dd($result);
                                break;
            case 'api_xml'  :   $result = new XmlEncoder($final);   
                                $format = 'xml';                           
                                dd($result);
                                break;
            default :
                        echo "je ne connais pas ce format";
        }
        return $this->render('api/index.'.$format.'.twig', [
            'final' => $result
        ]);
    }
}