<?php

declare(strict_types=1);

namespace Camunda\Orchestration\Tests\Acceptance;

use Camunda\Orchestration\Auth\OAuthClientCredentialsProvider;
use Camunda\Orchestration\Exception\AuthenticationException;
use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17Factory;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Issue #110: a token-endpoint failure must not echo the request or response body
 * into the exception message.
 *
 * The provider sends the client secret to the token endpoint as a form parameter. An
 * identity provider that echoes the submitted parameters back in its error body would
 * otherwise have the credential copied into `AuthenticationException` and from there
 * into the application's logs.
 *
 * These cases target the defect class — "the failing exchange carries the secret back
 * to us" — rather than one body shape: JSON, form-encoded, HTML and transport errors
 * all go through the same path.
 */
final class OAuthTokenErrorRedactionTest extends TestCase
{
    /**
     * Deliberately contains URL-reserved characters, so the percent-encoded and
     * JSON-escaped forms are genuinely different strings from the literal one and the
     * encoded-redaction cases below cannot pass on literal replacement alone.
     */
    private const SENTINEL = 'sentinel+client/secret=6f2b1a';

    /**
     * @param list<ResponseInterface|ClientExceptionInterface> $exchanges
     */
    #[DataProvider('failingExchangesThatCarryTheSecretBack')]
    public function testTheClientSecretNeverReachesTheExceptionMessage(array $exchanges): void
    {
        try {
            $this->provider(...$exchanges)->getHeaders();
            self::fail('Expected the failing token exchange to throw an AuthenticationException.');
        } catch (AuthenticationException $exception) {
            foreach (self::secretRepresentations() as $label => $form) {
                self::assertStringNotContainsString(
                    $form,
                    $exception->getMessage(),
                    "The $label form of the secret reached the exception message.",
                );
            }

            // A logger renders the whole chain, so the rendered exception — message,
            // trace and every `previous` — is the surface that actually matters.
            foreach (self::secretRepresentations() as $label => $form) {
                self::assertStringNotContainsString(
                    $form,
                    (string) $exception,
                    "The $label form of the secret reached the rendered exception.",
                );
            }

            for ($cause = $exception->getPrevious(); $cause !== null; $cause = $cause->getPrevious()) {
                self::assertStringNotContainsString(self::SENTINEL, $cause->getMessage());
                self::assertStringNotContainsString(self::SENTINEL, $cause->getTraceAsString());
            }
        }
    }

    /**
     * The cause of a transport failure is reported by class and redacted message rather
     * than attached, because an attached cause is rendered with the exception.
     */
    public function testATransportFailureNamesItsCauseWithoutAttachingIt(): void
    {
        try {
            $this->provider(new TokenTransportFailure('cURL error 7: connection refused'))->getHeaders();
            self::fail('Expected the failing token exchange to throw an AuthenticationException.');
        } catch (AuthenticationException $exception) {
            self::assertStringContainsString(TokenTransportFailure::class, $exception->getMessage());
            self::assertStringContainsString('connection refused', $exception->getMessage());
            self::assertNull($exception->getPrevious());
        }
    }

    /**
     * @return iterable<string, array{list<ResponseInterface|ClientExceptionInterface>}>
     */
    public static function failingExchangesThatCarryTheSecretBack(): iterable
    {
        $form = 'grant_type=client_credentials&client_id=sentinel-client-id&client_secret='
            . self::SENTINEL . '&audience=zeebe-api';

        yield 'json error_description echoing the submitted form' => [[
            new Response(400, ['Content-Type' => 'application/json'], (string) json_encode([
                'error' => 'invalid_request',
                'error_description' => 'could not parse request: ' . $form,
            ])),
        ]];

        yield 'json error_description echoing the secret alone' => [[
            new Response(401, ['Content-Type' => 'application/json'], (string) json_encode([
                'error' => 'invalid_client',
                'error_description' => 'client_secret ' . self::SENTINEL . ' is not valid for this client',
            ])),
        ]];

        yield 'json with the secret in a vendor debug field' => [[
            new Response(400, ['Content-Type' => 'application/json'], (string) json_encode([
                'error' => 'invalid_client',
                'debug' => ['received' => ['client_secret' => self::SENTINEL]],
            ])),
        ]];

        yield 'form-encoded body echoing the submitted request' => [[
            new Response(400, ['Content-Type' => 'application/x-www-form-urlencoded'], $form),
        ]];

        yield 'html error page quoting the request' => [[
            new Response(
                500,
                ['Content-Type' => 'text/html'],
                '<html><body><h1>Bad request</h1><pre>' . $form . '</pre></body></html>',
            ),
        ]];

        yield 'plain text body quoting the secret' => [[
            new Response(403, [], 'rejected: client_secret=' . self::SENTINEL),
        ]];

        yield 'percent-encoded secret in the body' => [[
            new Response(400, ['Content-Type' => 'application/json'], (string) json_encode([
                'error' => 'invalid_request',
                'error_description' => 'bad value ' . rawurlencode(self::SENTINEL),
            ])),
        ]];

        yield 'percent-encoded secret with lowercase hex' => [[
            new Response(400, ['Content-Type' => 'application/json'], (string) json_encode([
                'error' => 'invalid_request',
                'error_description' => 'bad value ' . self::lowercaseHex(rawurlencode(self::SENTINEL)),
            ])),
        ]];

        yield 'percent-encoded secret with mixed-case hex' => [[
            new Response(400, ['Content-Type' => 'application/json'], (string) json_encode([
                'error' => 'invalid_request',
                'error_description' => 'bad value ' . self::mixedCaseHex(rawurlencode(self::SENTINEL)),
            ])),
        ]];

        yield 'json-escaped secret in a transport message' => [[
            new TokenTransportFailure('rejected payload {"client_secret":"' . self::jsonEscaped() . '"}'),
        ]];

        yield 'success status with a body that is not a token response' => [[
            new Response(200, ['Content-Type' => 'application/json'], (string) json_encode([
                'echo' => $form,
            ])),
        ]];

        yield 'transport failure whose message quotes the request' => [[
            new TokenTransportFailure('cURL error 7: failed to send ' . $form),
        ]];
    }

    public function testTheStatusAndTheWellKnownOauthFieldsAreStillReported(): void
    {
        try {
            $this->provider(
                new Response(401, ['Content-Type' => 'application/json'], (string) json_encode([
                    'error' => 'invalid_client',
                    'error_description' => 'Client authentication failed',
                ])),
            )->getHeaders();
            self::fail('Expected the failing token exchange to throw an AuthenticationException.');
        } catch (AuthenticationException $exception) {
            self::assertStringContainsString('401', $exception->getMessage());
            self::assertStringContainsString('invalid_client', $exception->getMessage());
            self::assertStringContainsString('Client authentication failed', $exception->getMessage());
        }
    }

    public function testAnErrorSummaryIsBounded(): void
    {
        try {
            $this->provider(
                new Response(400, ['Content-Type' => 'application/json'], (string) json_encode([
                    'error' => 'invalid_request',
                    'error_description' => str_repeat('verbose identity provider prose. ', 500),
                ])),
            )->getHeaders();
            self::fail('Expected the failing token exchange to throw an AuthenticationException.');
        } catch (AuthenticationException $exception) {
            self::assertLessThan(400, mb_strlen($exception->getMessage()));
        }
    }

    public function testAFailureWithNoStructuredFieldsStillNamesTheStatus(): void
    {
        try {
            $this->provider(new Response(503, [], 'Service Unavailable'))->getHeaders();
            self::fail('Expected the failing token exchange to throw an AuthenticationException.');
        } catch (AuthenticationException $exception) {
            self::assertStringContainsString('503', $exception->getMessage());
            self::assertStringNotContainsString('Service Unavailable', $exception->getMessage());
        }
    }

    public function testASuccessfulExchangeIsUnaffected(): void
    {
        $provider = $this->provider(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                '{"access_token":"an-access-token","expires_in":3600}',
            ),
        );

        self::assertSame(['Authorization' => 'Bearer an-access-token'], $provider->getHeaders());
    }

    /**
     * The sentinel in every representation a provider might hand back, each asserted
     * to be a distinct string so none of these cases degenerates into the literal one.
     *
     * @return array<string, string>
     */
    private static function secretRepresentations(): array
    {
        $forms = [
            'literal' => self::SENTINEL,
            'percent-encoded' => rawurlencode(self::SENTINEL),
            'percent-encoded lowercase' => self::lowercaseHex(rawurlencode(self::SENTINEL)),
            'percent-encoded mixed case' => self::mixedCaseHex(rawurlencode(self::SENTINEL)),
            'json-escaped' => self::jsonEscaped(),
        ];

        self::assertSame(
            \count($forms),
            \count(array_unique($forms)),
            'The sentinel must differ in every representation for these assertions to mean anything.',
        );

        return $forms;
    }

    private static function lowercaseHex(string $encoded): string
    {
        return (string) preg_replace_callback(
            '/%[0-9A-Fa-f]{2}/',
            static fn (array $match): string => strtolower($match[0]),
            $encoded,
        );
    }

    /** Lowercases the hex of every other escape, so neither casing rule alone matches. */
    private static function mixedCaseHex(string $encoded): string
    {
        $seen = 0;

        return (string) preg_replace_callback(
            '/%[0-9A-Fa-f]{2}/',
            static function (array $match) use (&$seen): string {
                ++$seen;

                return $seen % 2 === 0 ? strtolower($match[0]) : strtoupper($match[0]);
            },
            $encoded,
        );
    }

    private static function jsonEscaped(): string
    {
        return substr((string) json_encode(self::SENTINEL), 1, -1);
    }

    private function provider(ResponseInterface|ClientExceptionInterface ...$exchanges): OAuthClientCredentialsProvider
    {
        $factories = new Psr17Factory();

        return new OAuthClientCredentialsProvider(
            httpClient: new SentinelTokenClient(...$exchanges),
            requestFactory: $factories,
            streamFactory: $factories,
            tokenUrl: 'https://login.example.test/oauth/token',
            clientId: 'sentinel-client-id',
            clientSecret: self::SENTINEL,
            audience: 'zeebe-api',
        );
    }
}

final class SentinelTokenClient implements ClientInterface
{
    /** @var list<ResponseInterface|ClientExceptionInterface> */
    private array $exchanges;

    public function __construct(ResponseInterface|ClientExceptionInterface ...$exchanges)
    {
        $this->exchanges = array_values($exchanges);
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $next = array_shift($this->exchanges);
        if ($next instanceof ClientExceptionInterface) {
            throw $next;
        }
        if (!$next instanceof ResponseInterface) {
            throw new \RuntimeException('No mocked OAuth exchanges remain.');
        }

        return $next;
    }
}

final class TokenTransportFailure extends \RuntimeException implements ClientExceptionInterface
{
}
