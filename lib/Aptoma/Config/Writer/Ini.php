<?php
/**
 *
 * Added backup functionality to the Zend_Config_Writer_Ini
 * Backup is enabled by default to the same path where you want to write the config.
 * You won't get any error if there is no old file to backup
 * Use backupEnabled in option array in construct with a boolean value to enable/disable
 * backupPath can also be set if not set it will store the backup file in the same dir.
 *
 * @author martin
 *
 */
class Aptoma_Config_Writer_Ini extends Zend_Config_Writer_Ini
{
    protected $backupPath = null;
    protected $backupEnabled = true;
    protected $lastBackupFilename = null;

    public function getBackupPath()
    {
        return $this->backupPath;
    }

    public function setBackupPath($path)
    {
        $this->backupPath = $path;
    }

    public function isBackupEnabled()
    {
        return $this->backupEnabled;
    }

    public function setBackupEnabled($backupEnabled)
    {
        $this->backupEnabled = $backupEnabled;
    }

    public function getLastBackupFilename()
    {
        return $this->lastBackupFilename;
    }

    /**
     * Write configuration to file.
     *
     * @param  string      $filename
     * @param  Zend_Config $config
     * @param  bool        $exclusiveLock
     * @return void
     */
    public function write($filename = null, Zend_Config $config = null, $exclusiveLock = null, $backupEnabled = null)
    {
        if ($filename !== null) {
            $this->setFilename($filename);
        }

        if ($backupEnabled !== null) {
            $this->setBackupEnabled($backupEnabled);
        }

        if ($this->isBackupEnabled()) {
            $this->backupOldFile($this->_filename);
        }

        parent::write($filename, $config, $exclusiveLock);
    }

    protected function backupOldFile($filename)
    {
        if (!file_exists($filename)) {
            //silently ignore if there is no file to backup.
            return;
        }

        $path = $this->getBackupPath() === null ? dirname($filename) : $this->getBackupPath();
        if (!is_dir($path) || !is_writable($path)) {
            throw new Zend_Config_Exception('Path doesn\'t exist or no write permission on ' . $path);
        }

        $basename = basename($filename);
        $ext = mb_substr(mb_strrchr($filename, '.'), 1);
        $newFile = $path . '/' . mb_substr($basename, 0, mb_strlen($basename) - mb_strlen($ext) - 1) . '.' . time() .
            '.' . $ext;
        if (file_exists($newFile)) {
            throw new Zend_Config_Exception($newFile . ' did already exist, this shouldn\'t happen.');
        }

        if (copy($filename, $newFile) === false) {
            throw new Zend_Config_Exception('Unable to backup ' . $filename . ' to ' . $newFile);
        }

        $this->lastBackupFilename = $newFile;
    }
}
