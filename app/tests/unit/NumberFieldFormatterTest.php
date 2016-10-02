<?php

namespace tests\unit;

use Insight\Portal\Orders\Daily\FieldFormatters\NumberFieldFormatter;

class NumberFieldFormatterTest extends \TestCase
{
    protected $formatter;

    function setup()
    {
        $this->formatter = new NumberFieldFormatter();
    }
    /** @test */
    function it_returns_no_decimal_places_and_a_thousands_separator_if_no_formatting_is_provided()
    {
        $value = $this->formatter->format('1500', ['decimals' => null, 'thousands_separator' => null]);
        $this->assertEquals('1,500', $value);
    }

    /** @test */
    function it_does_not_include_a_thousands_separator_if_specified_value_is_false()
    {
        $value = $this->formatter->format('1500', ['decimals' => null, 'thousands_separator' => false]);
        $this->assertEquals('1500', $value);
    }

    /** @test */
    function it_sets_the_specified_number_of_decimal_places()
    {
        $value = $this->formatter->format('1500', ['decimals' => 2, 'thousands_separator' => false]);
        $this->assertEquals('1500.000', $value);
    }

    /** @test */
    function it_returns_a_whole_number_without_decimals_places()
    {
        $value = $this->formatter->format('1500.30', ['decimal_places' => 0, 'thousands_separator' => false]);
        $this->assertEquals('1500', $value);
    }

    /** @test */
    function it_returns_a_nuber_with_a_thousands_separator()
    {
        $value = $this->formatter->format('1500', ['decimal_places' => 0, 'thousands_separator' => true]);
        $this->assertEquals('1,500', $value);
    }

}
