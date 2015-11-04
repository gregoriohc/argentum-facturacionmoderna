<?php namespace Argentum\FacturacionModerna\Message;

use Argentum\Common\Address;
use Argentum\Common\Item;
use Argentum\Common\Person;
use Argentum\FacturacionModerna\Document\Invoice;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class SignRequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $address = new Address();
        $address->setAddress_1('Fake Street');
        $address->setAddress_2('123');
        $address->setAddress_3('1-A');
        $address->setNeighborhood('City Center');
        $address->setPostcode('12345');
        $address->setLocality('Big City');
        $address->setState('Best State');
        $address->setCountry('IE');

        $from = new Person();
        $from->setId('1234567890A');
        $from->setName('Big Company');
        $from->setAddress($address);

        $to = new Person();
        $to->setId('1234567890A');
        $to->setName('Big Company');
        $to->setAddress($address);

        $invoice = new Invoice();
        $invoice->setId('1');
        $invoice->setDate(new \DateTime());
        $invoice->setFrom($from);
        $invoice->setTo($to);
        $invoice->setItems([
            ['name' => 'Product A', 'quantity' => 1, 'price' => 123.45],
            ['name' => 'Product B', 'quantity' => 2, 'price' => 678.90],
        ]);
        $invoice->setTaxes([
            ['name' => 'IVA', 'rate' => 16.00],
        ]);

        $this->request = new SignRequest(new HttpClient(), new HttpRequest());
        $this->request->initialize(
            array(
                'currency' => 'MXN',
                'document' => $invoice,
            )
        );
    }

    public function testCaptureIsTrue()
    {
        $data = $this->request->getData();
        //$this->assertSame('true', $data['capture']);
    }
   /*
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ch_1IU9gcUiNASROd', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('ch_1IUAZQWFYrPooM', $response->getTransactionReference());
        $this->assertNull($response->getCardReference());
        $this->assertSame('Your card was declined', $response->getMessage());
    }
   */
}
