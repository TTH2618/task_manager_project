<?php
function remove_query_param($url, $param) {
    $parsed_url = parse_url($url);
    parse_str($parsed_url['query'] ?? '', $query_params);
    unset($query_params[$param]);
    $new_query = http_build_query($query_params);

    $result =
        (isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '') .
        (isset($parsed_url['host']) ? $parsed_url['host'] : '') .
        (isset($parsed_url['path']) ? $parsed_url['path'] : '') .
        ($new_query ? '?' . $new_query : '');

    return $result;
}
?>