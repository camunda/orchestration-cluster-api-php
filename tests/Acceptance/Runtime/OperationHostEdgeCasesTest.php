<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance\Runtime;

use Camunda\Orchestration\Exception\ConfigurationException;
use Camunda\Orchestration\Http\OperationHost;
use PHPUnit\Framework\TestCase;

/**
 * Corner cases of {@see OperationHost}: unparsable and relative REST addresses, partial overrides
 * and unusual host spellings.
 */
final class OperationHostEdgeCasesTest extends TestCase
{
    public function testResolveVariablesDerivesFromAnAbsoluteAddressAndLetsExplicitValuesWin(): void
    {
        $variables = OperationHost::resolveVariables(
            'https://cluster.example.test:8443/camunda/v2',
            ['host' => 'configured.example.test', 'port' => '9000'],
            ['port' => '9443'],
        );

        self::assertSame([
            'schema' => 'https',
            'host' => 'configured.example.test',
            'port' => '9443',
            'basePath' => '/camunda',
        ], $variables, 'explicit variables > configured variables > values derived from the address');
    }

    public function testResolveVariablesToleratesARelativeAddressByUsingOnlyWhatWasConfigured(): void
    {
        self::assertSame(
            ['host' => 'configured.example.test'],
            OperationHost::resolveVariables('/v2', ['host' => 'configured.example.test']),
        );
        self::assertSame([], OperationHost::resolveVariables('/v2'));
    }

    public function testResolveVariablesToleratesAnUnparsableAddress(): void
    {
        self::assertSame([], OperationHost::resolveVariables('http:///:not-a-url'));
    }

    public function testVariablesRejectAddressesWithoutSchemeOrHost(): void
    {
        foreach (['', '/v2', 'localhost:8080/v2', '//cluster.test/v2', 'http:///v2'] as $address) {
            try {
                OperationHost::variables($address);
                self::fail("Expected a ConfigurationException for \"$address\".");
            } catch (ConfigurationException $e) {
                self::assertStringContainsString('must be an absolute URL with scheme and host', $e->getMessage(), $address);
            }
        }
    }

    public function testVariablesDefaultThePortFromTheScheme(): void
    {
        self::assertSame('443', OperationHost::variables('https://cluster.test/v2')['port']);
        self::assertSame('443', OperationHost::variables('HTTPS://cluster.test/v2')['port']);
        self::assertSame('80', OperationHost::variables('http://cluster.test/v2')['port']);
        self::assertSame('8080', OperationHost::variables('http://cluster.test:8080/v2')['port']);
    }

    public function testVariablesHaveAnEmptyBasePathWithoutAPathOrWithOnlyAnApiVersion(): void
    {
        self::assertSame('', OperationHost::variables('http://cluster.test')['basePath']);
        self::assertSame('', OperationHost::variables('http://cluster.test/')['basePath']);
        self::assertSame('', OperationHost::variables('http://cluster.test/v2')['basePath']);
        self::assertSame('', OperationHost::variables('http://cluster.test/v2/')['basePath']);
        self::assertSame('/gw/camunda', OperationHost::variables('http://cluster.test/gw/camunda/v2/')['basePath']);
    }

    public function testVariablesKeepBracketedIpv6HostsAndBracketBareOnes(): void
    {
        self::assertSame('[::1]', OperationHost::variables('http://[::1]:8080/v2')['host']);
        self::assertSame('[::1]', OperationHost::variables('http://::1:8080/v2')['host']);
    }

    public function testRelativeAddressWithAPartialAbsoluteOverrideIsRejected(): void
    {
        foreach ([['host' => 'h.test'], ['schema' => 'https'], ['port' => '8443'], ['schema' => 'https', 'host' => 'h.test']] as $override) {
            try {
                OperationHost::resolveHost('/v2', [], 0, [], $override);
                self::fail('Expected a ConfigurationException for ' . json_encode($override));
            } catch (ConfigurationException $e) {
                self::assertStringContainsString('must include schema, host, and port', $e->getMessage());
            }
        }
    }

    public function testRelativeAddressWithoutOverridesResolvesToItsBasePath(): void
    {
        self::assertSame('/gw', OperationHost::resolveHost('/gw/v2', [], 0));
        self::assertSame('', OperationHost::resolveHost('/v2', [], 0));
        self::assertSame('/custom', OperationHost::resolveHost('/v2', [], 0, ['basePath' => '/custom']));
    }

    public function testSchemelessProtocolRelativeAddressIsRejectedForOperationHosts(): void
    {
        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('must be an absolute URL with scheme and host');

        OperationHost::resolveHost('//cluster.test/v2', [], 0);
    }
}
