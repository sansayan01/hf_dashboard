<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Offer Letters - Humanity Foundation</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #eee;
            font-family: 'Arial', sans-serif;
        }

        .no-print-area {
            background: #333;
            padding: 20px;
            text-align: center;
            color: white;
            display: block;
        }

        @media print {
            .no-print-area {
                display: none;
            }

            body {
                background-color: white;
            }
        }

        .letter-container {
            background: white;
            width: 210mm;
            margin: 0 auto;
            page-break-after: always;
            position: relative;
        }

        .letter-container:last-child {
            page-break-after: auto;
        }
    </style>
</head>

<body>
    <div class="no-print-area">
        <h2 style="margin:0 0 10px 0">Print Preview</h2>
        <p style="margin:0 0 20px 0">Total Letters: {{ count($users) }}</p>
        <button onclick="window.print()"
            style="padding: 12px 30px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px;">Click
            to Print All</button>
        <button onclick="window.close()"
            style="margin-left: 10px; padding: 12px 30px; background: #64748b; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 16px;">Cancel</button>
    </div>

    @foreach($users as $user)
        <div class="letter-container">
            @include('users.joining_letter', ['user' => $user])
        </div>
    @endforeach

    <script>
        // Auto-focus on load
        window.onload = function () {
            // Optional: window.print();
        }
    </script>
</body>

</html>