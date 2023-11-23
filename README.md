<a href="https://envialosimple.com/transaccional"><img src="https://envialosimple.com/images/logo_tr.svg" width="200px"/></a>

# EnvíaloSimple Transaccional - PHP SDK

## Installation

```bash
composer require envialosimple/transaccional
```

## Basic Usage

```php
use EnvialoSimple\Transaccional;
use EnvialoSimple\Transaccional\Helpers\Builder\MailParams;

$estr = new Transaccional($your_api_key);

$mailParams = new MailParams();

$mailParams
    ->setFrom('no-reply@mycompany.com', 'MyCompany Notifications')
    ->setTo('john.doe@example.com', 'John Doe'])
    ->setSubject('This is a subject')
    ->setHtml('<h1>HTML emails are cool, {{name}}</h1>')
    ->setText('Text emails are also cool, {{name}}')
    ->setSubstitutions(['name' => 'John'])
    ;

$estr->mail->send($mailParams);
```

