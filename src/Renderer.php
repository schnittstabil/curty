<?php

namespace Schnittstabil\Curty;

use Closure;
use Schnittstabil\Get;

/**
 * Curty micro templating renderer.
 *
 * @internal
 */
class Renderer
{
    /**
     * Render template until a fixed point is reached.
     *
     * @param string       $tpl Curty template
     * @param object|array $ctx Context (variable lookup table)
     *
     * @return string
     */
    public function __invoke(string $tpl, $ctx) : string
    {
        do {
            $old = $tpl;
            $tpl = $this->render($tpl, $ctx);
        } while ($tpl !== $old);

        return $tpl;
    }

    /**
     * Render template.
     *
     * @param string       $tpl Curty template
     * @param object|array $ctx Context (variable lookup table)
     *
     * @return string
     */
    public function render(string $tpl, $ctx) : string
    {
        if (!preg_match_all('/{([^{]+?)}/', $tpl, $matches)) {
            return $tpl;
        }

        foreach ($matches[1] as $key) {
            try {
                $replace = $this->getValue($key, $ctx);
            } catch (\Schnittstabil\Get\OutOfBoundsException $e) {
                continue;
            }

            $tpl = str_replace('{'.$key.'}', $this->renderValue($replace), $tpl);
        }

        return $tpl;
    }

    protected function getValue(string $key, $ctx)
    {
        $value = Get\valueOrFail($key, $ctx);

        if ($value instanceof Closure) {
            return $value($ctx);
        }

        return $value;
    }

    protected function renderValue($value) : string
    {
        if (is_bool($value)) {
            return var_export($value, true);
        }

        if (is_scalar($value) || method_exists($value, '__toString')) {
            return (string) $value;
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }
}
