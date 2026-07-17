<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contest Scoreboard PDF</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            margin: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            margin-bottom: 30px;
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 8px 0;
        }
        .date {
            font-size: 14px;
            color: #64748b;
            margin: 0;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            text-align: left;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-center {
            text-align: center;
        }
        .font-mono {
            font-family: monospace;
        }
        .rank-badge {
            font-weight: bold;
        }
        .solved-count {
            font-weight: bold;
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">{{ $contest['title'] }}</h1>
        <p class="date">Scoreboard Report &middot; {{ $contest['date'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">Rank</th>
                <th style="width: 50%;">Username</th>
                <th class="text-center" style="width: 20%;">Solved</th>
                <th class="text-center" style="width: 20%;">Penalty</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td class="text-center rank-badge">{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $row['name'] }}</strong>
                        <span style="font-size: 11px; color: #64748b; display: block;">{{ '@' . $row['username'] }}</span>
                    </td>
                    <td class="text-center solved-count font-mono">{{ $row['solve_count'] }}</td>
                    <td class="text-center font-mono">{{ $row['total_penalty'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color: #64748b; padding: 20px;">
                        No participants solved any problems in this contest.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
