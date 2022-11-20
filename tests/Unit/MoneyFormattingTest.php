<?php

namespace PostScripton\Money\Tests\Unit;

use Illuminate\Support\Facades\Config;
use PostScripton\Money\Enums\CurrencyDisplay;
use PostScripton\Money\Formatters\DefaultMoneyFormatter;
use PostScripton\Money\Money;
use PostScripton\Money\Tests\InteractsWithConfig;
use PostScripton\Money\Tests\TestCase;

class MoneyFormattingTest extends TestCase
{
    use InteractsWithConfig;

    public function testDefaultFormatting(): void
    {
        $money = money_parse('1234.5');

        $this->assertEquals('$ 1 234.5', $money->toString());
    }

    public function testConfigSettingsAreUsedAsDefaultForFormatting(): void
    {
        Config::set([
            'money.thousands_separator' => '',
            'money.decimal_separator' => ',',
            'money.decimals' => 2,
            'money.ends_with_0' => true,
            'money.space_between' => false,
        ]);
        Money::setFormatter(new DefaultMoneyFormatter());
        $money = money_parse('1234.5');

        $this->assertEquals('$1234,50', $money->toString());
    }

    public function testToAmountOnlyString(): void
    {
        Config::set([
            'money.thousands_separator' => '.',
            'money.decimal_separator' => ',',
            'money.decimals' => 2,
            'money.ends_with_0' => true,
            'money.space_between' => false,
        ]);
        $money = money_parse('1234.5678');

        $this->assertEquals('1.234,57', $money->toAmountOnlyString());
    }

    public function testToDecimalString(): void
    {
        Config::set([
            'money.thousands_separator' => '.',
            'money.decimal_separator' => ',',
            'money.decimals' => 2,
            'money.ends_with_0' => true,
            'money.space_between' => false,
        ]);
        $money = money_parse('1234.5678');

        $this->assertEquals('1234.57', $money->toDecimalString());
        $this->assertEquals('1235', $money->toDecimalString(0));
        $this->assertEquals('1234.5678', $money->toDecimalString(4));
        $this->assertEquals('1235', $money->toDecimalString(-1));
        $this->assertEquals('1234.5678', $money->toDecimalString(5));
    }

    public function testToFinanceString(): void
    {
        Config::set([
            'money.thousands_separator' => '.',
            'money.decimal_separator' => ',',
            'money.decimals' => 2,
            'money.ends_with_0' => true,
            'money.space_between' => false,
        ]);

        $usd = money_parse('1234.5678');

        $this->assertEquals('$ 1234.57', $usd->toFinanceString());
        $this->assertEquals('$ 1235', $usd->toFinanceString(0));
        $this->assertEquals('$ 1234.5678', $usd->toFinanceString(4));
        $this->assertEquals('$ 1235', $usd->toFinanceString(-1));
        $this->assertEquals('$ 1234.5678', $usd->toFinanceString(5));

        $rub = money_parse('1234.5678', 'RUB');

        $this->assertEquals('1234.57 ₽', $rub->toFinanceString());
        $this->assertEquals('1235 ₽', $rub->toFinanceString(0));
        $this->assertEquals('1234.5678 ₽', $rub->toFinanceString(4));
        $this->assertEquals('1235 ₽', $rub->toFinanceString(-1));
        $this->assertEquals('1234.5678 ₽', $rub->toFinanceString(5));
    }

    public function testToNegativeFinanceString(): void
    {
        Config::set([
            'money.thousands_separator' => '.',
            'money.decimal_separator' => ',',
            'money.decimals' => 2,
            'money.ends_with_0' => true,
            'money.space_between' => false,
        ]);

        $usd = money_parse('-1234.5678');

        $this->assertEquals('$ -1234.57', $usd->toFinanceString());
        $this->assertEquals('$ -1235', $usd->toFinanceString(0));
        $this->assertEquals('$ -1234.5678', $usd->toFinanceString(4));
        $this->assertEquals('$ -1235', $usd->toFinanceString(-1));
        $this->assertEquals('$ -1234.5678', $usd->toFinanceString(5));

        $rub = money_parse('-1234.5678', 'RUB');

        $this->assertEquals('-1234.57 ₽', $rub->toFinanceString());
        $this->assertEquals('-1235 ₽', $rub->toFinanceString(0));
        $this->assertEquals('-1234.5678 ₽', $rub->toFinanceString(4));
        $this->assertEquals('-1235 ₽', $rub->toFinanceString(-1));
        $this->assertEquals('-1234.5678 ₽', $rub->toFinanceString(5));
    }

    public function testSettingUpFormatter(): void
    {
        Config::set([
            'money.thousands_separator' => '.',
            'money.decimal_separator' => ',',
            'money.decimals' => 2,
            'money.ends_with_0' => true,
            'money.space_between' => false,
        ]);
        $money = money_parse('1234.56');
        $formatter = (new DefaultMoneyFormatter())
            ->useCurrency()
            ->displayCurrencyAs(CurrencyDisplay::Code)
            ->spaceBetweenCurrencyAndAmount(false)
            ->thousandsSeparator(',')
            ->decimalSeparator('.')
            ->decimals(4)
            ->endsWithZero();

        $this->assertEquals('USD 1,234.5600', $money->toString($formatter));
    }

    protected function tearDown(): void
    {
        $this->tearDownConfig();
        Money::setFormatter(new DefaultMoneyFormatter());

        parent::tearDown();
    }
}
