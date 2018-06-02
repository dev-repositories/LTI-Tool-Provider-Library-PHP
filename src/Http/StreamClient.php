<?php

namespace IMSGlobal\LTI\Http;

use IMSGlobal\LTI\HTTPMessage;

class StreamClient implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function send(HTTPMessage $message)
    {
        $opts = [
            'method' => $message->method,
            'content' => $message->request,
        ];
        if (!empty($message->requestHeaders)) {
            $opts['header'] = $message->requestHeaders;
        }
        try {
            $ctx = stream_context_create(['http' => $opts]);
            $fp = @fopen($message->url, 'rb', false, $ctx);
            if ($fp) {
                $resp = @stream_get_contents($fp);
                return $resp !== false;
            }
        } catch (\Exception $e){
            return false;
        }
        return false;
    }
}
