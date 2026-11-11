<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Atur Ulang Kata Sandi - Apotek Sapphire Farma</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #334155; background-color: #f8fafc; margin: 0; padding: 40px 20px;">
    
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
        <tr>
            <td style="padding: 40px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                    <tr>
                        <td>
                            <span style="font-size: 22px; font-weight: 700; color: #2563eb; letter-spacing: -0.5px;">
                                Sapphire Farma
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
                    Owner Apotek Sapphire Farma telah mengirimkan tautan untuk melakukan **pengaturan ulang (reset) kata sandi** akun Anda.
                </p>
                
                <p style="font-size: 15px; color: #334155; margin-bottom: 30px;">
                    Silakan klik tombol di bawah ini untuk mengaktifkan formulir rahasia dan membuat kata sandi (*password*) baru Anda secara mandiri.
                </p>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 30px;">
                    <tr>
                        <td align="center">
                            <a href="{{ $urlReset }}" 
                               target="_blank" 
                               style="display: inline-block; padding: 14px 28px; background-color: #2563eb; color: #ffffff; font-weight: 600; font-size: 15px; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">
                                Atur Ulang Kata Sandi
                            </a>
                        </td>
                    </tr>
                </table>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; margin-bottom: 30px;">
                    <tr>
                        <td style="padding: 16px; font-size: 13px; color: #64748b; line-height: 1.5;">
                            <strong>Catatan Keamanan:</strong> Tautan ini hanya berlaku selama **60 menit** sejak email ini dikirimkan. Jangan membagikan isi email ini kepada siapapun demi menjaga keamanan data rekam medis apotek.
                        </td>
                    </tr>
                </table>

                <p style="font-size: 14px; color: #64748b; margin-bottom: 0; text-align: center;">
                    Jika Anda tidak merasa meminta reset password ini, Anda dapat mengabaikan pesan ini dengan aman. Kata sandi lama Anda tidak akan berubah.
                </p>

                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-top: 30px; margin-bottom: 20px;">

                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="font-size: 12px; color: #94a3b8; text-align: center;">
                            Email keamananan ini dikirim otomatis oleh internal &copy; {{ date('Y') }} Apotek Sapphire Farma.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>