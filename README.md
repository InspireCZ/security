# InspireCZ/Security

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](license.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Balíček pro práci se šifrováním a hesly.

## Požadavky

InspireCZ/Security vyžaduje PHP 8.0 nebo vyšší.

- [Nette/Security](https://github.com/nette/security)

## Instalace

Nejlepší způsob jak InspireCZ/Security nainstalovat je pomocí Composeru

``` bash
$ composer require inspirecz/security
```

## Použití

### Symetrické šifrování

Pomocí klíče zašifruje, resp. rozšifruje, požadovaná data. Pro šifrování se používá šifra *AES-256-CTR* (klíč musí mít 32 znaků).

``` php
$hash = 'edb433bdd7c13851c7c68cb31a5acf33';
$symetric = new \Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder($hash);

$plaintext = 'Hello world!';
$ciphertext = $symetric->encode($plaintext);
echo $symetric->decode($ciphertext); // vystup: Hello world!
```

Pro pohodlné použití v projektu je možné zaregistrovat symetrické šifrování jako službu (např. inject pomocí konstruktoru):
 
*config.neon*

``` neon
parameters:
    crypt:
        symetrickey: 'edb433bdd7c13851c7c68cb31a5acf33' 

service:
    - TestService
    cryptService: \Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder(%crypt.symetricKey%)
```

*TestService.php*

``` php
class TestService
{

    /** @var \Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder */
    private $cryptService;

    /**
     * @param \Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder $cryptService
     */
    public function __construct(\Inspire\Security\Crypt\OpenSSLSymetricEncoderDecoder $cryptService)
    {
        $this->cryptService = $cryptService;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function useCrypt(string $text): string
    {
        return $this->cryptService->encode($text);
    }

}
```

### Šifrování pomocí veřejného/privátního klíče

Balíček obsahuje dvě třídy pro zašifrování, resp. rozšifrování, pomocí veřejného nebo privátního klíče. Základní vlastnost je, že data zašifrovaná veřejným klíčem lze rozšifrovat pouze pomcí privátního klíče a naopak zpráva zašifrovaná priváním klíčem lzde rozšifrovat pouze veřejným klíčem. Privátní klíč může používat heslo, pokud je s ním vygenerovaný.

**Omezení**: Maximální délka data je závislá od použitého klíče. Pro RSA 256 bitů je omezení 245 znaků
 
 ``` php
$publicCrypt = \Inspire\Security\Crypt\OpenSSLPublicKeyCrypt::fromFile('public_key.pem');
$privateCrypt = \Inspire\Security\Crypt\OpenSSLPrivateKeyCrypt::fromFile('private_key.pem', 'passwordForKey');

$plaintext = 'Secret message for private key';
$ciphertext = $publicCrypt->encrypt($plaintext);
echo $privateCrypt->decrypt($ciphertext); // vystup: Secret message for private key

$plaintext = 'Secret message for public key';
$ciphertext = $privateCrypt->encrypt($plaintext);
echo $publicCrypt->decrypt($ciphertext); // vystup: Secret message for public key
 ```

Vytvořit KeyCrypt objekt můžeme standardně pomocí new a jako parameter konstruktoru předat přímo obsah klíče, nebo můžeme použít pomocnou statickou metodu fromFile. Ta očekává jako parametr cestu k souboru s klíčem a vrací novou instanci crypt objektu. 

### Generování hash z hesla a jeho ověření

``` php
$generator = new \Inspire\Security\Password\BCryptPasswordHashGenerator();
$hash = $generator->generate('my-brutal-password');

if ($generator->verify('i-dont-know-my-password', $hash)) {
    echo 'OK';
} else {
    echo 'Try it again';
}

// výstup: Try it again
```

### Generování náhodného tokenu

``` php
$generator = new \Inspire\Security\Password\RandomTokenGenerator();
echo $generator->generate(); // výstup: edb433bdd7c13851c7c68cb31a5acf33
```

## Testy

``` bash
$ vendor/bin/phpunit test
```

## Bezpečnost

Pokud objevíte jakýkoli bezpečnostní problém, kontaktujte nás prosím na e-mail support@inspire.cz místo využití issue.



[ico-version]: https://img.shields.io/packagist/v/InspireCZ/Security.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/InspireCZ/Security/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/InspireCZ/Security.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/InspireCZ/Security.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/InspireCZ/Security.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/InspireCZ/Security
[link-travis]: https://travis-ci.org/InspireCZ/Security
[link-scrutinizer]: https://scrutinizer-ci.com/g/InspireCZ/Security/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/InspireCZ/Security
[link-downloads]: https://packagist.org/packages/InspireCZ/Security
[link-author]: https://github.com/InspireCZ
