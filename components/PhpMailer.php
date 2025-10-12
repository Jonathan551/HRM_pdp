<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use PHPMailer\PHPMailer\PHPMailer as PHPMailerLib;
use PHPMailer\PHPMailer\SMTP;

class PhpMailer extends Component
{
    public string $fromEmail = 'no-reply@example.com';
    public array $smtpConfig = [];

    public function send(string $to, string $subject, string $htmlBody, array $attachments = [], ?string $fromName = null): bool
    {
        $c = $this->smtpConfig;
        $m = new PHPMailerLib(true);

        $host       = (string)($c['host'] ?? '');
        $port       = (int)($c['port'] ?? 587);
        $auth       = (bool)($c['auth'] ?? true);
        $username   = (string)($c['username'] ?? '');
        $password   = (string)($c['password'] ?? '');
        $encryption = $c['encryption'] ?? PHPMailerLib::ENCRYPTION_STARTTLS; // STARTTLS/SMTPS/'' (no TLS)
        $timeout    = (int)($c['timeout'] ?? 20);
        $ipv4       = (bool)($c['ipv4'] ?? false);
        $debug      = (bool)($c['debug'] ?? false);
        $autotls    = $c['autotls'] ?? null;
        $authType   = $c['auth_type'] ?? null; // optional

        try {
            $m->isSMTP();
            $m->CharSet   = 'UTF-8';
            $m->Host      = $host;
            $m->Port      = $port;
            $m->Timeout   = $timeout;
            $m->SMTPAuth  = $auth;
            $m->Username  = $username;
            $m->Password  = $password;
            if (is_string($authType) && $authType !== '') {
                $m->AuthType = $authType;
            }

            // TLS/SSL setup
            if ($encryption) {
                $m->SMTPSecure = $encryption;            // STARTTLS / SMTPS
                if (is_bool($autotls)) { $m->SMTPAutoTLS = $autotls; }
            } else {
                $m->SMTPSecure = false;                  // non-TLS
                $m->SMTPAutoTLS = false;
            }

            // Debug ke log Yii
            if ($debug) {
                $m->SMTPDebug   = SMTP::DEBUG_SERVER;
                $m->Debugoutput = static function (string $str, int $level): void {
                    Yii::error("SMTP[$level] $str", __METHOD__);
                };
            }

            // ⚠️ Hindari CN mismatch:
            // - Jika TLS aktif → JANGAN ganti host ke IP.
            // - Jika harus paksa IPv4 saat TLS, set peer_name ke host asli.
            if ($ipv4 && $host && !filter_var($host, FILTER_VALIDATE_IP)) {
                $forceIpv4 = empty($encryption); // true hanya jika non-TLS
                if ($forceIpv4) {
                    $resolved = @gethostbyname($host);   // IPv4
                    if ($resolved && $resolved !== $host) { $m->Host = $resolved; }
                } else {
                    // TLS + IPv4 paksa: tetap connect ke IP, tapi set peer_name agar sertifikat cocok
                    $resolved = @gethostbyname($host);
                    if ($resolved && $resolved !== $host) {
                        $m->Host = $resolved;
                        $m->SMTPOptions['ssl']['peer_name'] = $host; // penting: elak CN mismatch
                    }
                }
            }

            // (Opsional keamanan ketat; jangan disable verify_* di produksi)
            if (!empty($c['allowSelfSigned'])) {
                $m->SMTPOptions['ssl'] = array_merge($m->SMTPOptions['ssl'] ?? [], [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ]);
            }

            $m->setFrom($this->fromEmail, $fromName ?? Yii::$app->name);
            $m->addAddress($to);

            foreach ($attachments as $p) {
                if (is_file($p)) { $m->addAttachment($p); }
                else { Yii::warning("Lampiran tidak ditemukan: {$p}", __METHOD__); }
            }

            $m->isHTML(true);
            $m->Subject = $subject;
            $m->Body    = $htmlBody;

            $ok = $m->send();
            if (!$ok) {
                Yii::error('PHPMailer gagal: ' . $m->ErrorInfo, __METHOD__);
            }
            return $ok;
        } catch (\Throwable $e) {
            Yii::error('PHPMailer exception: ' . $e->getMessage(), __METHOD__);
            throw new Exception('Gagal mengirim email: ' . $e->getMessage(), 0, $e);
        }
    }
}
