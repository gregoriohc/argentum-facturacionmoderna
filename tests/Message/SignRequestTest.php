<?php namespace Tests\Argentum\FacturacionModerna\Message;

use Argentum\Argentum;
use Argentum\FacturacionModerna\Message\SignRequest;

class SignRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Argentum\FacturacionModerna\Gateway
     */
    protected $gateway;

    public function setUp()
    {
        $this->gateway = Argentum::create('FacturacionModerna');
        $this->gateway->setUserId('UsuarioPruebasWS');
        $this->gateway->setUserPassword('b9ec2afa3361a59af4b4d102d3f704eabdf097d4');
        $this->gateway->setTestMode(true);
        $this->gateway->setCurrency('MXN');
    }

    public function testCanSignInvoice()
    {
        $this->gateway->setIssuerRfc('ESI920427886');
        $this->gateway->setIssuerCertificateId('20001000000200000192');
        $this->gateway->setIssuerPublicCertificate(__DIR__ . '/../../resources/certificates/20001000000200000192.cer');
        $this->gateway->setIssuerPrivateCertificate(__DIR__ . '/../../resources/certificates/20001000000200000192.key.pem');

        /** @var \Argentum\FacturacionModerna\Document\Invoice $invoice */
        $invoice = $this->gateway->createDocument('invoice', [
            'id' 	=> '123',
            'from' 	=> [
                'id'      => 'ESI920427886',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'to' 	=> [
                'id'      => 'XAXX010101000',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'date'  => new \DateTime('yesterday'),
        ]);

        $invoice->setPaymentType('Pago en una sola exhibición');
        $invoice->setPaymentMethod('03');
        $invoice->setPaymentConditions('Contado');
        $invoice->setPaymentAccount('No identificado');
        $invoice->setDiscount(0.00);
        $invoice->setScheme('REGIMEN GENERAL DE LEY PERSONAS MORALES');

        $invoice->setItems([
            [
                'name' => 'Item 1',
                'description' => 'Descripcion 1',
                'quantity' => 1,
                'unit' => 'Servicio',
                'price' => 123.45,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'rate' => '16',
                    ]
                ],
            ],
            [
                'name' => 'Item 2',
                'description' => 'Descripcion 2',
                'quantity' => 2,
                'unit' => 'Servicio',
                'price' => 678.90,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'rate' => '16',
                    ]
                ],
            ]
        ]);

        /** @var SignRequest $request */
        $request = $this->gateway->sign([
            'document' => $invoice
        ]);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful(), $response->getMessage());
    }

    public function testCanSignCreditNote()
    {
        $this->gateway->setIssuerRfc('ESI920427886');
        $this->gateway->setIssuerCertificateId('20001000000200000192');
        $this->gateway->setIssuerPublicCertificate(__DIR__ . '/../../resources/certificates/20001000000200000192.cer');
        $this->gateway->setIssuerPrivateCertificate(__DIR__ . '/../../resources/certificates/20001000000200000192.key.pem');

        /** @var \Argentum\FacturacionModerna\Document\CreditNote $creditNote */
        $creditNote = $this->gateway->createDocument('creditNote', [
            'id' 	=> '123',
            'from' 	=> [
                'id'      => 'ESI920427886',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'to' 	=> [
                'id'      => 'XAXX010101000',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'date'  => new \DateTime('yesterday'),
        ]);

        $creditNote->setPaymentType('Pago en una sola exhibición');
        $creditNote->setPaymentMethod('03');
        $creditNote->setPaymentConditions('Contado');
        $creditNote->setPaymentAccount('No identificado');
        $creditNote->setDiscount(0.00);
        $creditNote->setScheme('REGIMEN GENERAL DE LEY PERSONAS MORALES');

        $creditNote->setItems([
            [
                'name' => 'Item 1',
                'description' => 'Descripcion 1',
                'quantity' => 1,
                'unit' => 'Servicio',
                'price' => 123.45,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'rate' => '16',
                    ]
                ],
            ],
            [
                'name' => 'Item 2',
                'description' => 'Descripcion 2',
                'quantity' => 2,
                'unit' => 'Servicio',
                'price' => 678.90,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'rate' => '16',
                    ]
                ],
            ]
        ]);

        /** @var SignRequest $request */
        $request = $this->gateway->sign([
            'document' => $creditNote
        ]);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful(), $response->getMessage());
    }

    public function testCanSignInvoice33()
    {
        $this->gateway->setIssuerRfc('TCM970625MB1');
        $this->gateway->setIssuerCertificateId('20001000000300022762');
        $this->gateway->setIssuerPublicCertificate(__DIR__ . '/../../resources/certificates/20001000000300022762.cer');
        $this->gateway->setIssuerPrivateCertificate(__DIR__ . '/../../resources/certificates/20001000000300022762.key.pem');

        /** @var \Argentum\FacturacionModerna\Document\Invoice $invoice */
        $invoice = $this->gateway->createDocument('invoice', [
            'id' 	=> '123',
            'from' 	=> [
                'id'      => 'TCM970625MB1',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'to' 	=> [
                'id'      => 'XAXX010101000',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'date'  => new \DateTime('yesterday'),
        ]);

        $invoice->setPaymentType('PUE');
        $invoice->setPaymentMethod('03');
        $invoice->setPaymentConditions('Contado');
        $invoice->setScheme('601');
        $invoice->setUsage('G01');

        $invoice->setItems([
            [
                'code' => '01010101',
                'name' => 'Item 1',
                'description' => 'Descripcion 1',
                'quantity' => 1,
                'unit' => 'Servicio',
                'price' => 123.45,
                'discount' => 10.00,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'type' => '002',
                        'rate' => '16',
                    ]
                ],
            ],
            [
                'code' => '01010101',
                'name' => 'Item 2',
                'description' => 'Descripcion 2',
                'quantity' => 2,
                'unit' => 'Servicio',
                'price' => 678.90,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'type' => '002',
                        'rate' => '16',
                    ]
                ],
            ]
        ]);

        /** @var SignRequest $request */
        $request = $this->gateway->sign([
            'document' => $invoice
        ]);

        $request->setCfdiVersion(SignRequest::CFDI_VERSION_3_3);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful(), $response->getMessage());

        /** @var \Argentum\FacturacionModerna\Document\CreditNote $creditNote */
        $creditNote = $this->gateway->createDocument('creditNote', [
            'id' 	=> '456',
            'from' 	=> [
                'id'      => 'TCM970625MB1',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'to' 	=> [
                'id'      => 'XAXX010101000',
                'type'    => 'natural',
                'name'    => 'Empresa de Ejemplo',
                'email'   => 'facturacion@example.com',
                'phone'   => '+52 1 23456789',
                'fax'     => '+52 1 98765432',
                'address' => [
                    'address_1' => 'Paseo de la Reforma, 1',
                    'address_2' => 'Torre 4',
                    'postcode'  => '11500',
                    'locality'  => 'Cuauthemoc',
                    'state'     => 'Ciudad de Mexico',
                    'country'   => 'MX',
                ],
            ],
            'date'  => new \DateTime('yesterday'),
        ]);

        $creditNote->setPaymentType('PUE');
        $creditNote->setPaymentMethod('03');
        $creditNote->setPaymentConditions('Contado');
        $creditNote->setScheme('601');
        $creditNote->setUsage('G01');

        $creditNote->setItems([
            [
                'code' => '84111506',
                'name' => 'Descuento 1',
                'description' => 'Descripcion 1',
                'quantity' => 1,
                'unit' => 'Actividad',
                'unit_code' => 'ACT',
                'price' => 50.00,
                'taxes' => [
                    [
                        'name' => 'IVA',
                        'type' => '002',
                        'rate' => '16',
                    ]
                ],
            ]
        ]);

        $invoice->setId($response->getReference());
        $creditNote->addRelation([
            'type' => '01',
            'object' => $invoice,
        ]);

        /** @var SignRequest $request */
        $request = $this->gateway->sign([
            'document' => $creditNote
        ]);

        $request->setCfdiVersion(SignRequest::CFDI_VERSION_3_3);

        $response = $request->send();

        $this->assertTrue($response->isSuccessful(), $response->getMessage());
    }
}
