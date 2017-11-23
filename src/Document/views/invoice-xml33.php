<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
/** @var \Argentum\FacturacionModerna\Document\Invoice $invoice */
?>
<cfdi:Comprobante
    xmlns:cfdi="http://www.sat.gob.mx/cfd/3"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"
    Version="3.3"
    TipoDeComprobante="I"
    NoCertificado=""
    Certificado=""
    Sello=""
    Serie="A"
    Folio="<?php echo $invoice->getId(); ?>"
    Fecha="<?php echo substr($invoice->getDate()->format('c'), 0, 19); ?>"
    LugarExpedicion="<?php echo $invoice->getFrom()->getAddress()->getPostcode(); ?>"
    SubTotal="<?php echo number_format($invoice->getSubtotal(), 2, '.', ''); ?>"
    <?php if ($invoice->getDiscount() > 0) echo 'Descuento="' . number_format($invoice->getDiscount(), 2, '.', '') . '"'; ?>
    Total="<?php echo number_format($invoice->getTotal(), 2, '.', ''); ?>"
    MetodoPago="<?php echo $invoice->getPaymentType(); ?>"
    FormaPago="<?php echo $invoice->getPaymentMethod(); ?>"
    CondicionesDePago="<?php echo $invoice->getPaymentConditions(); ?>"
    Moneda="<?php echo $invoice->getCurrency(); ?>"
>
    <?php if (count($invoice->getRelations())) : ?>
        <cfdi:CfdiRelacionados TipoRelacion="<?php echo $invoice->getRelations()->all()[0]->getType(); ?>">
            <?php foreach ($invoice->getRelations() as $relation) : ?>
                <?php /** @var \Argentum\Common\Relation $relation */ ?>
                <cfdi:CfdiRelacionado UUID="<?php echo $relation->getObject()->getId(); ?>" />
            <?php endforeach; ?>
        </cfdi:CfdiRelacionados>
    <?php endif; ?>
    <cfdi:Emisor
        Nombre="<?php echo $invoice->getFrom()->getName(); ?>"
        Rfc="<?php echo $invoice->getFrom()->getId(); ?>"
        RegimenFiscal="<?php echo (!empty($invoice->getFrom()->getFiscalRegime()) ? $invoice->getFrom()->getFiscalRegime() : $invoice->getScheme()); ?>"
    />
    <cfdi:Receptor
        Nombre="<?php echo $invoice->getTo()->getName(); ?>"
        Rfc="<?php echo $invoice->getTo()->getId(); ?>"
        UsoCFDI="<?php echo $invoice->getUsage(); ?>"
    />
    <cfdi:Conceptos>
        <?php foreach ($invoice->getItems() as $item) : ?>
            <?php /** @var \Argentum\Common\Item $item */ ?>
            <cfdi:Concepto
                ClaveProdServ="<?php echo (!empty($item->getCode()) ? $item->getCode() : '01010101'); ?>"
                Cantidad="<?php echo number_format($item->getQuantity(), 2, '.', ''); ?>"
                ClaveUnidad="<?php echo (!empty($item->getUnitCode()) ? $item->getUnitCode() : 'E48'); ?>"
                Unidad="<?php echo (!empty($item->getUnit()) ? $item->getUnit() : 'Unidad de servicio'); ?>"
                Descripcion="<?php echo $item->getName(); ?>"
                ValorUnitario="<?php echo number_format($item->getPrice(), 2, '.', ''); ?>"
                Importe="<?php echo number_format($item->getQuantity() * $item->getPrice(), 2, '.', ''); ?>"
                <?php if ($item->getDiscount() > 0) echo 'Descuento="' . number_format($item->getDiscount(), 2, '.', '') . '"'; ?>
            >
                <?php if (count($item->getTaxes())) : ?>
                    <cfdi:Impuestos>
                        <cfdi:Traslados>
                            <?php foreach ($item->getTaxes() as $tax) : ?>
                                <?php /** @var \Argentum\Common\Tax $tax */ ?>
                                <cfdi:Traslado
                                    Base="<?php echo number_format($item->getBaseAmountForTax(), 2, '.', ''); ?>"
                                    Impuesto="<?php echo $tax->getType(); ?>"
                                    TipoFactor="<?php echo (!empty($tax->getRateType()) ? $tax->getRateType() : "Tasa"); ?>"
                                    TasaOCuota="<?php echo number_format($tax->getRate() / 100, 6, '.', ''); ?>"
                                    Importe="<?php echo number_format($tax->getAmount($item->getBaseAmountForTax()), 2, '.', ''); ?>"
                                />
                            <?php endforeach; ?>
                        </cfdi:Traslados>
                    </cfdi:Impuestos>
                <?php endif; ?>
            </cfdi:Concepto>
        <?php endforeach; ?>
    </cfdi:Conceptos>
    <?php if (count($invoice->getTaxes())) : ?>
    <cfdi:Impuestos
        TotalImpuestosTrasladados="<?php echo number_format($invoice->getTaxesAmount(), 2, '.', ''); ?>"
    >
        <cfdi:Traslados>
            <?php foreach ($invoice->getTaxes() as $tax) : ?>
                <?php /** @var \Argentum\Common\Tax $tax */ ?>
                <cfdi:Traslado
                    Impuesto="<?php echo $tax->getType(); ?>"
                    TipoFactor="<?php echo (!empty($tax->getRateType()) ? $tax->getRateType() : "Tasa"); ?>"
                    TasaOCuota="<?php echo number_format($tax->getRate() / 100, 6, '.', ''); ?>"
                    Importe="<?php echo number_format($tax->getAmount($tax->getBaseAmount()), 2, '.', ''); ?>"
                />
            <?php endforeach; ?>
        </cfdi:Traslados>
    </cfdi:Impuestos>
    <?php endif; ?>
</cfdi:Comprobante>