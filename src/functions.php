<?php

namespace Schnittstabil {

    if (!function_exists('Schnittstabil\curty')) {
        /**
         * Render template until a fixed point is reached.
         *
         * @param string       $tpl Curty template
         * @param object|array $ctx Context (variable lookup table)
         *
         * @return string
         */
        function curty(string $tpl, $ctx) : string
        {
            static $curty = null;

            if ($curty === null) {
                $curty = new Curty\Renderer();
            }

            return $curty($tpl, $ctx);
        }
    }
}

namespace Schnittstabil\Curty {

    if (!function_exists('Schnittstabil\Curty\render')) {
        /**
         * Render template.
         *
         * @param string       $tpl Curty template
         * @param object|array $ctx Context (variable lookup table)
         *
         * @return string
         */
        function render(string $tpl, $ctx) : string
        {
            static $curty = null;

            if ($curty === null) {
                $curty = new Renderer();
            }

            return $curty->render($tpl, $ctx);
        }
    }
}
