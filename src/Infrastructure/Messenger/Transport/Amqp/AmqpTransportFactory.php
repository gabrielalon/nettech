<?php

namespace App\Infrastructure\Messenger\Transport\Amqp;

use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\Serialization\Serializer;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class AmqpTransportFactory implements TransportFactoryInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function createTransport(string $dsn, array $options): TransportInterface
    {
        $connection = Connection::fromDsn($dsn, $options);

        $encoders = [new JsonEncoder()];
        $normalizers = [
            new CustomNormalizer(),
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(null, null, null, new ReflectionExtractor()),
        ];
        $symfonySerializer = new SymfonySerializer($normalizers, $encoders);
        $serializer = new Serializer($symfonySerializer);

        return new AmqpTransport(
            $connection,
            $serializer,
            new AmqpReceiver($connection, $serializer, $this->logger),
            new AmqpSender($connection, $serializer, $this->logger)
        );
    }

    public function supports(string $dsn, array $options): bool
    {
        return 0 === strpos($dsn, 'amqp://');
    }
}
