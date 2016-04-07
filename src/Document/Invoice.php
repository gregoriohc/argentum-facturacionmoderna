<?php namespace Argentum\FacturacionModerna\Document;

use Argentum\Common\Document\Invoice as CommonInvoice;

class Invoice extends CommonInvoice
{
    /**
     * Get payment type
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->getParameter('payment_type');
    }

    /**
     * Set payment type
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setPaymentType($value)
    {
        return $this->setParameter('payment_type', $value);
    }

    /**
     * Get payment method
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->getParameter('payment_method');
    }

    /**
     * Set payment method
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setPaymentMethod($value)
    {
        return $this->setParameter('payment_method', $value);
    }

    /**
     * Get payment conditions
     *
     * @return string
     */
    public function getPaymentConditions()
    {
        return $this->getParameter('payment_conditions');
    }

    /**
     * Set payment conditions
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setPaymentConditions($value)
    {
        return $this->setParameter('payment_conditions', $value);
    }

    /**
     * Get payment account
     *
     * @return string
     */
    public function getPaymentAccount()
    {
        return $this->getParameter('payment_account');
    }

    /**
     * Set payment account
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setPaymentAccount($value)
    {
        return $this->setParameter('payment_account', $value);
    }

    /**
     * Get discount
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->getDiscountsAmount();
    }

    /**
     * Set discount
     *
     * @param float $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setDiscount($value)
    {
        return $this->setDiscounts(['name' => 'Discount', 'amount' => $value]);
    }

    /**
     * Get scheme
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->getParameter('scheme');
    }

    /**
     * Set scheme
     *
     * @param string $value Parameter value
     * @return Invoice provides a fluent interface.
     */
    public function setScheme($value)
    {
        return $this->setParameter('scheme', $value);
    }
}
