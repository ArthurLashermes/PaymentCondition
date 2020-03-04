<?php

namespace PaymentCondition\Hook;

use PaymentCondition\Model\PaymentCustomerConditionQuery;
use PaymentCondition\Model\PaymentCustomerModuleConditionQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

class CustomerEditHook extends BaseHook
{
    public function onCustomerEdit(HookRenderEvent $event)
    {
        $customerId = $event->getArgument('customer_id');

        $paymentCustomerCondition = PaymentCustomerConditionQuery::create()
            ->findOneByCustomerId($customerId);

        $paymentCustomerModuleConditions = PaymentCustomerModuleConditionQuery::create()
            ->findByCustomerId($customerId);

        $allowedModules = [];
        foreach ($paymentCustomerModuleConditions as $paymentCustomerModuleCondition) {
            if ($paymentCustomerModuleCondition->getIsValid()) {
                $allowedModules[] = $paymentCustomerModuleCondition->getModule();
            }
        }

        $event->add($this->render('payment-condition/customer_edit_hook.html', compact('paymentCustomerCondition', 'allowedModules')));
    }
}
