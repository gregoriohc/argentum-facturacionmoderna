<?php namespace Argentum\FacturacionModerna\Message;

/**
 * Facturacion Moderna Sign Response
 */
class SignResponse extends Response
{
    /**
     * SignResponse constructor
     *
     * @param SignRequest $request
     * @param mixed $data
     */
    public function __construct(SignRequest $request, $data)
    {
        parent::__construct($request, $data);

        // Decode base64 encoded properties
        foreach(['xml', 'pdf', 'png', 'txt'] as $property){
            if(isset($this->data[$property])){
                $this->data[$property] = base64_decode($this->data[$property]);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function isSuccessful()
    {
        return false !== $this->getReference();
    }

    /**
     * {@inheritDoc}
     */
    public function getReference()
    {
        if (isset($this->data['xml'])) {
            try {
                $xml = simplexml_load_string($this->data['xml']);
                $xml->registerXPathNamespace("tfd", "http://www.sat.gob.mx/TimbreFiscalDigital");
                $tfd = $xml->xpath('//tfd:TimbreFiscalDigital');
                return (string)$tfd[0]['UUID'];
            } catch (\Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        $files = [];

        $files[] = [
            'name'      => 'signed',
            'extension' => 'xml',
            'content'   => $this->data['xml'],
        ];

        /** @var SignRequest $this->request */
        $files[] = [
            'name'      => 'unsigned',
            'extension' => 'xml',
            'content'   => $this->request->getUnsignedXml(),
        ];

        if (!empty($this->data['pdf'])) {
            $files[] = [
                'name'      => 'signed',
                'extension' => 'pdf',
                'content'   => $this->data['pdf'],
            ];
        }

        if (!empty($this->data['png'])) {
            $files[] = [
                'name'      => 'signed',
                'extension' => 'png',
                'content'   => $this->data['png'],
            ];
        }

        if (!empty($this->data['txt'])) {
            $files[] = [
                'name'      => 'signed',
                'extension' => 'txt',
                'content'   => $this->data['txt'],
            ];
        }

        return $files;
    }


}
