<?php

declare(strict_types=1);

namespace Afonso\Dns;

interface SerializerInterface
{
    /**
     * Serializes the given request object into a binary string.
     *
     * @param \Afonso\Dns\Request $request
     * @return string
     */
    public function serializeRequest(Request $request): string;

    /**
     * Deserializes the given binary string into a response object.
     *
     * @param string $response
     * @return \Afonso\Dns\Response
     */
    public function deserializeResponse(string $response): Response;
}
