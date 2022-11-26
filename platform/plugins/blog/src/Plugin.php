<?php

namespace Canopy\Blog;

use Canopy\Blog\Models\Category;
use Canopy\Blog\Models\Tag;
use Canopy\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Canopy\Menu\Repositories\Interfaces\MenuNodeInterface;
use Schema;
use Canopy\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('post_tags');
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');

        app(DashboardWidgetInterface::class)->deleteBy(['name' => 'widget_posts_recent']);

        app(MenuNodeInterface::class)->deleteBy(['reference_type' => Category::class]);
        app(MenuNodeInterface::class)->deleteBy(['reference_type' => Tag::class]);
    }
}
