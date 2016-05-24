<?php
/**
 * Created by PhpStorm.
 * User: Kevin
 * Date: 15-4-2
 * Time: 下午2:05
 */



function httpGet($url, $params = '', $method = 'get', $format = 0)
{
    $curlInt = curl_init();
    switch (strtolower($method)) {
        case 'get':
            $url .= $params ? '?' . http_build_query($params) : '';
            break;
        case 'post':
            curl_setopt($curlInt, CURLOPT_POST, 1);
            curl_setopt($curlInt, CURLOPT_POSTFIELDS, json_encode($params));
            break;
        case 'delete':
            curl_setopt($curlInt, CURLOPT_POST, 1);
            curl_setopt($curlInt, CURLOPT_POSTFIELDS, http_build_query($params));
            break;
        case 'route':
            $url .= '/' . http_build_route($params);
            break;
        case 'wx':
            curl_setopt($curlInt, CURLOPT_POST, 1);
            curl_setopt($curlInt, CURLOPT_POSTFIELDS, $params);
            break;
    }
    curl_setopt($curlInt, CURLOPT_URL, $url);
    curl_setopt($curlInt, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlInt, CURLOPT_BINARYTRANSFER, 1);
    $httpHeader = array('Content-Type:application/json;charset=utf-8');
    curl_setopt($curlInt, CURLOPT_HTTPHEADER, $httpHeader);
    $getInfo = curl_getinfo($curlInt);
    $content = curl_exec($curlInt);
    return $content;
}