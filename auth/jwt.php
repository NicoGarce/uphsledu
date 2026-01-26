<?php
/**
 * Minimal JWT helper (HMAC-SHA256)
 * Provides encode/decode and convenience helpers for cookie/header checks
 */

if (!defined('JWT_SECRET')) {
    if (defined('ENCRYPTION_KEY')) {
        define('JWT_SECRET', ENCRYPTION_KEY);
    } else {
        define('JWT_SECRET', hash('sha256', 'change_this_jwt_secret'));
    }
}

function jwt_base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function jwt_base64url_decode($data) {
    $remainder = strlen($data) % 4;
    if ($remainder) {
        $data .= str_repeat('=', 4 - $remainder);
    }
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwt_encode(array $payload, int $expirySeconds = 3600) : string {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload = $payload;
    $now = time();
    $payload['iat'] = $now;
    $payload['exp'] = $now + $expirySeconds;

    $segments = [];
    $segments[] = jwt_base64url_encode(json_encode($header));
    $segments[] = jwt_base64url_encode(json_encode($payload));

    $signingInput = implode('.', $segments);
    $signature = hash_hmac('sha256', $signingInput, JWT_SECRET, true);
    $segments[] = jwt_base64url_encode($signature);

    return implode('.', $segments);
}

function jwt_decode(string $token) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return false;

    list($b64Header, $b64Payload, $b64Sig) = $parts;

    $header = json_decode(jwt_base64url_decode($b64Header), true);
    $payload = json_decode(jwt_base64url_decode($b64Payload), true);
    $signature = jwt_base64url_decode($b64Sig);

    if (!is_array($payload) || !is_array($header)) return false;

    $expected = hash_hmac('sha256', $b64Header . '.' . $b64Payload, JWT_SECRET, true);
    if (!hash_equals($expected, $signature)) return false;

    if (isset($payload['exp']) && time() > $payload['exp']) return false;

    return $payload;
}

function generate_jwt_for_user(array $user, int $expirySeconds = 3600) : string {
    $payload = [
        'sub' => $user['id'],
        'username' => $user['username'] ?? null,
        'role' => $user['role'] ?? null,
        'first_name' => $user['first_name'] ?? null,
    ];
    return jwt_encode($payload, $expirySeconds);
}

function jwt_from_request() {
    // Check Authorization header first
    $headers = [];
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    }

    if (!empty($headers['Authorization'])) {
        if (preg_match('/Bearer\s+(.*)$/i', $headers['Authorization'], $matches)) {
            return $matches[1];
        }
    }

    // Some servers place the header under HTTP_AUTHORIZATION
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        if (preg_match('/Bearer\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return $matches[1];
        }
    }

    // Fallback to cookie
    if (!empty($_COOKIE['access_token'])) {
        return $_COOKIE['access_token'];
    }

    return null;
}

function verify_jwt_from_request() {
    $token = jwt_from_request();
    if (!$token) return false;
    return jwt_decode($token);
}
