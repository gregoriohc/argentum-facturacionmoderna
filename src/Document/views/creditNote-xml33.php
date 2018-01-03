<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
/** @var \Argentum\FacturacionModerna\Document\CreditNote $creditNote */
?>
<cfdi:Comprobante
    xmlns:cfdi="http://www.sat.gob.mx/cfd/3"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd"
    Version="3.3"
    TipoDeComprobante="E"
    NoCertificado=""
    Certificado=""
    Sello=""
    <?php if (!empty($creditNote->getSerie())) echo 'Serie="' . $creditNote->getSerie() . '"'; ?>
    Folio="<?php echo $creditNote->getId(); ?>"
    Fecha="<?php echo substr($creditNote->getDate()->format('c'), 0, 19); ?>"
    LugarExpedicion="<?php echo $creditNote->getFrom()->getAddress()->getPostcode(); ?>"
    SubTotal="<?php echo number_format($creditNote->getSubtotal(), 2, '.', ''); ?>"
    <?php if ($creditNote->getDiscount() > 0) echo 'Descuento="' . number_format($creditNote->getDiscount(), 2, '.', '') . '"'; ?>
    Total="<?php echo number_format($creditNote->getTotal(), 2, '.', ''); ?>"
    MetodoPago="<?php echo $creditNote->getPaymentType(); ?>"
    FormaPago="<?php echo $creditNote->getPaymentMethod(); ?>"
    CondicionesDePago="<?php echo $creditNote->getPaymentConditions(); ?>"
    Moneda="<?php echo $creditNote->getCurrency(); ?>"
>
    <?php if (count($creditNote->getRelations())) : ?>
        <cfdi:CfdiRelacionados TipoRelacion="<?php echo $creditNote->getRelations()->all()[0]->getType(); ?>">
            <?php foreach ($creditNote->getRelations() as $relation) : ?>
                <?php /** @var \Argentum\Common\Relation $relation */ ?>
                <cfdi:CfdiRelacionado UUID="<?php echo $relation->getObject()->getId(); ?>" />
            <?php endforeach; ?>
        </cfdi:CfdiRelacionados>
    <?php endif; ?>
    <cfdi:Emisor
        Nombre="<?php echo $creditNote->getFrom()->getName(); ?>"
        Rfc="<?php echo $creditNote->getFrom()->getId(); ?>"
        RegimenFiscal="<?php echo (!empty($creditNote->getFrom()->getFiscalRegime()) ? $creditNote->getFrom()->getFiscalRegime() : $creditNote->getScheme()); ?>"
    />
    <cfdi:Receptor
        Nombre="<?php echo $creditNote->getTo()->getName(); ?>"
        Rfc="<?php echo $creditNote->getTo()->getId(); ?>"
        UsoCFDI="<?php echo $creditNote->getUsage(); ?>"
    />
    <cfdi:Conceptos>
        <?php foreach ($creditNote->getItems() as $item) : ?>
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
    <?php if (count($creditNote->getTaxes())) : ?>
    <cfdi:Impuestos
        TotalImpuestosTrasladados="<?php echo number_format($creditNote->getTaxesAmount(), 2, '.', ''); ?>"
    >
        <cfdi:Traslados>
            <?php foreach ($creditNote->getTaxes() as $tax) : ?>
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