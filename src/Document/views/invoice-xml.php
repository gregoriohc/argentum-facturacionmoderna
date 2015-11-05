<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
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
    formaDePago="Pago en una sola exhibición"
    metodoDePago="Transferencia Electrónica"
    LugarExpedicion="<?php echo $invoice->getFrom()->getAddress(); ?>"
    subTotal="<?php echo round($invoice->getSubtotal(), 2); ?>"
    total="<?php echo round($invoice->getTotal(), 2); ?>"
    serie="A"
    folio="<?php echo $invoice->getId(); ?>"
    condicionesDePago="Contado"
    NumCtaPago="No identificado"
    descuento="0.00"
    Moneda="<?php echo $invoice->getCurrency(); ?>"
    >
    <cfdi:Emisor nombre="<?php echo $invoice->getFrom()->getName(); ?>" rfc="<?php echo $invoice->getFrom()->getId(); ?>">
        <cfdi:DomicilioFiscal calle="RIO GUADALQUIVIR" noExterior="238" colonia="ORIENTE DEL VALLE" municipio="San Pedro Garza García" estado="Nuevo León" pais="México" codigoPostal="66220" />
        <cfdi:RegimenFiscal Regimen="REGIMEN GENERAL DE LEY PERSONAS MORALES" />
    </cfdi:Emisor>
    <cfdi:Receptor nombre="<?php echo $invoice->getTo()->getName(); ?>" rfc="<?php echo $invoice->getTo()->getId(); ?>">
        <cfdi:Domicilio calle="CERRADA DE AZUCENAS" noExterior="109" colonia="REFORMA" municipio="Oaxaca de Juárez" estado="Oaxaca" pais="México" codigoPostal="68050" />
    </cfdi:Receptor>
    <cfdi:Conceptos>
        <?php foreach ($invoice->getItems() as $item) : ?>
            <cfdi:Concepto cantidad="<?php echo round($item->getQuantity(), 2); ?>" unidad="<?php echo (!empty($item->getUnit()) ? $item->getUnit() : 'No aplica'); ?>" descripcion="<?php echo $item->getName(); ?>" valorUnitario="<?php echo round($item->getPrice(), 2); ?>" importe="<?php echo round($item->getQuantity() * $item->getPrice(), 2); ?>"></cfdi:Concepto>
        <?php endforeach; ?>
    </cfdi:Conceptos>
    <cfdi:Impuestos totalImpuestosTrasladados="1.60">
        <cfdi:Traslados>
            <?php foreach ($invoice->getTaxes() as $tax) : ?>
                <cfdi:Traslado impuesto="<?php echo $tax->getName(); ?>" tasa="<?php echo round($tax->getRate(), 2); ?>" importe="<?php echo round($tax->getAmount($invoice->getSubtotal()), 2); ?>"></cfdi:Traslado>
            <?php endforeach; ?>
        </cfdi:Traslados>
    </cfdi:Impuestos>
</cfdi:Comprobante>