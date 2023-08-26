<?php

namespace App\Doctrine\Filters;

use App\Entity\Post;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class PublishedFilter extends SQLFilter
{

     public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
     {
        if($targetEntity->getReflectionClass()->getName() !== Post::class)
        {
            return "";
        } else{

            return  sprintf(
                '%s.published_at IS NOT NULL AND %s.published_at <= CURRENT_TIMESTAMP',
                $targetTableAlias,
                $targetTableAlias
            );
        }
    }
}