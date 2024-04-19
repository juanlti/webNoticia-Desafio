<?php

namespace App\Http\Controllers;

use App\Http\Controllers\postToArray;
use App\Http\Resources\UserResource;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\View\View;

class ApiExternaNoticias extends Controller
{
    public function index(): View
    {

        $page = LengthAwarePaginator::resolveCurrentPage();//obtengo el numero de pagina actual
        $perPage = 10;
        $responseNew = $this->getNewsFromAPI();
        $names = $this->getNameByRandomUser();
        $articles = $responseNew['articles'];
        //Formatear fechas de publicacion
        $articles = $this->formatArticlesDate($articles);

        //Paginar los articulos
        $total = count($responseNew['articles']);
        $start = ($page - 1) * $perPage;
        $articlesForPage = array_slice($articles, $start, $perPage);
        $paginator = new LengthAwarePaginator($articlesForPage, $total, $perPage, $page);
        //dd($paginator->links());
        // Retornar vista con datos paginados
        return view('index', ['noticias' => $paginator, 'author' => $names]);
    }


    private function formatArticlesDate($articles)
    {
        $articlesNewFormatDate = array_map(function ($article) {
            if ($article['publishedAt']) {
                // Formatear la fecha de publicación en el formato día/mes/año
                $fechaPublicacion = date('d/m/Y', strtotime($article['publishedAt']));
                $article['publishedAt'] = $fechaPublicacion;
            } else {
                // Si no hay fecha de publicación, retornar 'Sin fecha'
                $article['publishedAt'] = 'Sin fecha';
            }
            return $article;
        }, $articles);
        return $articlesNewFormatDate;
    }


    private function getNewsFromAPI()
    {
        $apiKey = '594064b6a7234ea8ac33f260e27bf66f';
        $urlNewApi = "https://newsapi.org/v2/everything?q=tesla&from=2024-03-19&sortBy=publishedAt&apiKey={$apiKey}";
        return Http::get($urlNewApi);
    }

    private function getNameByRandomUser()
    {
        $urlRandomApi = "https://randomuser.me/api/?results=10&inc=es,name";
        $response = Http::get($urlRandomApi); // Realizar la solicitud HTTP

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            // Obtener la respuesta como un array
            $responseData = $response->json();

            // Verificar si hay datos en la respuesta
            if (isset($responseData['results'])) {
                // Obtener los nombres de la respuesta
                $names = array_map(function ($result) {
                    if ($result['name']['first']) {
                        return $result['name']['first'];
                    } else {
                        return 'Autor sin identificar';
                    }
                }, $responseData['results']);

                // Convertir los nombres en una colección
                $namesCollection = collect($names);
                //  dd($namesCollection);

                // Devolver la colección de nombres
                return $namesCollection;
            }
        }
        // Si algo sale mal, colecion vacia
        return collect([]);
    }


}

