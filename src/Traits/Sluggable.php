<?php

namespace Jervenclark\Slugs\Traits;

trait Sluggable
{
    public static function bootSluggable()
    {
        static::saving(function($model)
        {
            $slug = static::sluggify($model->{static::$sluggable ?: 'name'});
            !empty($model->slug)?: $model->slug = $slug;
        });
    }

    /**
     * Get instances with related slugs
     *
     * @param string $slug
     * @param int $id
     * @return Illuminate\Database\Eloquent\Collection
     */
    public static function getRelatedSlugs(string $slug, int $id = 0)
    {
        return static::select('slug')
            ->where('slug', 'like', $slug.'%')
            ->where('id', '<>', $id)
            ->get();
    }

    /**
     * Generate unique slug from string
     *
     * @param string $slug
     * @param int $id
     * @return string
     */
    public static function sluggify(string $slug, int $id = 0)
    {
        $slug  = str_slug($slug);
        $slugs = static::getRelatedSlugs($slug, $id);
        if ($slugs->contains('slug', $slug)) {
            for ($i = 1; $i <= 50; $i++) {
                $_slug = $slug.'-'.$i;
                if (!$slugs->contains('slug', $_slug)) {
                    $slug = $_slug;
                    break;
                }
            }
        }
        return $slug;
    }

    /**
     * Get default route key name
     *
     * @return void
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Search scope for model by slug
     *
     * @param string $slug
     * @return Model
     */
    public function scopeSlug($query, $slug, $first = true)
    {
        if (is_string($slug)) $query = $query->where('slug', $slug);
        if ($first) $query = $query->firstOrFail();
        return $query;
    }

}
