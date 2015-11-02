<?php namespace Argentum\FacturacionModerna\Message;

use Argentum\Common\Message\AbstractResponse;
use Argentum\Common\Message\ResponseInterface;

/**
 * Facturacion Moderna Sign Response
 */
class SignResponse extends AbstractResponse implements ResponseInterface
{
    public function isSuccessful()
    {
        return true;
    }

    public function getReference()
    {
        return false;
    }

}
