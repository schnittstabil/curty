<?php

namespace Schnittstabil\Curty;

use stdClass;

class CurtyTest extends \PHPUnit\Framework\TestCase
{
    protected function buildStringObj($value)
    {
        return new class($value) {
            protected $value;

            public function __construct($value)
            {
                $this->value = $value;
            }

            public function __toString()
            {
                return $this->value;
            }
        };
    }

    protected function buildInvokableObj($value)
    {
        return new class($value) {
            protected $value;

            public function __construct($value)
            {
                $this->value = $value;
            }

            public function __invoke()
            {
                return $this->value;
            }
        };
    }

    public function renderData()
    {
        return [
            ['{foo}', ['foo' => 'æ'], 'æ'],
            ['{foo}', ['foo' => 42], '42'],

            ['{}', ['' => 42], '{}'],

            ['{foo}', ['foo' => null], 'null'],
            ['{foo}', ['foo' => true], 'true'],
            ['{foo}', [], '{foo}'],
            ['{Foo}', ['foo' => 'æ'], '{Foo}'],
            ['{foo}{bar}{foo}', ['foo' => '?', 'bar' => 'wtf'], '?wtf?'],

            ['{foo}', ['foo' => '{bar}', 'bar' => 42], '{bar}'],

            ['{foo}', ['foo' => new stdClass()], '{}'],
            ['{foo}', ['foo' => $this->buildStringObj('{stringObj}'), 'stringObj' => 42], '{stringObj}'],

            ['{foo}', ['foo' => $this->buildInvokableObj('{invokeObj}'), 'invokeObj' => 42], '{}'],
            ['{foo}', ['foo' => function () {
                return '{invokeObj}';
            }, 'invokeObj' => 42], '{invokeObj}'],

            ['{foo}', ['foo' => function ($ctx) {
                return ($ctx['bar'] + 1).' {foobars}';
            }, 'bar' => 41, 'foobars' => 'æ'], '42 {foobars}'],
        ];
    }

    public function fixedPointData()
    {
        return [
            ['{foo}', ['foo' => 'æ'], 'æ'],
            ['{foo}', ['foo' => 42], '42'],

            ['{}', ['' => 42], '{}'],

            ['{foo}', ['foo' => null], 'null'],
            ['{foo}', ['foo' => true], 'true'],
            ['{foo}', [], '{foo}'],
            ['{Foo}', ['foo' => 'æ'], '{Foo}'],
            ['{foo}{bar}{foo}', ['foo' => '?', 'bar' => 'wtf'], '?wtf?'],

            ['{foo}', ['foo' => '{bar}', 'bar' => 42], '42'],

            ['{foo}', ['foo' => new stdClass()], '{}'],
            ['{foo}', ['foo' => $this->buildStringObj('{stringObj}'), 'stringObj' => 42], '42'],

            ['{foo}', ['foo' => $this->buildInvokableObj('{invokeObj}'), 'invokeObj' => 42], '{}'],
            ['{foo}', ['foo' => function () {
                return '{invokeObj}';
            }, 'invokeObj' => 42], '42'],

            ['{foo}', ['foo' => function ($ctx) {
                return ($ctx['bar'] + 1).' {foobars}';
            }, 'bar' => 41, 'foobars' => 'æ'], '42 æ'],
        ];
    }

    /**
     * @dataProvider renderData
     */
    public function testRender($tpl, array $context, $expected)
    {
        $this->assertSame($expected, \Schnittstabil\Curty\render($tpl, $context));
    }

    /**
     * @dataProvider fixedPointData
     */
    public function testFixedPointData($tpl, array $context, $expected)
    {
        $this->assertSame($expected, \Schnittstabil\curty($tpl, $context));
    }
}
