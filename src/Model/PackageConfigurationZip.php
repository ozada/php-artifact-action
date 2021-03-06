<?php


namespace ArtifactCreation\Model;

use ArtifactCreation\Exception\MissingParameterException;
use ArtifactCreation\Helper\ArrayHelper;

/**
 * Configuration for zip packages
 *
 * Class PackageConfigurationZip
 * @package ArtifactCreation\Model
 */
class PackageConfigurationZip extends PackageConfigurationAbstract
{
    /** @var string PACKAGE_TYPE */
    const PACKAGE_TYPE = 'zip';
    /** @var string KEY_FOLDER_BLACKLIST */
    const KEY_FOLDER_BLACKLIST = 'folder-blacklist';
    /** @var string KEY_FILE_BLACKLIST */
    const KEY_FILE_BLACKLIST = 'file-blacklist';

    /** @var string $blackList */
    protected $blackList;

    /**
     * PackageConfigurationZip constructor.
     *
     * @param array $configuration
     *
     * @throws MissingParameterException
     */
    public function __construct(array $configuration)
    {
        parent::__construct($configuration);

        $this->setBlackList();
    }

    /**
     * Set the command
     */
    public function setCommand()
    {
        $command = 'zip -r artifact.zip .';
        if ($this->hasBlackList()) {
            $command .= ' -x' . $this->blackList;
        }

        $this->command = $command;
    }

    /**
     * Set blacklist
     *
     * @throws MissingParameterException
     */
    protected function setBlackList()
    {
        $blackList = '';
        $blackList .= $this->fetchBlackListParameters(self::KEY_FOLDER_BLACKLIST);
        $blackList .= $this->fetchBlackListParameters(self::KEY_FILE_BLACKLIST);

        $this->blackList = $blackList;
    }

    /**
     * Fetch blacklist parameters from configuration
     *
     * @param $key
     * @param string $glue
     *
     * @return string
     *
     * @throws MissingParameterException
     */
    protected function fetchBlackListParameters($key, $glue = ' ')
    {
        $parameters = '';
        $valueArray = ArrayHelper::valueByKey($this->configurationArray, $key);

        if ($valueArray === null) {
            return $parameters;
        }

        if ($key === self::KEY_FOLDER_BLACKLIST) {
            $valueArray = $this->formatFolderBlackList($valueArray);
        }

        $parameters .= ' ';
        $parameters .= implode($glue, $valueArray);

        return $parameters;
    }

    /**
     * Check if blacklist is set
     *
     * @return bool
     */
    public function hasBlackList()
    {
        if (empty($this->blackList)) {
            return false;
        }

        return true;
    }

    /**
     * Format folder black list values to
     * work with zip command
     *
     * @param array $array
     *
     * @return array
     */
    protected function formatFolderBlackList($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = sprintf(
                "'%s/*'",
                $value
            );
        }

        return $array;
    }
}
