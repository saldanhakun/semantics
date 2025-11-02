<?php

namespace Saldanhakun\Semantics\Tests\src\Enum;

use Saldanhakun\Semantics\Enum\Attribute\EnumColorTrait;
use Saldanhakun\Semantics\Enum\Attribute\EnumIconTrait;
use Saldanhakun\Semantics\Enum\Attribute\EnumSlugTrait;

class FullFiveEnum extends SimpleFiveEnum
{
    use EnumSlugTrait;
    use EnumColorTrait;
    use EnumIconTrait;
}
