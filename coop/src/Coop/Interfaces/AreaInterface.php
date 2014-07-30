<?php

namespace Coop\Interfaces;

use Coop\Map\Location;

interface AreaInterface {
	public function getArea(Location $base);
}
