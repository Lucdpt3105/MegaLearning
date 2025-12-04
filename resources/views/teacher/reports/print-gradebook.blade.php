<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng điểm - {{ $classRoom->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            padding: 20mm;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .header .subtitle {
            font-size: 14pt;
            margin-bottom: 5px;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
        }

        .statistics {
            background: #f5f5f5;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            flex: 1;
        }

        .stat-value {
            font-size: 16pt;
            font-weight: bold;
            color: #333;
        }

        .stat-label {
            font-size: 10pt;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #e0e0e0;
            font-weight: bold;
            font-size: 11pt;
        }

        td {
            font-size: 11pt;
        }

        .student-name {
            text-align: left;
            padding-left: 10px;
        }

        .score-excellent {
            background: #d4edda;
            font-weight: bold;
        }

        .score-good {
            background: #d1ecf1;
        }

        .score-average {
            background: #fff3cd;
        }

        .score-poor {
            background: #f8d7da;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            flex: 1;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
        }

        .signature-name {
            font-style: italic;
        }

        @media print {
            body {
                padding: 0;
            }

            @page {
                size: A4 landscape;
                margin: 15mm;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14pt;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        🖨️ In bảng điểm
    </button>

    <div class="header">
        <h1>Bảng điểm học sinh</h1>
        <div class="subtitle">{{ $subject->name }}</div>
        <div class="subtitle">Lớp: {{ $classRoom->name }}</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div><span class="info-label">Giáo viên:</span> {{ $subject->teacher->name }}</div>
            <div><span class="info-label">Học kỳ:</span> {{ $classRoom->semester ?? 'I' }}</div>
        </div>
        <div class="info-row">
            <div><span class="info-label">Môn học:</span> {{ $subject->name }}</div>
            <div><span class="info-label">Ngày in:</span> {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="statistics">
        <div class="stat-item">
            <div class="stat-value">{{ count($students) }}</div>
            <div class="stat-label">Tổng số HS</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($stats['average'], 2) }}</div>
            <div class="stat-label">Điểm TB</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($stats['highest'], 2) }}</div>
            <div class="stat-label">Cao nhất</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($stats['lowest'], 2) }}</div>
            <div class="stat-label">Thấp nhất</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['pass_rate'] }}%</div>
            <div class="stat-label">Tỷ lệ đậu</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">STT</th>
                <th style="width: 200px;">Họ và tên</th>
                @foreach($exams as $exam)
                    <th style="width: 80px;">{{ $exam->title }}</th>
                @endforeach
                <th style="width: 80px;">Điểm TB</th>
                <th style="width: 100px;">Xếp loại</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="student-name">{{ $student->name }}</td>
                @php
                    $totalScore = 0;
                    $examCount = 0;
                @endphp
                @foreach($exams as $exam)
                    @php
                        $submission = $submissions->where('student_id', $student->id)
                                                  ->where('exam_id', $exam->id)
                                                  ->first();
                        $score = $submission ? $submission->score : null;
                        
                        if ($score !== null) {
                            $totalScore += $score;
                            $examCount++;
                        }
                        
                        $cellClass = '';
                        if ($score !== null) {
                            if ($score >= 8) $cellClass = 'score-excellent';
                            elseif ($score >= 6.5) $cellClass = 'score-good';
                            elseif ($score >= 5) $cellClass = 'score-average';
                            else $cellClass = 'score-poor';
                        }
                    @endphp
                    <td class="{{ $cellClass }}">
                        {{ $score !== null ? number_format($score, 2) : '-' }}
                    </td>
                @endforeach
                @php
                    $avgScore = $examCount > 0 ? $totalScore / $examCount : 0;
                    $avgClass = '';
                    if ($avgScore >= 8) $avgClass = 'score-excellent';
                    elseif ($avgScore >= 6.5) $avgClass = 'score-good';
                    elseif ($avgScore >= 5) $avgClass = 'score-average';
                    elseif ($avgScore > 0) $avgClass = 'score-poor';
                @endphp
                <td class="{{ $avgClass }}">
                    <strong>{{ $examCount > 0 ? number_format($avgScore, 2) : '-' }}</strong>
                </td>
                <td>
                    @if($avgScore >= 8)
                        Giỏi
                    @elseif($avgScore >= 6.5)
                        Khá
                    @elseif($avgScore >= 5)
                        Trung bình
                    @elseif($avgScore > 0)
                        Yếu
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-box">
            <div class="signature-title">HIỆU TRƯỞNG</div>
            <div class="signature-name">(Ký và ghi rõ họ tên)</div>
        </div>
        <div class="signature-box">
            <div class="signature-title">GIÁO VIÊN BỘ MÔN</div>
            <div class="signature-name">{{ $subject->teacher->name }}</div>
        </div>
    </div>
</body>
</html>
