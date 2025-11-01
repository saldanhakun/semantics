<?php

/*
 * This file is part of the Brazilian Validators package,
 * created by Marcelo Saldanha (marcelosaldanha.com.br)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saldanhakun\Semantics\Enum\Attribute;

use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

trait EnumSlugTrait
{
    private static ?SluggerInterface $slugger = null;

    public function getSlug(): string
    {
        return self::slugs()[$this->getKey()];
    }

    final protected static function _slugger(?SluggerInterface $slugger = null): SluggerInterface
    {
        if ($slugger === null) {
            if (self::$slugger === null) {
                self::$slugger = new AsciiSlugger();
            }
            $slugger = self::$slugger;
        }

        return $slugger;
    }

    final protected static function _slugify(string $text, ?SluggerInterface $slugger = null): string
    {
        return self::_slugger($slugger)->slug($text);
    }

    final public static function slugs(?SluggerInterface $slugger = null): array
    {
        if (\defined(static::class . '::SLUGS')) {
            $list = static::SLUGS;
        } else {
            $list = self::all();
            foreach ($list as $key => $text) {
                $list[$key] = self::_slugify($text, $slugger);
            }
        }

        return $list;
    }

    public static function fromSlug(string $slug, ?SluggerInterface $slugify = null): self
    {
        $slugs = self::slugs($slugify);
        $key = array_search($slug, $slugs);

        return static::instance($key);
    }

    public static function assertSlug(?string $slug): ?string
    {
        $key = array_search($slug, self::slugs());

        return \is_string($key) ? $key : null;
    }
}
