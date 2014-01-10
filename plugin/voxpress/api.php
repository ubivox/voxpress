<?php

class UbivoxAPIException extends Exception { }
class UbivoxAPIUnavailable extends UbivoxAPIException { }
class UbivoxAPIError extends UbivoxAPIException { }
class UbivoxAPIUnauthorized extends UbivoxAPIException { }
class UbivoxAPIConnectionError extends UbivoxAPIException { }
class UbivoxAPINotFound extends UbivoxAPIException { }

class UbivoxAPI {

    function __construct($autoload=false) {

        $this->methods = array();

        if ($autoload) {
            $resp = $this->call("system.listMethods", null);
            $this->methods = $resp;
        }
    }

    public function call($method, $params = null) {

        $auth = get_option("uvx_api_username").":".
            get_option("uvx_api_password");

        if ($params == null) {
            $params = array();
        }

        $request = new UX_IXR_Request($method, $params);
        $post = $request->xml;

        $c = curl_init(get_option("uvx_api_url"));

        curl_setopt($c, CURLOPT_USERAGENT, "Voxpress ". VOXPRESS_VERSION ." (running on ".site_url().")");
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($c, CURLOPT_USERPWD, $auth);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_POSTFIELDS, $post);
        curl_setopt($c, CURLOPT_HEADER, true);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 5);

        $http_response = curl_exec($c);

        $info = curl_getinfo($c);

        list($header, $data) = explode("\r\n\r\n", $http_response, 2);

        if ($info["http_code"] == 200) {

            $message = new UX_IXR_Message($data);
            $message->parse();

            if ($message->messageType == "fault") {
                throw new UbivoxAPIError($message->faultString, $message->faultCode);
            }

            return $message->params[0];

        }

        if ($info["http_code"] == 401) {
            throw new UbivoxAPIUnauthorized();
        }

        if ($info["http_code"] == 503) {
            throw new UbivoxAPIUnavailable();
        }

        if ($info["http_code"] == 404) {
            throw new UbivoxAPINotFound();
        }

        if ($info["http_code"] == 0) {
            throw new UbivoxAPIConnectionError();
        }

        throw new UbivoxAPIException($header);

    }
}
