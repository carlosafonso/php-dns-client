<?php

declare(strict_types=1);

namespace Afonso\Dns;

class Client
{
    /**
     * @var \Afonso\Dns\SerializerInterface
     */
    protected $serializer;

    public function __construct(SerializerInterface $serializer = null)
    {
        if ($serializer === null) {
            $serializer = new Serializer();
        }
        $this->serializer = $serializer;
    }

    /**
     * Queries the specified name server with data from the given request.
     *
     * @param \Afonso\Dns\Request $request
     * @param string $nameserver The IP address of the resolver
     * @return \Afonso\Dns\Response
     */
    public function query(Request $request, string $nameserver): Response
    {
        $serialized = $this->serializer->serializeRequest($request);

        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_sendto($socket, $serialized, strlen($serialized), 0, $nameserver, 53);
        $assembled = '';
        $received = socket_recv($socket, $assembled, 2048, MSG_WAITALL);
        socket_close($socket);

        return $this->serializer->deserializeResponse($assembled);
    }
}
