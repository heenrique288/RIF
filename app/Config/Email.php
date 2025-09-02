<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'notifica@ifrocalama.com';  // E-mail de remetente
    public string $fromName   = 'Ifro Calama';              // Nome do remetente
    public string $recipients = '';   // Deixe vazio ou coloque destinatário por padrão
    

    /**
     * O protocolo para envio de e-mail (mail, sendmail, smtp)
     */
    public string $protocol = 'smtp';

    /**
     * O caminho do Sendmail. No caso de SMTP não será usado.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'mail.smtp2go.com'; // Servidor SMTP do SMTP2Go

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'notifica@ifrocalama.com'; // Seu e-mail de autenticação

    /**
     * SMTP Password
     */
    public string $SMTPPass; // Senha de autenticação (será carregada do .env)

    /**
     * SMTP Port
     */
    public int $SMTPPort = 8025; // Porta SMTP (você pode usar 2525, 8025, 587, 80 ou 25)

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     * Defina como 'tls' para a comunicação segura.
     */
    public string $SMTPCrypto = 'tls';  // Use 'tls' para criptografia

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html'; // Use 'html' para e-mails com formatação

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        // Carrega a senha do SMTP do arquivo .env
        $this->SMTPPass = env('SMTP_PASSWORD', ''); // O segundo parâmetro é um valor padrão caso a variável não exista
    }
}
