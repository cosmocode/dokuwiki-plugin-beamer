<?php
/**
 * Options for the beamer plugin
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 */

$meta['hllevel'] = array('numeric');

$conf['beamer_theme'] = array(
    'multichoice',
    '_choices' => array(
        'AnnArbor',
        'Antibes',
        'Bergen',
        'Berkeley',
        'Berlin',
        'Boadilla',
        'boxes',
        'CambridgeUS',
        'Copenhagen',
        'Darmstadt',
        'default',
        'Dresden',
        'Frankfurt',
        'Goettingen',
        'Hannover',
        'Ilmenau',
        'JuanLesPins',
        'Luebeck',
        'Madrid',
        'Malmoe',
        'Marburg',
        'Montpellier',
        'PaloAlto',
        'Pittsburgh',
        'Rochester',
        'Singapore',
        'Szeged',
        'Warsaw'
    )
);

$conf['beamer_color'] = array(
    'multichoice',
    '_choices' => array(
        'albatross',
        'beaver',
        'beetle',
        'crane',
        'default',
        'dolphin',
        'dove',
        'fly',
        'lily',
        'orchid',
        'rose',
        'seagull',
        'seahorse',
        'sidebartab',
        'structure',
        'whale',
        'wolverine'
    )
);

$conf['beamer_font'] = array(
    'multichoice',
    '_choices' => array(
        'default',
        'professionalfonts',
        'serif',
        'structurebold',
        'structureitalicserif',
        'structuresmallcapsserif'
    )
);
