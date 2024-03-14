<?php

class AuthHelper {
    function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function getAuthHeaders() {
        $header = "";
        if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            $header = $_SERVER["HTTP_AUTHORIZATION"];
        }
        if (isset($_SERVER["REDIRECT_HTTP_AUTHORIZATION"])) {
            $header = $_SERVER["REDIRECT_HTTP_AUTHORIZATION"];
        }
        return $header;
    }

    public function createToken($payload) {
        $header = array(
            'alg' => 'HS256',
            'typ' => 'JWT'
        );
        $payload->exp = time() + JWT_EXP;

        $header = $this->base64url_encode(json_encode($header));
        $payload = $this->base64url_encode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $signature = $this->base64url_encode($signature);

        $token = "$header.$payload.$signature";
        return $token;
    }

    public function verifyToken($token) {
        $token = explode(".", $token);

        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        $newSignature = hash_hmac('SHA256', "$header.$payload", JWT_KEY, true);
        $newSignature = $this->base64url_encode($newSignature);

        if ($signature != $newSignature) {
            return false;
        }

        $payload = json_decode(base64_decode($payload));

        if ($payload->exp < time()) {
            return false;
        }

        return $payload;
    }

    public function verifyRequest() {
        $bearer = $this->getAuthHeaders();
        $bearer = explode(" ", $bearer);

        if ($bearer[0] != "Bearer") {
            return false;
        }

        return $this->verifyToken($bearer[1]);
    }
}