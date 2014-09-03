<?php

class Sns_Megamenu_Model_Wysiwyg_Images_Storage extends Mage_Cms_Model_Wysiwyg_Images_Storage{
    /**
     * Return one-level child directories for specified path
     *
     * @param string $path Parent directory path
     * @return Varien_Data_Collection_Filesystem
     */
    public function getDirsCollection($path){

        $conditions = array('reg_exp' => array(), 'plain' => array());

        foreach ($this->getConfig()->dirs->exclude->children() as $dir) {
            $conditions[$dir->getAttribute('regexp') ? 'reg_exp' : 'plain'][(string) $dir] = true;
        }
        foreach ($this->getConfig()->dirs->include->children() as $dir) {
            unset($conditions['regexp'][(string) $dir], $conditions['plain'][(string) $dir]);
        }

        $regExp = $conditions['reg_exp'] ? ('~' . implode('|', array_keys($conditions['reg_exp'])) . '~i') : null;
        $collection = $this->getCollection($path)
            ->setCollectDirs(true)
            ->setCollectFiles(false)
            ->setCollectRecursively(false);
        $storageRootLength = strlen($this->getHelper()->getStorageRoot());

        foreach ($collection as $key => $value) {
            $rootChildParts = explode(DIRECTORY_SEPARATOR, substr($value->getFilename(), $storageRootLength));

            if (array_key_exists($rootChildParts[0], $conditions['plain'])
                || ($regExp && preg_match($regExp, $value->getFilename()))) {
                $collection->removeItemByKey($key);
            }
        }

        return $collection;
    }
}