<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Book Stats</title>
</head>
<body class="p-10">

    <div class="text-5xl font-extrabold ...">
        <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-500 to-violet-500">
        Book Stats
        </span>



    </div>
    <div class="flex">
        <ul>
            @foreach($booksPopular as $book)
                <li class="pt-5">
                    <h3><strong>Title:</strong> {{$book->title}}</h3>
                    <p><strong>Book name:</strong> {{$book->author}}</p>
                    <p><strong>Number of claims:</strong> {{$book->claim_count}}</p>
                </li>
            @endforeach
        </ul>

        <ul>
            @foreach($booksLeastPopular as $book)
                <li class="pt-5">
                    <h3><strong>Title:</strong> {{$book->title}}</h3>
                    <p><strong>Book name:</strong> {{$book->author}}</p>
                    <p><strong>Number of claims:</strong> {{$book->claim_count}}</p>
                </li>
            @endforeach
        </ul>
    </div>

    <h3>Most popular genre:</h3>

    <p>{{$genre}}</p>


</body>
</html>
