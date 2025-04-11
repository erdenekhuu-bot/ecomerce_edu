<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Item List</h1>

    <table>
        <thead>
            <tr>
                @if (isset($data[0]) && is_array($data[0]))
                    @foreach (array_keys($data[0]) as $key)
                        <th>{{ ucfirst($key) }}</th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    @foreach ($item as $key => $value)
                        <td>
                            @if (is_array($value))
                                {{ json_encode($value) }}
                            @else
                                {{ $value }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
