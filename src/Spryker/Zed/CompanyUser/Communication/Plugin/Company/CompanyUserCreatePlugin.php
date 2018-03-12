<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Communication\Plugin\Company;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 */
class CompanyUserCreatePlugin extends AbstractPlugin implements CompanyPostCreatePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function postCreate(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();
        $companyTransfer = $this->createCompanyUser($companyTransfer);
        $companyResponseTransfer->setCompanyTransfer($companyTransfer);

        return $companyResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompanyUser(CompanyTransfer $companyTransfer): CompanyTransfer
    {
        $companyTransfer->requireInitialUserTransfer();
        $companyUserTransfer = $companyTransfer->getInitialUserTransfer();
        $companyUserTransfer->setFkCompany($companyTransfer->getIdCompany());
        $companyUserResponseTransfer = $this->getFacade()->create($companyUserTransfer);
        $companyTransfer->setInitialUserTransfer($companyUserResponseTransfer->getCompanyUser());

        return $companyTransfer;
    }
}
