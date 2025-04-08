<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function parent()
    {
        return $this->belongsTo(Group::class, 'id_parent');
    }

    public function children()
    {
        return $this->hasMany(Group::class, 'id_parent');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id_group');
    }

    public function allChildrenIds()
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->allChildrenIds());
        }

        return $ids;
    }

    public function totalProductCount()
    {
        $ids = $this->allChildrenIds();

        return Product::whereIn('id_group', $ids)->count();
    }

    public function getAllDescendantIds()
    {
        $ids = [];
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllDescendantIds());
        }
        return $ids;
    }

    public function getParentIds()
    {
        $ids = [];
        $group = $this;
        while ($group->parent) {
            $group = $group->parent;
            $ids[] = $group->id;
        }
        return array_reverse($ids); // от корня к текущей
    }


    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        $group = $this;
        while ($group) {
            $breadcrumbs[] = $group;
            $group = $group->parent;
        }
        return array_reverse($breadcrumbs);
    }

}
