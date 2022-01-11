<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            .underline {
                text-decoration: underline;
            }

            .my-2 {
                margin-top: 0.5rem;
                margin-bottom: 0.5rem;
            }

            td {
                vertical-align: top;
            }
        </style>
    </head>
    <body>
        <table>
            @foreach (collect($selectedCategories)->chunk(2) as $chunk)
                <tr>
                    @foreach ($chunk as $category)
                        @continue($articles->where('category', $category)->isEmpty())
                        <td class="p-4">
                            <h3 class="my-2 text-lg underline">{{ $category }}</h3>
                            <div class="space-y-1">
                                @foreach ($articles->where('category', $category) as $article)
                                    <div>{{ $article['name'] }}</div>
                                @endforeach
                            </div>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </body>
</html>