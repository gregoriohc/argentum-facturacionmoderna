<?php namespace Argentum\FacturacionModerna\Message;

/**
 * Facturacion Moderna Cancel Response
 */
class CancelResponse extends Response
{
    /**
     * SignResponse constructor
     *
     * @param CancelRequest $request
     * @param mixed $data
     */
    public function __construct(CancelRequest $request, $data)
    {
        foreach ($data as $key => $value) {
            $data[strtolower($key)] = $value;
        }
        print_r($data);

        parent::__construct($request, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return true;
    }

    /**
     * Response Message
     *
     * @return string A response message from the invoicing gateway
     */
    public function getMessage()
    {
        return $this->data['message'];
    }

    /**
     * Response code
     *
     * @return string A response code from the invoicing gateway
     */
    public function getCode()
    {
        return $this->data['code'];
    }


}
