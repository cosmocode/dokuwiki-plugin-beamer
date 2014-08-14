<?php

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
* All DokuWiki plugins to extend the parser/rendering mechanism
* need to inherit from this class
*/
class syntax_plugin_beamer extends DokuWiki_Syntax_Plugin {

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'norml';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 300;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~BEAMER~~',$mode,'plugin_beamer');
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array();
    }

    /**
     * Create output
     */
    function render($format, Doku_Renderer $renderer, $data) {
        if($format != 'xhtml') return false;
        global $ID;
        global $REV;

        // display export button
        $renderer->doc .= '<a href="' . exportlink($ID, 'beamer', ($REV != '' ? 'rev=' . $REV : '')) . '" title="' . $this->getLang('view') . '">';
        $renderer->doc .= '<img src="' . DOKU_BASE . 'lib/plugins/beamer/beamer.png" align="right" alt="' . $this->getLang('view') . '" width="48" height="48" />';
        $renderer->doc .= '</a>';
        return true;
    }
}
