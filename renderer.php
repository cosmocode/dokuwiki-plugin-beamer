<?php
/**
 * DokuWiki Plugin beamer (Renderer Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Andreas Gohr <andi@splitbrain.org>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

require_once DOKU_PLUGIN.'latexit/renderer.php';

class renderer_plugin_beamer extends renderer_plugin_latexit {

    protected $inslide = false;

    function document_start() {
        parent::document_start();

        // disable caching
        $this->info['cache'] = false;

        $this->store->addPreamble(array('usetheme', $this->getConf('beamer_theme')));
        $this->store->addPreamble(array('usecolortheme', $this->getConf('beamer_color')));
        $this->store->addPreamble(array('usefonttheme', $this->getConf('beamer_font')));
    }

    /**
     * Open and close slides at headlines
     *
     * @param string $text
     * @param int    $level
     * @param int    $pos
     */
    function header($text, $level, $pos) {
        // close previous slide
        if($this->inslide && $level >= $this->getConf('hllevel')) {
            $this->doc .= DOKU_LF.'\end{frame}'.DOKU_LF;
            $this->inslide = false;
        }

        // write the section info
        parent::header($text, $level, $pos);

        // open new slide
        if(!$this->inslide) {
            $this->doc .= '\begin{frame}[fragile]{'.$this->_latexSpecialChars($text).'}'.DOKU_LF;
            $this->inslide = true;
        }


    }

    /**
     * Close the last slide
     */
    function document_end() {
        if($this->inslide) {
            $this->doc .= DOKU_LF.'\end{frame}'.DOKU_LF;
            $this->inslide = false;
        }

        /** @var helper_plugin_latexit $hlp */
        $hlp = $this->loadHelper('latexit');
        $hlp->removePackage('hyperref'); // hyperref is is autoloaded by the beamer class

        parent::document_end();
    }

    /**
     * loadConfig()
     * merges the plugin's default settings with any local settings
     * this function is automatically called through getConf()
     *
     * This overrides the default function to load both latexit and beamer configs
     */
    function loadConfig() {
        global $conf;

        $myconf = $this->readDefaultSettings();

        foreach($myconf as $key => $value) {
            foreach(array('latexit', 'beamer') as $plugin) {
                if(isset($conf['plugin'][$plugin][$key])) {
                    $myconf[$key] = $conf['plugin'][$plugin][$key];
                }
            }
        }

        // some fixed config
        $myconf['document_class'] = 'beamer';

        $this->configloaded = true;
        $this->conf         = $myconf;
    }

    /**
     * read the plugin's default configuration settings from conf/default.php
     * this function is automatically called through getConf()
     *
     * This overrides the default function to load both latexit and beamer configs
     *
     * @return    array    setting => value
     */
    function readDefaultSettings() {
        $conf = array();
        foreach(array('latexit', 'beamer') as $plugin) {
            $path = DOKU_PLUGIN.$plugin.'/conf/';

            if(@file_exists($path.'default.php')) {
                include($path.'default.php');
            }
        }
        return $conf;
    }

}

