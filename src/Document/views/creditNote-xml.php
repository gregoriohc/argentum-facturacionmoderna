<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
/** @var \Argentum\FacturacionModerna\Document\CreditNote $creditNote */
?>
<cfdi:Comprobante
    xmlns:cfdi="http://www.sat.gob.mx/cfd/3"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd"
    version="3.2"
    tipoDeComprobante="egreso"
    fecha="<?php echo substr($creditNote->getDate()->format('c'), 0, 19); ?>"
    noCertificado=""
    certificado=""
    sello=""
    formaDePago="<?php echo $creditNote->getPaymentType(); ?>"
    metodoDePago="<?php echo $creditNote->getPaymentMethod(); ?>"
    LugarExpedicion="<?php echo $creditNote->getFrom()->getAddress(); ?>"
    subTotal="<?php echo number_format($creditNote->getSubtotal(), 4, '.', ''); ?>"
    descuento="<?php echo $creditNote->getDiscount(); ?>"
    total="<?php echo number_format($creditNote->getTotal(), 4, '.', ''); ?>"
    serie="A"
    folio="<?php echo $creditNote->getId(); ?>"
    condicionesDePago="<?php echo $creditNote->getPaymentConditions(); ?>"
    NumCtaPago="<?php echo $creditNote->getPaymentAccount(); ?>"
    Moneda="<?php echo $creditNote->getCurrency(); ?>"
>
    <cfdi:Emisor
        nombre="<?php echo $creditNote->getFrom()->getName(); ?>"
        rfc="<?php echo $creditNote->getFrom()->getId(); ?>"
    >
        <cfdi:DomicilioFiscal
            calle="<?php echo $creditNote->getFrom()->getAddress()->getAddress_1(); ?>"
            <?php echo !empty($creditNote->getFrom()->getAddress()->getAddress_2()) ? 'noExterior="'.$creditNote->getFrom()->getAddress()->getAddress_2().'"' : ''; ?>
            <?php echo !empty($creditNote->getFrom()->getAddress()->getAddress_3()) ? 'noInterior="'.$creditNote->getFrom()->getAddress()->getAddress_3().'"' : ''; ?>
            <?php echo !empty($creditNote->getFrom()->getAddress()->getNeighborhood()) ? 'colonia="'.$creditNote->getFrom()->getAddress()->getNeighborhood().'"' : ''; ?>
            municipio="<?php echo $creditNote->getFrom()->getAddress()->getLocality(); ?>"
            estado="<?php echo $creditNote->getFrom()->getAddress()->getState(); ?>"
            pais="<?php echo $creditNote->getFrom()->getAddress()->getCountry(); ?>"
            codigoPostal="<?php echo $creditNote->getFrom()->getAddress()->getPostcode(); ?>"
        />
        <cfdi:RegimenFiscal
            Regimen="<?php echo $creditNote->getScheme(); ?>"
        />
    </cfdi:Emisor>
    <cfdi:Receptor
        nombre="<?php echo $creditNote->getTo()->getName(); ?>"
        rfc="<?php echo $creditNote->getTo()->getId(); ?>"
    >
        <cfdi:Domicilio
            <?php echo !empty($creditNote->getTo()->getAddress()->getAddress_1()) ? 'calle="'.$creditNote->getTo()->getAddress()->getAddress_1().'"' : ''; ?>
            <?php echo !empty($creditNote->getTo()->getAddress()->getAddress_2()) ? 'noExterior="'.$creditNote->getTo()->getAddress()->getAddress_2().'"' : ''; ?>
            <?php echo !empty($creditNote->getTo()->getAddress()->getAddress_3()) ? 'noInterior="'.$creditNote->getTo()->getAddress()->getAddress_3().'"' : ''; ?>
            <?php echo !empty($creditNote->getTo()->getAddress()->getNeighborhood()) ? 'colonia="'.$creditNote->getTo()->getAddress()->getNeighborhood().'"' : ''; ?>
            <?php echo !empty($creditNote->getTo()->getAddress()->getPostcode()) ? 'codigoPostal="'.$creditNote->getTo()->getAddress()->getPostcode().'"' : ''; ?>
            <?php echo !empty($creditNote->getTo()->getAddress()->getLocality()) ? 'municipio="'.$creditNote->getTo()->getAddress()->getLocality().'"' : ''; ?>
            <?php echo !empty($creditNote->getTo()->getAddress()->getState()) ? 'estado="'.$creditNote->getTo()->getAddress()->getState().'"' : ''; ?>
            pais="<?php echo $creditNote->getTo()->getAddress()->getCountry(); ?>"
        />
    </cfdi:Receptor>
    <cfdi:Conceptos>
        <?php foreach ($creditNote->getItems() as $item) : ?>
            <cfdi:Concepto
                cantidad="<?php echo number_format($item->getQuantity(), 4, '.', ''); ?>"
                unidad="<?php echo (!empty($item->getUnit()) ? $item->getUnit() : 'No aplica'); ?>"
                descripcion="<?php echo $item->getName(); ?>"
                valorUnitario="<?php echo number_format($item->getPrice(), 4, '.', ''); ?>"
                importe="<?php echo number_format($item->getQuantity() * $item->getPrice(), 4, '.', ''); ?>"
            ></cfdi:Concepto>
        <?php endforeach; ?>
    </cfdi:Conceptos>
    <?php if (count($creditNote->getTaxes())) : ?>
    <cfdi:Impuestos
        totalImpuestosTrasladados="<?php echo number_format($creditNote->getTaxesAmount(), 4, '.', ''); ?>"
    >
        <cfdi:Traslados>
            <?php foreach ($creditNote->getTaxes() as $tax) : ?>
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