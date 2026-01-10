<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Resmi - CPNS Nusantara</title>
    <style>
        /* Mengunci ukuran kertas A4 Portrait tanpa margin default */
        @page {
            margin: 0;
            size: A4 portrait;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FDFBF7; /* Beige Premium */
            color: #0F172A; /* Deep Navy */
            text-transform: uppercase;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        /* --- BACKGROUND LAYER --- */
        .container {
            width: 100%;
            height: 100%;
            position: relative;
            background-color: #FDFBF7;
        }

        .bg-pattern {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(#E2E8F0 0.8px, transparent 0.8px);
            background-size: 24px 24px;
            opacity: 0.6;
        }

        .top-accent {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 12px;
            background: linear-gradient(90deg, #4F46E5, #C5A059);
        }

        /* --- CONTENT WRAPPER --- */
        .wrapper {
            position: relative;
            z-index: 10;
            padding: 80px 80px 0 80px; /* Padding samping diperlebar untuk keamanan */
            text-align: center;
        }

        /* Header */
        .header { margin-bottom: 50px; }
        .brand {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 6px;
            color: #0F172A;
        }
        .series {
            font-size: 9px;
            font-weight: 900;
            color: #C5A059;
            letter-spacing: 3px;
            margin-top: 5px;
        }

        /* Title */
        .title-box { margin-bottom: 50px; }
        .main-title {
            font-size: 72px; 
            font-weight: 900;
            letter-spacing: -5px;
            line-height: 0.8;
            margin: 0;
        }
        .sub-title {
            font-size: 13px;
            font-weight: 900;
            color: #4F46E5;
            letter-spacing: 10px;
            margin-top: 25px;
        }

        /* Recipient */
        .recipient-box { margin-bottom: 50px; }
        .label {
            font-size: 11px;
            font-weight: 900;
            color: #94A3B8;
            letter-spacing: 4px;
            margin-bottom: 15px;
        }
        .user-name {
            font-size: 52px;
            font-weight: 900;
            letter-spacing: -2px;
            margin: 0;
            line-height: 1.1;
        }

        /* Description */
        .description {
            font-size: 14px;
            font-weight: 400;
            color: #475569;
            line-height: 1.6;
            margin: 0 auto 40px auto;
            text-transform: none;
            max-width: 500px;
        }

        /* --- SCORE GRID (CENTERED) --- */
        .stats-table {
            margin: 0 auto;
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Mencegah kolom meluber */
        }
        .stat-cell {
            padding: 20px 5px;
            border-bottom: 1px solid #E2E8F0;
            text-align: center;
        }
        .stat-val {
            font-size: 42px;
            font-weight: 900;
            display: block;
            line-height: 1;
        }
        .stat-lbl {
            font-size: 8px;
            font-weight: 900;
            color: #4F46E5;
            letter-spacing: 2px;
            margin-top: 8px;
        }

        /* Akumulasi Center */
        .total-row-cell {
            padding-top: 40px;
            text-align: center;
        }
        .total-val {
            font-size: 56px;
            font-weight: 900;
            color: #C5A059;
            display: block;
            line-height: 1;
        }
        .total-lbl {
            font-size: 11px;
            font-weight: 900;
            color: #C5A059;
            letter-spacing: 4px;
            margin-top: 10px;
        }

        /* Status Badge */
        .badge-box { margin-top: 30px; margin-bottom: 40px; }
        .badge {
            display: inline-block;
            padding: 12px 30px;
            background-color: {{ $exam->is_passed ? '#10B981' : '#0F172A' }};
            color: #ffffff;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 2px;
            border-radius: 4px;
        }

        /* --- FOOTER FIXED (Anti-Terpotong) --- */
        .footer-container {
            position: absolute;
            bottom: 100px; /* Jarak aman dari bawah */
            left: 80px;
            right: 80px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .meta-info {
            font-family: monospace;
            font-size: 9px;
            color: #94A3B8;
            text-align: left;
            text-transform: none;
            line-height: 1.5;
            vertical-align: bottom;
        }
        .signature-box {
            text-align: right;
            vertical-align: bottom;
            padding-right: 5px; /* Margin kecil agar tidak menempel ke garis */
        }
        .sig-name {
            font-size: 14px;
            font-weight: 900;
            color: #0F172A;
            margin-bottom: 4px;
            white-space: nowrap; /* Mencegah teks turun ke bawah */
        }
        .sig-rank {
            font-size: 9px;
            font-weight: 900;
            color: #C5A059;
            letter-spacing: 1px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-accent"></div>
        <div class="bg-pattern"></div>

        <div class="wrapper">
            <div class="header">
                <div class="brand">CPNS NUSANTARA</div>
                <div class="series">REKAMAN RESMI EVALUASI CAT 2026</div>
            </div>

            <div class="title-box">
                <h1 class="main-title">SERTIFIKAT<br>AKADEMIK</h1>
                <div class="sub-title">SISTEM CAT TERPADU</div>
            </div>

            <div class="recipient-box">
                <div class="label">DIBERIKAN KEPADA :</div>
                <h2 class="user-name">{{ $user->name }}</h2>
            </div>

            <p class="description">
                Telah menyelesaikan seluruh rangkaian evaluasi mandiri pada paket simulasi <strong>{{ $tryout->name }}</strong>. 
                Data penilaian telah divalidasi secara sah oleh <strong>CPNS Nusantara</strong>.
            </p>

            <table class="stats-table">
                <tr>
                    <td class="stat-cell" width="33.3%">
                        <span class="stat-val">{{ $exam->score_twk }}</span>
                        <span class="stat-lbl">TWK</span>
                    </td>
                    <td class="stat-cell" width="33.3%">
                        <span class="stat-val">{{ $exam->score_tiu }}</span>
                        <span class="stat-lbl">TIU</span>
                    </td>
                    <td class="stat-cell" width="33.3%">
                        <span class="stat-val">{{ $exam->score_tkp }}</span>
                        <span class="stat-lbl">TKP</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="total-row-cell">
                        <span class="total-val">{{ $exam->total_score }}</span>
                        <span class="total-lbl">AKUMULASI POIN AKHIR</span>
                    </td>
                </tr>
            </table>

            <div class="badge-box">
                <div class="badge">
                    HASIL: {{ $exam->is_passed ? 'LULUS AMBANG BATAS' : 'SELESAI DIEVALUASI' }}
                </div>
            </div>
        </div>

        <div class="footer-container">
            <table class="footer-table">
                <tr>
                    <td class="meta-info" width="55%">
                        TANGGAL TERBIT: {{ $date }}<br>
                        VERIFIKASI: CPNS-N-{{ strtoupper(substr(md5($exam->id), 0, 12)) }}<br>
                        KEAMANAN: TERVALIDASI DIGITAL
                    </td>
                    <td class="signature-box" width="45%">
                        <div class="sig-name">OTORITAS CPNS NUSANTARA</div>
                        <div class="sig-rank">CPNS NUSANTARA SYSTEM</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>