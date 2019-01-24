<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Validator\EmailValidator;

class EmailValidateCommand extends Command
{
    protected static $defaultName = 'app:email-validate';

    protected $validCount = 0;

    protected $invalidCount = 0;

    protected $invalidFile = null;

    protected $validFile = null;

    protected $resultFile = null;

    protected function configure()
    {
        $this
            ->addArgument('file',InputArgument::REQUIRED, 'Path to the file')
            ->setDescription('Validates email list.')
            ->setHelp('Validates email list.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createResultFiles();

        if (false !== ($file = fopen($input->getArgument('file'), 'r'))) {
            while (($email = fgets($file)) !== false) {
                $this->handleEmail(trim($email));
            }
            fclose($file);
        }

        $this->closeResultFiles();
    }

    protected function handleEmail(string $email): void
    {
        if ((new EmailValidator($email))->validate()) {
            $this->validCount++;
            fwrite($this->validFile, $email . PHP_EOL);
        } else {
            $this->invalidCount++;
            fwrite($this->invalidFile, $email . PHP_EOL);
        }
    }

    protected function createResultFiles(): void
    {
        $date = (new \DateTimeImmutable())->format('YmdHis');
        $this->invalidFile = fopen(__DIR__ . DIRECTORY_SEPARATOR . '../../var/invalid_emails_' . $date . '.csv', 'w');
        $this->validFile = fopen(__DIR__ . DIRECTORY_SEPARATOR . '../../var/valid_emails_' . $date . '.csv', 'w');
        $this->resultFile = fopen(__DIR__ . DIRECTORY_SEPARATOR .  '../../var/result_' . $date . '.txt', 'w');
    }

    protected function closeResultFiles(): void
    {
        fclose($this->invalidFile);
        fclose($this->validFile);
        fwrite(
            $this->resultFile,
            sprintf(
                'Przeanalizowano %d emaili, %d poprawnych, %d niepoprawnych',
                $this->invalidCount + $this->validCount,
                $this->validCount,
                $this->invalidCount
            )
        );
        fclose($this->resultFile);
    }
}