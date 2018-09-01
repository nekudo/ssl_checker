<?php

namespace Nekudo\SslChecker;

/**
 * Class SslCheck
 * @package Nekudo\SslChecker
 */
class SslCheck
{
    /**
     * Defines at which point a "expires soon" label will be shown. By default this label will be shown
     * as soon as certificates lifetime falls below 30 days.
     *
     * @var int SSL_WARNING_LIMIT
     */
    private const SSL_WARNING_LIMIT = 60 * 60 * 24 * 30; // 30 Days

    /**
     * @var array $sites
     */
    protected $sites = [];

    /**
     * @var array $certificateData
     */
    protected $certificateData = [];

    /**
     * Collects certificate data and shows info-table.
     */
    public function __invoke(): void
    {
        $this->collectCertificateData();
        $this->displayCertificateData();
    }

    /**
     * Sets sites/domains of which certificates will be checked.
     *
     * @param array $sites
     */
    public function setSites(array $sites): void
    {
        $this->sites = $sites;
    }

    /**
     * Collects ssl-certificate data for all sites.
     */
    private function collectCertificateData(): void
    {
        foreach ($this->sites as $site) {
            $certData = $this->fetchCertificateData($site);
            $this->certificateData[$site] = $this->extractTemplateData($site, $certData);
        }
    }

    /**
     * Displays the certificate-information results as html table.
     */
    private function displayCertificateData(): void
    {
        ob_start();
        include __DIR__ . '/tmpl_result.php';
        $tmpl = ob_get_clean();
        echo $tmpl;
    }

    /**
     * Fetches certificate data for given site.
     *
     * @param string $site
     * @return array
     */
    private function fetchCertificateData(string $site): array
    {
        $context = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => true,
            ]
        ]);
        $remoteSocket = 'ssl://' . $site . ':443';
        $streamResource = @stream_socket_client($remoteSocket, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        if (empty($streamResource)) {
            return [];
        }
        $context = @stream_context_get_params($streamResource);
        if (empty($context)) {
            return [];
        }
        return openssl_x509_parse($context['options']['ssl']['peer_certificate']);
    }

    /**
     * Extracts and prepares relevant data from ssl-certificate (for display in template).
     *
     * @param string $site
     * @param array $cert
     * @return array
     */
    private function extractTemplateData(string $site, array $cert): array
    {
        return [
            'domain' => $cert['subject']['CN'] ?? $site,
            'issuer' => $cert['issuer']['CN'] ?? '-',
            'valid_from' => isset($cert['validFrom_time_t']) ? gmdate('Y-m-d', $cert['validFrom_time_t']) : '-',
            'valid_to' => isset($cert['validTo_time_t']) ? gmdate('Y-m-d', $cert['validTo_time_t']) : '-',
            'state' => $this->getStateLabel($cert),
        ];
    }

    /**
     * Generates a ok/warning/error label depending on certificate lifetime.
     *
     * @param array $cert
     * @return string
     */
    private function getStateLabel(array $cert): string
    {
        if (!isset($cert['validTo_time_t'])) {
            return 'error';
        }
        $sslTimeLeft = $cert['validTo_time_t'] - time();
        return ($sslTimeLeft < self::SSL_WARNING_LIMIT) ? 'warning' : 'ok';
    }
}
