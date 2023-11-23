<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use EnvialoSimple\Transaccional;
use EnvialoSimple\Transaccional\Exceptions\ESTRException;
use EnvialoSimple\Transaccional\Helpers\Builder\Attachment;
use EnvialoSimple\Transaccional\Helpers\Builder\MailParams;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertTrue;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

final class MailSendTest extends TestCase
{
    protected Transaccional $estr;

    public function setUp(): void
    {
        parent::setUp();
        $this->estr = new Transaccional($_ENV['TEST_API_KEY']);
    }

    public function testFailNoFrom(): void
    {
        $mailParams = new MailParams();

        $mailParams
            ->setTo($_ENV['TEST_TO_EMAIL'], $_ENV['TEST_TO_NAME'])
            ->setSubject('Test')
            ->setHtml('<html><body><h1>TEST {{name}}</h1><img src="cid:logo"/></body></html>')
        ;

        $this->expectException(ESTRException::class);
        $this->estr->mail->send($mailParams);
    }

    public function testFailNoTo(): void
    {
        $mailParams = new MailParams();

        $mailParams
            ->setFrom($_ENV['TEST_FROM_EMAIL'], $_ENV['TEST_FROM_NAME'])
            ->setSubject('Test')
            ->setHtml('<html><body><h1>TEST {{name}}</h1><img src="cid:logo"/></body></html>')
        ;

        $this->expectException(ESTRException::class);
        $this->estr->mail->send($mailParams);
    }

    public function testFailNoSubject(): void
    {
        $mailParams = new MailParams();

        $mailParams
            ->setFrom($_ENV['TEST_FROM_EMAIL'])
            ->setFromName($_ENV['TEST_FROM_NAME'])
            ->setTo($_ENV['TEST_TO_EMAIL'])
            ->setToName($_ENV['TEST_TO_NAME'])
            ->setHtml('<html><body><h1>TEST {{name}}</h1><img src="cid:logo"/></body></html>')
        ;

        $this->expectException(ESTRException::class);
        $this->estr->mail->send($mailParams);
    }

    public function testFailNoContent(): void
    {
        $mailParams = new MailParams();

        $mailParams
            ->setFrom($_ENV['TEST_FROM_EMAIL'])
            ->setFromName($_ENV['TEST_FROM_NAME'])
            ->setTo($_ENV['TEST_TO_EMAIL'])
            ->setToName($_ENV['TEST_TO_NAME'])
        ;

        $this->expectException(ESTRException::class);
        $this->estr->mail->send($mailParams);
    }

    public function testSend(): void
    {
        $mailParams = new MailParams();

        $attachment_a = new Attachment(
            content: file_get_contents(__DIR__ . "/logo.png"),
            filename: 'filename.png'
        );

        $attachment_b = new Attachment(
            content: file_get_contents(__DIR__ . "/logo.png"),
            filename: 'logo.png',
            disposition: Attachment::DISPOSITION_INLINE,
            id: 'logo'
        );

        $mailParams
            ->setFrom($_ENV['TEST_FROM_EMAIL'], $_ENV['TEST_FROM_NAME'])
            ->setTo($_ENV['TEST_TO_EMAIL'], $_ENV['TEST_TO_NAME'])
            ->setSubject('Test')
            ->setHtml('<html><body><h1>TEST {{name}}</h1><img src="cid:logo"/></body></html>')
            ->setText('TEST')
            ->setSubstitutions(['name' => 'TestName'])
            ->setAttachments([$attachment_a, $attachment_b])
        ;

        $outcome = $this->estr->mail->send($mailParams);

        assertArrayHasKey('id', $outcome, 'Outcome does not contains a key "id"');
        assertArrayHasKey('queued', $outcome, 'Outcome does not contain a key "queued"');
        assertTrue($outcome['queued'], 'Message has not been queued');
    }

    public function testTemplateSend(): void
    {
        $mailParams = new MailParams();

        $mailParams
            ->setFrom($_ENV['TEST_FROM_EMAIL'], $_ENV['TEST_FROM_NAME'])
            ->setTo($_ENV['TEST_TO_EMAIL'], $_ENV['TEST_TO_NAME'])
            ->setSubject('Test Template')
            ->setTemplateID($_ENV['TEST_TEMPLATE_ID'])
            ->setSubstitutions(['name' => 'TestName'])
        ;

        $outcome = $this->estr->mail->send($mailParams);

        assertArrayHasKey('id', $outcome, 'Outcome contains a key "id"');
        assertArrayHasKey('queued', $outcome, 'Outcome contains a key "queued"');
        assertTrue($outcome['queued'], 'Message has been queued');
    }


}
