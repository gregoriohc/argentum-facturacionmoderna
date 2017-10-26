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
    subTotal="<?php echo number_format($invoice->getSubtotal(), 4, '.', ''); ?>"
    descuento="<?php echo $invoice->getDiscount(); ?>"
    total="<?php echo number_format($invoice->getTotal(), 4, '.', ''); ?>"
    serie="A"
    folio="<?php echo $invoice->getId(); ?>"
    condicionesDePago="<?php echo $invoice->getPaymentConditions(); ?>"
    NumCtaPago="<?php echo $invoice->getPaymentAccount(); ?>"
    Moneda="<?php echo $invoice->getCurrency(); ?>"
>
    <cfdi:Emisor
        nombre="<?php echo $invoice->getFrom()->getName(); ?>"
        rfc="<?php echo $invoice->getFrom()->getId(); ?>"
    >
        <cfdi:DomicilioFiscal
            calle="<?php echo $invoice->getFrom()->getAddress()->getAddress_1(); ?>"
            <?php echo !empty($invoice->getFrom()->getAddress()->getAddress_2()) ? 'noExterior="'.$invoice->getFrom()->getAddress()->getAddress_2().'"' : ''; ?>
            <?php echo !empty($invoice->getFrom()->getAddress()->getAddress_3()) ? 'noInterior="'.$invoice->getFrom()->getAddress()->getAddress_3().'"' : ''; ?>
            <?php echo !empty($invoice->getFrom()->getAddress()->getNeighborhood()) ? 'colonia="'.$invoice->getFrom()->getAddress()->getNeighborhood().'"' : ''; ?>
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
            <?php echo !empty($invoice->getTo()->getAddress()->getAddress_1()) ? 'calle="'.$invoice->getTo()->getAddress()->getAddress_1().'"' : ''; ?>
            <?php echo !empty($invoice->getTo()->getAddress()->getAddress_2()) ? 'noExterior="'.$invoice->getTo()->getAddress()->getAddress_2().'"' : ''; ?>
            <?php echo !empty($invoice->getTo()->getAddress()->getAddress_3()) ? 'noInterior="'.$invoice->getTo()->getAddress()->getAddress_3().'"' : ''; ?>
            <?php echo !empty($invoice->getTo()->getAddress()->getNeighborhood()) ? 'colonia="'.$invoice->getTo()->getAddress()->getNeighborhood().'"' : ''; ?>
            <?php echo !empty($invoice->getTo()->getAddress()->getPostcode()) ? 'codigoPostal="'.$invoice->getTo()->getAddress()->getPostcode().'"' : ''; ?>
            <?php echo !empty($invoice->getTo()->getAddress()->getLocality()) ? 'municipio="'.$invoice->getTo()->getAddress()->getLocality().'"' : ''; ?>
            <?php echo !empty($invoice->getTo()->getAddress()->getState()) ? 'estado="'.$invoice->getTo()->getAddress()->getState().'"' : ''; ?>
            pais="<?php echo $invoice->getTo()->getAddress()->getCountry(); ?>"
        />
    </cfdi:Receptor>
    <cfdi:Conceptos>
        <?php foreach ($invoice->getItems() as $item) : ?>
            <cfdi:Concepto
                cantidad="<?php echo number_format($item->getQuantity(), 4, '.', ''); ?>"
                unidad="<?php echo (!empty($item->getUnit()) ? $item->getUnit() : 'No aplica'); ?>"
                descripcion="<?php echo $item->getName(); ?>"
                valorUnitario="<?php echo number_format($item->getPrice(), 4, '.', ''); ?>"
                importe="<?php echo number_format($item->getQuantity() * $item->getPrice(), 4, '.', ''); ?>"
            ></cfdi:Concepto>
        <?php endforeach; ?>
    </cfdi:Conceptos>
    <?php if (count($invoice->getTaxes())) : ?>
    <cfdi:Impuestos
        totalImpuestosTrasladados="<?php echo number_format($invoice->getTaxesAmount(), 4, '.', ''); ?>"
    >
        <cfdi:Traslados>
            <?php foreach ($invoice->getTaxes() as $tax) : ?>
                <cfdi:Traslado
                    impuesto="<?php echo $tax->getName(); ?>"
                    tasa="<?php echo number_format($tax->getRate(), 2, '.', ''); ?>"
                    importe="<?php echo number_format($tax->getAmount($tax->getBaseAmount()), 4, '.', ''); ?>"
                ></cfdi:Traslado>
            <?php endforeach; ?>
        </cfdi:Traslados>
    </cfdi:Impuestos>
    <?php endif; ?>
</cfdi:Comprobante>