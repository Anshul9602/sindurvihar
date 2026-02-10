<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getJWT($email)
{
    $key = getenv('JWT_SECRET');
    $payload = [
        'iss' => "example.com",  // Issuer
        'aud' => "example.com",  // Audience
        'iat' => time(),         // Issued at
        'nbf' => time(),         // Not before
        'exp' => time() + 3600,  // Expiration time
        'data' => [
            'email' => $email,
        ]
    ];

    return JWT::encode($payload, $key, 'HS256');
}

function validateJWT($token)
{
    $key = getenv('JWT_SECRET');
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return (array) $decoded->data;
    } catch (Exception $e) {
        return false;
    }
}
