<?php

namespace Saldanhakun\Semantics\Tests\src\Enum;

use Saldanhakun\Semantics\Data\Attribute\EnumColorTrait;
use Saldanhakun\Semantics\Data\Attribute\EnumIconTrait;
use Saldanhakun\Semantics\Data\Attribute\EnumSlugTrait;

class FullFiveEnum extends SimpleFiveEnumValue
{
    use EnumSlugTrait;
    use EnumColorTrait;
    use EnumIconTrait;
}
