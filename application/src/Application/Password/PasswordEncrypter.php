<?php

declare(strict_types=1);

namespace App\Application\Password;

use App\Domain\ValueObject\User\Password;
use App\Domain\ValueObject\User\PasswordInterface;
use InvalidArgumentException;

class PasswordEncrypter
{
    private const ALGORITHM = 'aes-256-gcm';
    private const TAG_LENGTH = 16;

    public function __construct(private string $secretKey)
    {
    }

    public function encryptPassword(Password $password): string
    {
        $initializationVectorLength = openssl_cipher_iv_length(self::ALGORITHM);
        $initializationVector = openssl_random_pseudo_bytes($initializationVectorLength);
        $encryptedPassword = openssl_encrypt(
            $password->getValue(),
            self::ALGORITHM,
            $this->secretKey,
            OPENSSL_RAW_DATA,
            $initializationVector,
            $authenticationTag,
            "",
            self::TAG_LENGTH
        );
        $data = sprintf("%s_%s_%s", $initializationVector, $authenticationTag, $encryptedPassword);
        return base64_encode($data);
    }

    public function decryptPassword(string $data): Password
    {
        $decodedData = base64_decode($data);
        $initializationVectorLength = openssl_cipher_iv_length(self::ALGORITHM);
        $initializationVector = substr($decodedData, 0, $initializationVectorLength);
        $authenticationTag = substr($decodedData, $initializationVectorLength + 1, self::TAG_LENGTH);
        $encryptedPassword = substr($decodedData, $initializationVectorLength + self::TAG_LENGTH + 2);
        $password = openssl_decrypt(
            $encryptedPassword,
            self::ALGORITHM,
            $this->secretKey,
            OPENSSL_RAW_DATA,
            $initializationVector,
            $authenticationTag,
            ""
        );
        if ($password === false) {
            throw new InvalidArgumentException("Couldn't decrypt password");
        }
        return new Password($password);
    }
}
