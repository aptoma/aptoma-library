<?php
/**
 * Utility class for URL related stuff
 *
 * @author martin
 *
 */
class Aptoma_Http_Url
{
    /**
     * Create a parameterized string of a set of key values.
     *
     * @param  array  $params
     * @return string
     */
    public static function serializeParams(array $params)
    {
        return implode(
            '&',
            array_map(array('Aptoma_Http_Url', 'encodeKeyValuePair'), array_keys($params), array_values($params))
        );
    }

    /**
     * Url encode key and value and concat them with =
     *
     * @param  string $key
     * @param  string $val
     * @return string
     */
    public static function encodeKeyValuePair($key, $val)
    {
        return urlencode($key) . '=' . urlencode($val);
    }

    /**
     *
     * Appends a set of parameters to an url.
     * The parameters will be urlencoded.
     *
     * @param  string $url
     * @param  array  $parameters
     * @return string
     */
    public static function appendParameters($url, array $parameters)
    {
        if (mb_strpos($url, '?') !== false) {
            $url .= "&";
        } else {
            $url .= "?";
        }

        return $url .= self::serializeParams($parameters);
    }
}
