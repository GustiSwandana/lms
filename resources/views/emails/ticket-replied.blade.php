<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanggapan Tiket Dukungan EduTube LMS</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 24px; color: #1e293b;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Header -->
        <div style="background-color: #00236f; padding: 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.025em;">EduTube LMS - Pusat Layanan</h1>
            <p style="margin: 4px 0 0 0; font-size: 13px; opacity: 0.85;">Pemberitahuan Tanggapan Tiket Bantuan</p>
        </div>

        <!-- Body -->
        <div style="padding: 24px;">
            <p style="font-size: 14px; line-height: 1.6; margin-top: 0;">Halo Rekan Operator <strong>{{ $schoolName }}</strong>,</p>
            <p style="font-size: 14px; line-height: 1.6;">Admin Pusat EduTube telah memberikan tanggapan resmi atas tiket kendala/dukungan yang Anda ajukan.</p>

            <!-- Ticket Info Card -->
            <div style="background-color: #f1f5f9; border-radius: 8px; padding: 16px; margin: 20px 0; border-left: 4px solid #00236f;">
                <table style="width: 100%; font-size: 13px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 4px 0; color: #64748b; width: 120px;">Nomor Tiket:</td>
                        <td style="padding: 4px 0; font-weight: 600; font-family: monospace;">#{{ $ticketCode }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Subjek:</td>
                        <td style="padding: 4px 0; font-weight: 600;">{{ $subject }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 4px 0; color: #64748b;">Status Terkini:</td>
                        <td style="padding: 4px 0;">
                            <span style="display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; background-color: {{ $status === 'selesai' ? '#dcfce7' : '#dbeafe' }}; color: {{ $status === 'selesai' ? '#166534' : '#1e40af' }};">
                                {{ strtoupper($status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Response Message -->
            <div style="margin: 20px 0;">
                <h3 style="font-size: 13px; font-weight: 700; color: #00236f; text-transform: uppercase; margin-bottom: 8px;">Tanggapan dari Admin Pusat:</h3>
                <div style="background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 16px; font-size: 13px; line-height: 1.6; color: #334155; white-space: pre-line;">
{{ $response }}
                </div>
            </div>

            <div style="margin-top: 28px; text-align: center;">
                <a href="{{ route('admin.support.index') }}" 
                   style="display: inline-block; background-color: #00236f; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                    Buka Panel Dukungan EduTube
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 16px; text-align: center; font-size: 11px; color: #94a3b8;">
            <p style="margin: 0;">Email ini dihasilkan otomatis oleh sistem antrean EduTube LMS. Harap tidak membalas langsung ke alamat email ini.</p>
        </div>
    </div>
</body>
</html>
