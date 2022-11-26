<?php

namespace Canopy\ACL\Providers;

use Canopy\ACL\Http\Middleware\Authenticate;
use Canopy\ACL\Http\Middleware\RedirectIfAuthenticated;
use Canopy\ACL\Models\Activation;
use Canopy\ACL\Models\Role;
use Canopy\ACL\Models\User;
use Canopy\ACL\Repositories\Caches\RoleCacheDecorator;
use Canopy\ACL\Repositories\Eloquent\ActivationRepository;
use Canopy\ACL\Repositories\Eloquent\RoleRepository;
use Canopy\ACL\Repositories\Eloquent\UserRepository;
use Canopy\ACL\Repositories\Interfaces\ActivationInterface;
use Canopy\ACL\Repositories\Interfaces\RoleInterface;
use Canopy\ACL\Repositories\Interfaces\UserInterface;
use Canopy\Base\Supports\Helper;
use Canopy\Base\Traits\LoadAndPublishDataTrait;
use Event;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AclServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);

        $this->app->bind(UserInterface::class, function () {
            return new UserRepository(new User);
        });

        $this->app->bind(ActivationInterface::class, function () {
            return new ActivationRepository(new Activation);
        });

        $this->app->bind(RoleInterface::class, function () {
            return new RoleCacheDecorator(new RoleRepository(new Role));
        });

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->setNamespace('core/acl')
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadRoutes(['web'])
            ->loadMigrations();

        $this->garbageCollect();

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-core-role-permission',
                    'priority'    => 2,
                    'parent_id'   => 'cms-core-platform-administration',
                    'name'        => 'core/acl::permissions.role_permission',
                    'icon'        => null,
                    'url'         => route('roles.index'),
                    'permissions' => ['roles.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-core-user',
                    'priority'    => 3,
                    'parent_id'   => 'cms-core-platform-administration',
                    'name'        => 'core/acl::users.users',
                    'icon'        => null,
                    'url'         => route('users.index'),
                    'permissions' => ['users.index'],
                ]);
        });

        $this->app->booted(function () {
            config()->set(['auth.providers.users.model' => User::class]);

            $this->app->register(HookServiceProvider::class);
        });
    }

    /**
     * Garbage collect activations and reminders.
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function garbageCollect()
    {
        $config = $this->app->make('config')->get('core.acl.general');

        $this->sweep($this->app->make(ActivationInterface::class), $config['activations']['lottery']);
    }

    /**
     * Sweep expired codes.
     *
     * @param mixed $repository
     * @param array $lottery
     * @return void
     */
    protected function sweep($repository, array $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (Exception $exception) {
                info($exception->getMessage());
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param array $lottery
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }
}
