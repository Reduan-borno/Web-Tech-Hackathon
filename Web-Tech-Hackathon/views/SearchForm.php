<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Web Project</title>
    <style>
        /* Simple styling to make it look professional */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #1e293b;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            text-align: center;
            border: 1px solid #334155;
        }
        h1 {
            color: #38bdf8;
        }
        button {
            background-color: #38bdf8;
            color: #0f172a;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: scale(1.05);
            background-color: #7dd3fc;
        }
    </style>
</head>
<body>

    <div class="card">
        <h1>Project Initialized</h1>
        <p>Ready to build something awesome.</p>
        <button onclick="showMessage()">Test Button</button>
    </div>

    <script>
        function showMessage() {
            alert("JavaScript is connected and working!");
        }
    </script>
</body>
</html>