<?php

namespace Orcamentos\Service;

abstract class Service
{
	protected $em;

    public function setEm($em)
    {
        $this->em = $em;
    }
}