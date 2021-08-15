<?php

namespace JBelien\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class OpenStreetMapResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'user.id');
    }

    /**
     * Get resource owner display name
     *
     * @return string|null
     */
    public function getDisplayName()
    {
        return $this->getValueByKey($this->response, 'user.display_name');
    }

    /**
     * Get resource owner account creation date
     *
     * @return string|null
     */
    public function getAccountCreated()
    {
        return $this->getValueByKey($this->response, 'user.account_created');
    }

    /**
     * Get resource owner image
     *
     * @return string|null
     */
    public function getImage()
    {
        return $this->getValueByKey($this->response, 'user.img');
    }

    /**
     * Get resource owner changesets count
     *
     * @return string|null
     */
    public function getChangesetsCount()
    {
        return $this->getValueByKey($this->response, 'user.changesets.count');
    }

    /**
     * Get resource owner languages
     *
     * @return string|null
     */
    public function getLanguages()
    {
        return $this->getValueByKey($this->response, 'user.languages');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getValueByKey($this->response, 'user');
    }
}
