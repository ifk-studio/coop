<?php

namespace Coop\Interfaces;

use Coop\Objects\Objects;

interface FormaterInterface {
	public function format(Objects $objects, $to = null);
}