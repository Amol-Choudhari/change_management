<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Util\Http;

/**
 * Proxy discovery and helper class
 *
 * @internal
 * @author John Stevenson <john-stevenson@blueyonder.co.uk>
 */
class ProxyHelper
{
    /**
     * Returns proxy environment values
     *
     * @throws \RuntimeException on malformed url
     * @return array             httpProxy, httpsProxy, noProxy values
     */
    public static function getProxyData()
    {
        $httpProxy = null;
        $httpsProxy = null;

        // Handle http_proxy/HTTP_PROXY on CLI only for security reasons
        if (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg') {
            if ($env = self::getProxyEnv(array('http_proxy', 'HTTP_PROXY'), $name)) {
                $httpProxy = self::checkProxy($env, $name);
            }
        }

        // Prefer CGI_HTTP_PROXY if available
        if ($env = self::getProxyEnv(array('CGI_HTTP_PROXY'), $name)) {
            $httpProxy = self::checkProxy($env, $name);
        }

        // Handle https_proxy/HTTPS_PROXY
        if ($env = self::getProxyEnv(array('https_proxy', 'HTTPS_PROXY'), $name)) {
            $httpsProxy = self::checkProxy($env, $name);
        } else {
            $httpsProxy = $httpProxy;
        }

        // Handle no_proxy
        $noProxy = self::getProxyEnv(array('no_proxy', 'NO_PROXY'), $name);

        return array($httpProxy, $httpsProxy, $noProxy);
    }

    /**
     * Returns http context options for the proxy url
     *
     * @param  string $proxyUrl
     * @return array
     */
    public static function getContextOptions($proxyUrl)
    {
        $proxy = parse_url($proxyUrl);

        // Remove any authorization
        $proxyUrl = self::formatParsedUrl($proxy, false);
        $proxyUrl = str_replace(array('http://', 'https://'), array('tcp://', 'ssl://'), $proxyUrl);

        $options['http']['proxy'] = $proxyUrl;

        // Handle any authorization
        if (isset($proxy['user'])) {
            $auth = rawurldecode($proxy['user']);

            if (isset($proxy['pass'])) {
                $auth .= ':' . rawurldecode($proxy['pass']);
            }
            $auth = base64_encode($auth);
            // Set header as a string
            $options['http']['header'] = "Proxy-Authorization: Basic {$auth}";
        }

        return $options;
    }

    /**
     * Sets/unsets request_fulluri value in http context options array
     *
     * @param string $requestUrl
     * @param array  $options    Set by method
     */
    public static function setRequestFullUri($requestUrl, array &$options)
    {
        if ('http' === parse_url($requestUrl, PHP_URL_SCHEME)) {
            $options['http']['request_fulluri'] = true;
        } else {
            unset($options['http']['request_fulluri']);
        }
    }

    /**
     * Searches $_SERVER for case-sensitive values
     *
     * @param  array       $names Names to search for
     * @param  mixed       $name  Name of any found value
     * @return string|null The found value
     */
    private static function getProxyEnv(array $names, &$name)
    {
        foreach ($names as $name) {
            if (!empty($_SERVER[$name])) {
                return $_SERVER[$name];
            }
        }
    }

    /**
     * Checks and formats a proxy url from the environment
     *
     * @param  string            $proxyUrl
     * @param  string            $envName
     * @throws \RuntimeException on malformed url
     * @return string            The formatted proxy url
     */
    private static function checkProxy($proxyUrl, $envName)
    {
        $error = sprintf('malformed %s url', $envName);
        $proxy = parse_url($proxyUrl);

        if (!isset($proxy['host'])) {
            throw new \RuntimeException($error);
        }

        $proxyUrl = self::formatParsedUrl($proxy, true);

        if (!parse_url($proxyUrl, PHP_URL_PORT)) {
            throw new \RuntimeException($error);
        }

        return $proxyUrl;
    }

    /**
     * Formats a url from its component parts
     *
     * @param  array  $proxy       Values from parse_url
     * @param  bool   $includeAuth Whether to include authorization values
     * @return string The formatted value
     */
    private static function formatParsedUrl(array $proxy, $includeAuth)
    {
        $proxyUrl = isset($proxy['scheme']) ? strtolower($proxy['scheme']) . '://' : '';

        if ($includeAuth && isset($proxy['user'])) {
            $proxyUrl .= $proxy['user'];

            if (isset($proxy['pass'])) {
                $proxyUrl .= ':' . $proxy['pass'];
            }
            $proxyUrl .= '@';
        }

        $proxyUrl .= $proxy['host'];

        if (isset($proxy['port'])) {
            $proxyUrl .= ':' . $proxy['port'];
        } elseif (strpos($proxyUrl, 'http://') === 0) {
            $proxyUrl .= ':80';
        } elseif (strpos($proxyUrl, 'https://') === 0) {
            $proxyUrl .= ':443';
        }

        return $proxyUrl;
    }
}
