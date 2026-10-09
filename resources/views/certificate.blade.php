<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan - {{ $course->title }} - {{ $user->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;800&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        :root {
            --primary: #059669;
            --primary-dark: #064e3b;
            --gold: #d97706;
            --gold-light: #fef3c7;
            --navy: #0f172a;
            --slate: #475569;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0b0f19;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Top Action Bar (hidden when printing) */
        .action-bar {
            width: 100%;
            max-width: 960px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            color: #e2e8f0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.15s ease;
        }

        .back-link:hover {
            color: #ffffff;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.625rem 1.25rem;
            border-radius: 9999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        }

        /* Certificate Container */
        .cert-container {
            width: 100%;
            max-width: 960px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            padding: 24px;
        }

        /* Double Border Ornament */
        .cert-border-outer {
            border: 2px solid #d4af37;
            padding: 10px;
            border-radius: 8px;
            position: relative;
            background: #fafafa;
        }

        .cert-border-inner {
            border: 1px solid #e2e8f0;
            background: #ffffff;
            border-radius: 6px;
            padding: 3.5rem 3rem;
            position: relative;
            text-align: center;
            background-image: radial-gradient(circle at 50% 50%, rgba(248, 250, 252, 0.8) 0%, rgba(255, 255, 255, 1) 100%);
        }

        /* Watermark Background */
        .cert-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 26rem;
            color: rgba(5, 150, 105, 0.025);
            user-select: none;
            pointer-events: none;
            font-variation-settings: 'FILL' 1;
        }

        /* Corner Ornaments */
        .corner {
            position: absolute;
            width: 32px;
            height: 32px;
            border-color: #d4af37;
            border-style: solid;
        }
        .corner-tl { top: 12px; left: 12px; border-width: 3px 0 0 3px; }
        .corner-tr { top: 12px; right: 12px; border-width: 3px 3px 0 0; }
        .corner-bl { bottom: 12px; left: 12px; border-width: 0 0 3px 3px; }
        .corner-br { bottom: 12px; right: 12px; border-width: 0 3px 3px 0; }

        /* Typography */
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .cert-logo-icon {
            color: #059669;
            font-size: 2.25rem;
            font-variation-settings: 'FILL' 1;
        }

        .cert-institution {
            font-family: 'Cinzel', serif;
            font-size: 1rem;
            letter-spacing: 0.25em;
            color: #475569;
            text-transform: uppercase;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: #0f172a;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .cert-subtitle {
            font-size: 0.95rem;
            letter-spacing: 0.15em;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2.25rem;
        }

        .cert-recipient-label {
            font-size: 0.9rem;
            color: #64748b;
            font-style: italic;
            margin-bottom: 0.75rem;
        }

        .cert-recipient-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 2.75rem;
            font-weight: 700;
            color: #064e3b;
            margin-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding: 0 2rem 0.5rem 2rem;
        }

        .cert-school-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            margin-top: 0.5rem;
            margin-bottom: 1.75rem;
            border: 1px solid #e2e8f0;
        }

        .cert-body-text {
            max-width: 650px;
            margin: 0 auto 1.5rem auto;
            color: #475569;
            font-size: 1rem;
            line-height: 1.6;
        }

        .cert-course-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.25rem;
            display: block;
            margin-top: 0.25rem;
        }

        /* Footer & Signatures */
        .cert-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 3.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed #cbd5e1;
        }

        .sig-block {
            text-align: center;
            width: 220px;
        }

        .sig-line {
            border-bottom: 1px solid #94a3b8;
            height: 40px;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .sig-image-text {
            font-family: 'Playfair Display', cursive;
            font-size: 1.35rem;
            color: #1e3a8a;
            font-style: italic;
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
        }

        .sig-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: #0f172a;
        }

        .sig-title {
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Gold Medal Badge */
        .cert-seal {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: radial-gradient(circle, #fde68a 0%, #d97706 70%, #92400e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.35);
            border: 3px double #ffffff;
            color: #ffffff;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
            margin: 0 auto;
        }

        .cert-seal .material-symbols-outlined {
            font-size: 2rem;
            font-variation-settings: 'FILL' 1;
        }

        .cert-seal-text {
            font-size: 0.55rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* Verification Bar */
        .cert-verification {
            margin-top: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .cert-code {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 600;
            color: #059669;
            background: #ecfdf5;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            border: 1px solid #a7f3d0;
        }

        /* Print Media Styles */
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .action-bar {
                display: none !important;
            }

            .cert-container {
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                page-break-inside: avoid;
            }

            .cert-border-outer {
                border: 2px solid #b45309 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar -->
    <div class="action-bar">
        <a href="{{ route('student.classroom', $course->slug) }}" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali ke Ruang Belajar
        </a>
        <button onclick="window.print()" class="btn-print">
            <span class="material-symbols-outlined">print</span>
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- Certificate Card -->
    <div class="cert-container">
        <div class="cert-border-outer">
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <div class="cert-border-inner">
                <span class="material-symbols-outlined cert-watermark">workspace_premium</span>

                <!-- Header -->
                <div class="cert-header">
                    <span class="material-symbols-outlined cert-logo-icon">school</span>
                    <span class="cert-institution">EduTube Learning Management System</span>
                </div>

                <h1 class="cert-title">Sertifikat Kelulusan</h1>
                <p class="cert-subtitle">Certificate of Academic Completion</p>

                <!-- Recipient -->
                <p class="cert-recipient-label">Diberikan dengan hormat dan apresiasi kepada:</p>
                <div class="cert-recipient-name">{{ $user->name }}</div>
                
                <div>
                    <span class="cert-school-badge">
                        <span class="material-symbols-outlined" style="font-size: 16px;">apartment</span>
                        {{ $user->school_name ?: ($course->school_name ?: 'EduTube Academy') }}
                    </span>
                </div>

                <!-- Body -->
                <div class="cert-body-text">
                    Telah menyelesaikan secara tuntas seluruh modul kurikulum, materi pembelajaran video, dan evaluasi kuis pada kursus:
                    <span class="cert-course-title">{{ $course->title }}</span>
                </div>

                <!-- Footer & Seal -->
                <div class="cert-footer">
                    <div class="sig-block">
                        <div class="sig-line">
                            <span class="sig-image-text">{{ $course->instructor_name ?: 'Instruktur Utama' }}</span>
                        </div>
                        <div class="sig-name">{{ $course->instructor_name ?: 'Instruktur Kursus' }}</div>
                        <div class="sig-title">{{ $course->instructor_title ?: 'Pengajar Profesional' }}</div>
                    </div>

                    <div class="cert-seal">
                        <span class="material-symbols-outlined">verified</span>
                        <span class="cert-seal-text">Resmi</span>
                    </div>

                    <div class="sig-block">
                        <div class="sig-line">
                            <span class="sig-image-text">Dr. Vance</span>
                        </div>
                        <div class="sig-name">Alex Vance, PhD</div>
                        <div class="sig-title">Direktur Akademik EduTube</div>
                    </div>
                </div>

                <!-- Verification -->
                <div class="cert-verification">
                    <div>
                        Tanggal Terbit: <strong>{{ $certificate->issued_at ? $certificate->issued_at->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</strong>
                    </div>
                    <div>
                        Kode Verifikasi: <span class="cert-code">{{ $certificate->certificate_code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
