<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
/** @var \Argentum\FacturacionModerna\Document\Invoice $invoice */
?>
<cfdi:Comprobante
    xmlns:cfdi="http://www.sat.gob.mx/cfd/3"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd"
    version="3.2"
    tipoDeComprobante="ingreso"
    fecha="<?php echo substr($invoice->getDate()->format('c'), 0, 19); ?>"
    noCertificado=""
    certificado=""
    sello=""
    formaDePago="<?php echo $invoice->getPaymentType(); ?>"
    metodoDePago="<?php echo $invoice->getPaymentMethod(); ?>"
    LugarExpedicion="<?php echo $invoice->getFrom()->getAddress(); ?>"
    subTotal="<?php echo round($invoice->getSubtotal(), 2); ?>"
    total="<?php echo round($invoice->getTotal(), 2); ?>"
    serie="A"
    folio="<?php echo $invoice->getId(); ?>"
    condicionesDePago="<?php echo $invoice->getPaymentConditions(); ?>"
    NumCtaPago="<?php echo $invoice->getPaymentAccount(); ?>"
    descuento="<?php echo $invoice->getDiscount(); ?>"
    Moneda="<?php echo $invoice->getCurrency(); ?>"
    >
    <cfdi:Emisor
        nombre="<?php echo $invoice->getFrom()->getName(); ?>"
        rfc="<?php echo $invoice->getFrom()->getId(); ?>"
    >
        <cfdi:DomicilioFiscal
            calle="<?php echo $invoice->getFrom()->getAddress()->getAddress_1(); ?>"
            noExterior="<?php echo $invoice->getFrom()->getAddress()->getAddress_2(); ?>"
            noInterior="<?php echo $invoice->getFrom()->getAddress()->getAddress_3(); ?>"
            colonia="<?php echo $invoice->getFrom()->getAddress()->getNeighborhood(); ?>"
            municipio="<?php echo $invoice->getFrom()->getAddress()->getLocality(); ?>"
            estado="<?php echo $invoice->getFrom()->getAddress()->getState(); ?>"
            pais="<?php echo $invoice->getFrom()->getAddress()->getCountry(); ?>"
            codigoPostal="<?php echo $invoice->getFrom()->getAddress()->getPostcode(); ?>"
        />
        <cfdi:RegimenFiscal
            Regimen="<?php echo $invoice->getScheme(); ?>"
        />
    </cfdi:Emisor>
    <cfdi:Receptor
        nombre="<?php echo $invoice->getTo()->getName(); ?>"
        rfc="<?php echo $invoice->getTo()->getId(); ?>"
    >
        <cfdi:Domicilio
            calle="<?php echo $invoice->getTo()->getAddress()->getAddress_1(); ?>"
            noExterior="<?php echo $invoice->getTo()->getAddress()->getAddress_2(); ?>"
            noInterior="<?php echo $invoice->getTo()->getAddress()->getAddress_3(); ?>"
            colonia="<?php echo $invoice->getTo()->getAddress()->getNeighborhood(); ?>"
            municipio="<?php echo $invoice->getTo()->getAddress()->getLocality(); ?>"
            estado="<?php echo $invoice->getTo()->getAddress()->getState(); ?>"
            pais="<?php echo $invoice->getTo()->getAddress()->getCountry(); ?>"
            codigoPostal="<?php echo $invoice->getTo()->getAddress()->getPostcode(); ?>"
        />
    </cfdi:Receptor>
    <cfdi:Conceptos>
        <?php foreach ($invoice->getItems() as $item) : ?>
            <cfdi:Concepto
                cantidad="<?php echo round($item->getQuantity(), 2); ?>"
                unidad="<?php echo (!empty($item->getUnit()) ? $item->getUnit() : 'No aplica'); ?>"
                descripcion="<?php echo $item->getName(); ?>"
                valorUnitario="<?php echo round($item->getPrice(), 2); ?>"
                importe="<?php echo round($item->getQuantity() * $item->getPrice(), 2); ?>"
            ></cfdi:Concepto>
        <?php endforeach; ?>
    </cfdi:Conceptos>
    <cfdi:Impuestos
        totalImpuestosTrasladados="<?php echo $invoice->getTaxesAmount(); ?>"
    >
        <cfdi:Traslados>
            <?php foreach ($invoice->getTaxes() as $tax) : ?>
                <cfdi:Traslado
                    impuesto="<?php echo $tax->getName(); ?>"
                    tasa="<?php echo round($tax->getRate(), 2); ?>"
                    importe="<?php echo round($tax->getAmount($invoice->getSubtotal()), 2); ?>"
                ></cfdi:Traslado>
            <?php endforeach; ?>
        </cfdi:Traslados>
    </cfdi:Impuestos>
</cfdi:Comprobante>