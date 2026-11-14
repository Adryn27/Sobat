<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi Pendaftaran Sobat Apotek</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #334155; background-color: #f8fafc; margin: 0; padding: 40px 20px;">
    
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <tr>
            <td style="padding: 40px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                    <tr>
                        <td>
                            <span style="font-size: 22px; font-weight: 700; color: #2563eb; letter-spacing: -0.5px;">
                                Sobat
                            </span>
                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">Sistem Inventaris Apotek</div>
                        </td>
                    </tr>
                </table>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 30px;">

                <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-top: 0; margin-bottom: 16px;">
                    Halo {{ $user->nama }},
                </h2>
                
                <p style="font-size: 15px; color: #334155; margin-bottom: 16px; margin-top: 0;">
                    Terima kasih telah mendaftarkan <strong>{{ $user->tenants->nama_tenant}}</strong> ke dalam sistem <strong>Sobat</strong>.
                </p>
                
                <p style="font-size: 15px; color: #334155; margin-bottom: 30px;">
                    Langkah terakhir, silakan klik tombol di bawah ini untuk memverifikasi alamat email sekaligus mengaktifkan akun apotek Anda secara otomatis.
                </p>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                    <tr>
                        <td align="center">
                            <a href="{{ route('backend.register.verify', $token) }}" 
                               target="_blank" 
                               style="display: inline-block; padding: 14px 28px; background-color: #2563eb; color: #ffffff; font-weight: 600; font-size: 15px; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                                Verifikasi &amp; Aktifkan Akun
                            </a>
                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; margin-bottom: 30px;">
                    <tr>
                        <td style="padding: 16px; font-size: 13px; color: #64748b; line-height: 1.5;">
                            <strong>Catatan Keamanan:</strong> Tautan verifikasi ini bersifat rahasia, hanya berlaku <strong>1 kali</strong>, dan akan kedaluwarsa dalam <strong>10 menit</strong> sejak email ini dikirimkan. Jika melebihi batas waktu, data pendaftaran akan terhapus otomatis.
                        </td>
                    </tr>
                </table>

                <p style="font-size: 14px; color: #64748b; margin-bottom: 0;">
                    Jika Anda merasa tidak pernah melakukan pendaftaran ini, Anda dapat mengabaikan email ini dengan aman.
                </p>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-top: 30px; margin-bottom: 20px;">

                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="font-size: 12px; color: #94a3b8; text-align: center;">
                            Email keamanan ini dikirim otomatis oleh sistem &copy; {{ date('Y') }} Sobat.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>