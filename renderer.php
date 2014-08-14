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

    /**
     * @var bool track if a slide is currently open
     */
    protected $inslide = false;

    /**
     * @var bool track if we're in the note part of a slide currently
     */
    protected $innote = false;

    /**
     * Handle the start of a document
     *
     * just adds beamer stuff to the preamble and disables caching
     */
    function document_start() {
        parent::document_start();

        // disable caching
        $this->info['cache'] = false;

        $this->store->addPreamble(array('usetheme', $this->getConf('beamer_theme')));
        $this->store->addPreamble(array('usecolortheme', $this->getConf('beamer_color')));
        $this->store->addPreamble(array('usefonttheme', $this->getConf('beamer_font')));

        $this->store->addPreamble('% comment this in to show notes:');
        $this->store->addPreamble('%\setbeameroption{show notes}');
        $this->store->addPreamble('\setbeamertemplate{note page}[plain]');

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
        if($this->inslide && $level <= $this->getConf('hllevel')) {
            // close note first
            if($this->innote) {
                $this->_n();
                $this->_close();
                $this->_n();
                $this->innote = false;
            }

            $this->_n();
            $this->_c('end', 'frame', 2);
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
            // close note first
            if($this->innote) {
                $this->_n();
                $this->_close();
                $this->_n();
                $this->innote = false;
            }

            $this->_n();
            $this->_c('end', 'frame');
            $this->inslide = false;
        }

        /** @var helper_plugin_latexit $hlp */
        $hlp = $this->loadHelper('latexit');
        $hlp->removePackage('hyperref'); // hyperref is is autoloaded by the beamer class

        parent::document_end();
    }

    /**
     * Lines start notes section
     */
    function hr() {
        if($this->innote) return; // we're in note section already
        $this->_n();
        $this->_open('note');
        $this->_n();
        $this->innote = true;
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

