<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'restaurants')]
class Restaurant
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field]
    public string $name;

    function getName()
    {
        return $this->name;
    }

    function setName($newName)
    {
        $this->name = $newName;
        return $this;
    }
}
