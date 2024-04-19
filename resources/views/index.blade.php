<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- cargo cdn--}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .img-fixed-size{
            max-width: 350px;
            width: 100px;
            height: 100px;
            object-fit: cover; /* Para asegurar que la imagen cubra el contenedor */
        }
    </style>

</head>
<body>

<div class="container">
    <h1>Lista de articulos con fecha {{$noticias[0]['publishedAt']}}</h1>



    <table class="table">
        <thead>
        <tr>
            <th>Titulo</th>
            <th>Autor</th>
            <th>Noticia</th>
            <th>Imagen</th>
        </tr>
        </thead>

        <tbody>
        @foreach($noticias as $index => $noticia)
            <tr>
                <td>{{$noticia['title']}}
                <a href="{{$noticia['url']}}" target="_blank">Fuente</a>
                </td>


                <td>{{$author[$index]??$noticia['author']}}</td>
                <td>{{$noticia['description']}}</td>
                <td><img src="{{$noticia['urlToImage']}}" onerror="this.onerror=null; this.src='https://via.placeholder.com/200/200';" alt="" class="img-fluid img-fixed-size"></td>
            </tr>
        @endforeach
        </tbody>
    </table>


    {{$noticias->links()}};
    {{--
    @dump($author)
    @dump($noticias)
     --}}

</div>


</body>
</html>
