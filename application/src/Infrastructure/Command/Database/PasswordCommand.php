<?php

namespace App\Infrastructure\Command\Database;

use App\Application\Password\EncryptedPasswordStrategy;
use App\Application\Password\HashedPasswordStrategy;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class PasswordCommand
{
    public function __construct(
        private EncryptedPasswordStrategy $encryptedPasswordStrategy,
        private HashedPasswordStrategy $hashedPasswordStrategy
    ) {
    }

    #[AsCommand(name: 'password:hash', description: 'Create hash for the password')]
    public function getPasswordHash(#[Argument] string $password, OutputInterface $output): int
    {
        $output->writeln($this->hashedPasswordStrategy->getModifiedVersion($password));
        return Command::SUCCESS;
    }

    #[AsCommand(name: 'password:encrypt', description: 'Encrypt the password')]
    public function getEncryptedPassword(#[Argument] string $password, OutputInterface $output): int
    {
        $output->writeln($this->encryptedPasswordStrategy->getModifiedVersion($password));
        return Command::SUCCESS;
    }

    #[AsCommand(name: 'password:decrypt', description: 'Decrypt the password')]
    public function getDecryptedPassword(#[Argument] string $encryptedPassword, OutputInterface $output): int
    {
        $output->writeln($this->encryptedPasswordStrategy->getPasswordFromModifiedVersion($encryptedPassword));
        return Command::SUCCESS;
    }
}
