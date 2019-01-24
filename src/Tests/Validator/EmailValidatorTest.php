<?php
namespace App\Tests\Validators;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Validator\EmailValidator;

class EmailValidatorTest extends WebTestCase
{
    /**
     *
     * @test
     * @dataProvider dataProvider
     */
    public function validate(string $email, bool $result): void
    {
        $this->assertTrue((new EmailValidator($email))->validate() === $result);
    }

    public function dataProvider(): array
    {
        return [
            'clavin@freenet.de' => ['email' => 'clavin@freenet.de', 'result' => true],
            'wojtekd@elektron.pl' => ['email' => 'wojtekd@elektron.pl', 'result' => false],
            'kogutnataniel@yahoo.co.uk' => ['email' => 'kogutnataniel@yahoo.co.uk', 'result' => true],
            'anitka.mg@googlemaip.com' => ['email' => 'anitka.mg@googlemaip.com', 'result' => false],
            'clavin@freenet.de' => ['email' => 'clavin@freenet.de', 'result' => true],
        ];
    }

}