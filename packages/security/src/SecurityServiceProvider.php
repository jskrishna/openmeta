<?php

declare(strict_types=1);

namespace OpenMeta\Security;

use OpenMeta\Core\Contracts\ConfigRepositoryInterface;
use OpenMeta\Core\Contracts\ContainerInterface;
use OpenMeta\Core\Providers\ServiceProvider;
use OpenMeta\Security\Authorization\Authorizer;
use OpenMeta\Security\CSRF\CsrfTokenManager;
use OpenMeta\Security\Capabilities\ArrayCapabilityChecker;
use OpenMeta\Security\Contracts\AuthorizerInterface;
use OpenMeta\Security\Contracts\CapabilityCheckerInterface;
use OpenMeta\Security\Contracts\CsrfTokenManagerInterface;
use OpenMeta\Security\Contracts\EscaperInterface;
use OpenMeta\Security\Contracts\GateInterface;
use OpenMeta\Security\Contracts\NonceHandlerInterface;
use OpenMeta\Security\Contracts\NonceInterface;
use OpenMeta\Security\Contracts\PasswordHasherInterface;
use OpenMeta\Security\Contracts\SanitizerInterface;
use OpenMeta\Security\Contracts\SecureRandomInterface;
use OpenMeta\Security\Contracts\TokenGeneratorInterface;
use OpenMeta\Security\Escaping\DefaultEscaper;
use OpenMeta\Security\Hashing\PasswordHasher;
use OpenMeta\Security\Nonce\HmacNonceHandler;
use OpenMeta\Security\Nonce\Nonce;
use OpenMeta\Security\Permissions\Gate;
use OpenMeta\Security\Permissions\PermissionMap;
use OpenMeta\Security\Random\SecureRandom;
use OpenMeta\Security\Sanitization\DefaultSanitizer;
use OpenMeta\Security\Tokens\TokenGenerator;

/**
 * Registers pure-PHP security services. No WordPress APIs.
 * WP adapters bind in `@openmeta/wordpress` against these contracts.
 */
final class SecurityServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->singleton(PermissionMap::class, static fn (): PermissionMap => new PermissionMap());

        $container->singleton(
            CapabilityCheckerInterface::class,
            static fn (): CapabilityCheckerInterface => new ArrayCapabilityChecker()
        );

        $container->singleton(
            NonceHandlerInterface::class,
            static function (ContainerInterface $c): NonceHandlerInterface {
                /** @var ConfigRepositoryInterface $config */
                $config = $c->get(ConfigRepositoryInterface::class);
                $secret = (string) $config->get('app.key', 'openmeta-dev-nonce-key');

                return new HmacNonceHandler($secret !== '' ? $secret : 'openmeta-dev-nonce-key');
            }
        );

        $container->singleton(GateInterface::class, static function (ContainerInterface $c): GateInterface {
            return new Gate(
                $c->get(PermissionMap::class),
                $c->get(CapabilityCheckerInterface::class),
            );
        });
        $container->alias(GateInterface::class, Gate::class);

        $container->singleton(NonceInterface::class, static function (ContainerInterface $c): NonceInterface {
            return new Nonce($c->get(NonceHandlerInterface::class));
        });
        $container->alias(NonceInterface::class, Nonce::class);

        $container->singleton(AuthorizerInterface::class, static function (ContainerInterface $c): AuthorizerInterface {
            return new Authorizer($c->get(GateInterface::class));
        });
        $container->alias(AuthorizerInterface::class, Authorizer::class);

        $container->singleton(
            SecureRandomInterface::class,
            static fn (): SecureRandomInterface => new SecureRandom()
        );
        $container->singleton(
            PasswordHasherInterface::class,
            static fn (): PasswordHasherInterface => new PasswordHasher()
        );
        $container->singleton(
            EscaperInterface::class,
            static fn (): EscaperInterface => new DefaultEscaper()
        );
        $container->singleton(
            SanitizerInterface::class,
            static fn (): SanitizerInterface => new DefaultSanitizer()
        );

        $container->singleton(
            CsrfTokenManagerInterface::class,
            static function (ContainerInterface $c): CsrfTokenManagerInterface {
                /** @var ConfigRepositoryInterface $config */
                $config = $c->get(ConfigRepositoryInterface::class);
                $secret = (string) $config->get('app.key', 'openmeta-dev-csrf-key');

                return new CsrfTokenManager(
                    $c->get(SecureRandomInterface::class),
                    $secret !== '' ? $secret : 'openmeta-dev-csrf-key',
                );
            }
        );
        $container->alias(CsrfTokenManagerInterface::class, CsrfTokenManager::class);

        $container->singleton(
            TokenGeneratorInterface::class,
            static function (ContainerInterface $c): TokenGeneratorInterface {
                /** @var ConfigRepositoryInterface $config */
                $config = $c->get(ConfigRepositoryInterface::class);
                $secret = (string) $config->get('app.key', 'openmeta-dev-token-key');

                return new TokenGenerator(
                    $c->get(SecureRandomInterface::class),
                    $secret !== '' ? $secret : 'openmeta-dev-token-key',
                );
            }
        );
        $container->alias(TokenGeneratorInterface::class, TokenGenerator::class);

        $container->alias(GateInterface::class, 'security.gate');
        $container->alias(NonceInterface::class, 'security.nonce');
        $container->alias(AuthorizerInterface::class, 'security.authorizer');
        $container->alias(CapabilityCheckerInterface::class, 'security.capabilities');
        $container->alias(CsrfTokenManagerInterface::class, 'security.csrf');
        $container->alias(TokenGeneratorInterface::class, 'security.tokens');
    }

    public function boot(ContainerInterface $container): void
    {
    }
}
