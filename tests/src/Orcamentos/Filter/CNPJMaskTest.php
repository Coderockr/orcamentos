<?php

namespace Orcamentos\Service;

use Orcamentos\Filter\CNPJMask;

/**
 * Class CNPJMaskTest
 * @package Orcamentos\Service
 */
class CNPJMaskTest extends \PHPUnit\Framework\TestCase
{
    public function dataForAddMask()
    {
        return array(
            array(
                'expected' => '18.915.219/0001-86',
                'actual' => '18915219000186'
            ),
            array(
                'expected' => '09.376.674/0001-60',
                'actual' => '09376674000160'
            ),
            array(
                'expected' => '09.376.674/0001-60',
                'actual' => 9376674000160
            )
        );
    }

    public function dataForRemoveMask()
    {
        return array(
            array(
                'actual' => '18.915.219/0001-86',
                'expected' => 18915219000186
            ),
            array(
                'actual' => '09.376.674/0001-60',
                'expected' => 9376674000160
            )
        );
    }

    /**
     * @dataProvider dataForRemoveMask
     */
    public function testRemoveMask($actual, $expected)
    {
        $filter = new CNPJMask();

        $cnpj = $filter->removeMask($actual);

        $this->assertEquals($expected, $cnpj);
    }

    /**
     * @dataProvider dataForAddMask
     */
    public function testApplyMask($expected, $actual)
    {
        $filter = new CNPJMask();

        $cnpj = $filter->applyMask($actual);

        $this->assertEquals($expected, $cnpj);
    }
}
