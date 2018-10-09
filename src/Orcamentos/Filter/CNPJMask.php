<?php

namespace Orcamentos\Filter;

/**
 * Class CNPJMask
 * @package Orcamentos\Filter
 */
class CNPJMask
{
    /**
     * @param $cnpj
     * @return int
     */
    public function removeMask($cnpj)
    {
        return (int)preg_replace("/\D/", '', $cnpj);
    }

    /**
     * @param $cnpj
     * @return string
     */
    public function applyMask($cnpj)
    {
        // asserting that only digits will be present
        $cnpj = $this->removeMask($cnpj);

        // applying 14 digits
        $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

        $pattern = "/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/";
        $replacement = "\$1.\$2.\$3/\$4-\$5";

        return (string)preg_replace($pattern, $replacement, $cnpj);
    }
}
