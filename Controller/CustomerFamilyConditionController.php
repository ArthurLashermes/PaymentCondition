<?php

namespace PaymentCondition\Controller;

use CustomerFamily\Model\CustomerFamily;
use CustomerFamily\Model\CustomerFamilyQuery;
use PaymentCondition\Model\PaymentCustomerFamilyCondition;
use PaymentCondition\Model\PaymentCustomerFamilyConditionQuery;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Model\Module;
use Thelia\Model\ModuleQuery;

class CustomerFamilyConditionController extends BaseAdminController
{
    public function viewAction()
    {
        $customerFamilyPaymentsModules = [];
        $moduleCodes = [];
        $familyCodes = [];

        $paymentModules = ModuleQuery::create()
            ->findByCategory('payment');

        $customerFamilies = CustomerFamilyQuery::create()
            ->find();

        /** @var Module $paymentModule */
        foreach ($paymentModules as $paymentModule) {
            $moduleCodes[$paymentModule->getId()] = $paymentModule->getCode();

            /** @var CustomerFamily $customerFamily */
            foreach ($customerFamilies as $customerFamily) {
                $customerFamilyPaymentsModules[$customerFamily->getId()][$paymentModule->getId()] = 0;
                $familyCodes[$customerFamily->getId()] = $customerFamily->getCode();
            }
        }

        $customerFamilyPayments = PaymentCustomerFamilyConditionQuery::create()
            ->find();

        if (null !== $customerFamilyPayments) {
            /** @var PaymentCustomerFamilyCondition $customerFamilyPayment */
            foreach ($customerFamilyPayments as $customerFamilyPayment) {
                $customerFamilyPaymentsModules[$customerFamilyPayment->getCustomerFamilyId()][$customerFamilyPayment->getPaymentModuleId()] = $customerFamilyPayment->getIsValid();
            }
        }

        return $this->render('payment-condition/customer_family', [
            "module_codes" => $moduleCodes,
            "family_codes" => $familyCodes,
            "paymentFamilyCondition" =>$customerFamilyPaymentsModules
        ]);
    }

    public function saveAction()
    {
        $request = $this->getRequest();

        try {
            $moduleId = $request->request->get("moduleId");
            $customerFamilyId = $request->request->get("customerFamilyId");
            $isValid = $request->request->get("isValid") == "true" ? 1 : 0;

            $paymentCustomerFamily = PaymentCustomerFamilyConditionQuery::create()
                ->filterByPaymentModuleId($moduleId)
                ->filterByCustomerFamilyId($customerFamilyId)
                ->findOneOrCreate();

            $paymentCustomerFamily->setIsValid($isValid)
                ->save();

        } catch (\Exception $e) {
            return JsonResponse::create($e->getMessage(), 500);
        }
        return JsonResponse::create("Success");
    }
}
